<?php

namespace App\Http\Controllers\Web;

use App\Enums\ServiceType;
use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Models\ArrivalRecord;
use App\Models\AuditLog;
use App\Models\Citizen;
use App\Models\DeathRecord;
use App\Models\DomicileRecord;
use App\Models\MoveRecord;
use App\Models\PovertyRecord;
use App\Models\ServiceRequest;
use App\Models\Village;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ServiceController extends Controller
{
    /**
     * Report a service provided to a citizen.
     */
    public function store(Request $request)
    {
        Gate::authorize('service.report');

        $request->validate([
            'citizen_nik' => 'required|string|size:16|exists:citizens,nik',
            'service_type' => ['required', 'string', 'max:255', 'in:'.implode(',', ServiceType::values())],
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        if ($user->isOperatorDesa()) {
            $citizen = Citizen::where('nik', $request->citizen_nik)->first();
            if ($citizen->desa_id != $user->desa_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki hak untuk memberikan layanan di luar wilayah tugas Anda.',
                ], 403);
            }
        }

        // Global Check: If citizen is marked as DECEASED (DeathRecord ACTIVE), block ALL services
        $deathActive = DeathRecord::where('citizen_nik', $request->citizen_nik)
            ->where('status', 'ACTIVE')
            ->exists();

        if ($deathActive) {
            return response()->json([
                'success' => false,
                'message' => 'Layanan tidak dapat diberikan karena warga ini sudah terdaftar sebagai penduduk yang sudah meninggal.',
            ], 422);
        }

        // Security Hardening: Atomic check and create to prevent race conditions
        $isPoverty = $request->service_type == ServiceType::POVERTY->value;
        $isDomicile = $request->service_type == ServiceType::DOMICILE->value;
        $isMove = $request->service_type == ServiceType::MOVE->value;
        $isDeath = $request->service_type == ServiceType::DEATH->value;

        if ($isPoverty || $isDomicile || $isMove || $isDeath) {
            // 1. Unified Record Check
            $record = match (true) {
                $isPoverty => PovertyRecord::where('citizen_nik', $request->citizen_nik)->latest()->first(),
                $isDomicile => DomicileRecord::where('citizen_nik', $request->citizen_nik)->latest()->first(),
                $isMove => MoveRecord::where('citizen_nik', $request->citizen_nik)->latest()->first(),
                $isDeath => DeathRecord::where('citizen_nik', $request->citizen_nik)->latest()->first(),
                default => null
            };

            if ($record) {
                $isExpired = $record->valid_until && Carbon::parse($record->valid_until)->isPast();
                $isRejected = $record->status === 'REJECTED';

                // Block if status is PENDING or ACTIVE (and not expired)
                if ($record->status === 'PENDING') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Permohonan layanan ini sedang dalam proses verifikasi desa.',
                    ], 422);
                }

                if ($record->status === 'ACTIVE' && ! $isExpired) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Warga ini sudah memiliki layanan yang masih aktif.',
                    ], 422);
                }
            }

            return DB::transaction(function () use ($request, $isPoverty, $isDomicile, $isMove, $isDeath) {
                // Keep the daily duplicate check as a fallback for the ServiceRequest table itself
                $exists = ServiceRequest::where('citizen_nik', $request->citizen_nik)
                    ->where('service_type', $request->service_type)
                    ->whereDate('created_at', now()->toDateString())
                    ->where('status', 'PENDING')
                    ->lockForUpdate()
                    ->exists();

                if ($exists) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Layanan ini sudah dilaporkan untuk warga tersebut hari ini.',
                    ], 422);
                }

                $finalNotes = $request->notes;
                if ($isMove && $request->destination_address) {
                    $finalNotes = "TUJUAN: {$request->destination_address}".($request->notes ? "\nCATATAN: {$request->notes}" : '');
                } elseif ($isDeath && $request->date_of_death) {
                    $dateOfDeath = Carbon::parse($request->date_of_death)->translatedFormat('d F Y');
                    $finalNotes = "TANGGAL: {$dateOfDeath}\nLOKASI: ".($request->place_of_death ?? '-')."\nPENYEBAB: ".($request->cause_of_death ?? '-').($request->notes ? "\nCATATAN: {$request->notes}" : '');
                }

                $service = ServiceRequest::create([
                    'citizen_nik' => $request->citizen_nik,
                    'service_type' => $request->service_type,
                    'status' => 'PENDING',
                    'front_office_user_id' => Auth::user()->id,
                    'notes' => $finalNotes,
                ]);

                if ($isPoverty) {
                    PovertyRecord::updateOrCreate(
                        ['citizen_nik' => $request->citizen_nik],
                        ['status' => 'PENDING', 'source' => 'FRONT_OFFICE_REQUEST']
                    );
                } elseif ($isDomicile) {
                    DomicileRecord::updateOrCreate(
                        ['citizen_nik' => $request->citizen_nik],
                        ['status' => 'PENDING', 'source' => 'FRONT_OFFICE_REQUEST']
                    );
                } elseif ($isMove) {
                    MoveRecord::updateOrCreate(
                        ['citizen_nik' => $request->citizen_nik],
                        ['status' => 'PENDING', 'source' => 'FRONT_OFFICE_REQUEST']
                    );
                } elseif ($isDeath) {
                    DeathRecord::updateOrCreate(
                        ['citizen_nik' => $request->citizen_nik],
                        [
                            'status' => 'PENDING',
                            'source' => 'FRONT_OFFICE_REQUEST',
                            'date_of_death' => $request->date_of_death,
                            'place_of_death' => $request->place_of_death,
                            'cause_of_death' => $request->cause_of_death,
                        ]
                    );
                }

                Cache::forget("citizen_services_{$request->citizen_nik}");

                Audit::log('REPORT_SERVICE', $service, $service->toArray());

                return response()->json([
                    'success' => true,
                    'message' => 'Pelayanan berhasil dicatat.',
                ]);
            });
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Pelayanan Belum Tersedia.',
            ], 404);
        }
    }

    /**
     * Show the form for creating a new arrival record.
     */
    public function arrivalCreate(): View
    {
        Gate::authorize('service.report');

        return view('services.admin.arrival.create');
    }

    /**
     * Register a new arrival (citizen move-in).
     */
    public function arrivalStore(Request $request)
    {
        Gate::authorize('service.report');

        $request->validate([
            'nik' => 'required|string|size:16',
            'nama_lengkap' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'alamat_desa' => 'required|string',
            'desa_id' => 'required|exists:villages,id',
            'previous_address' => 'required|string',
            'arrival_date' => 'required|date',
            'kontak' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {
            // 1. Create or Update Citizen
            $citizen = Citizen::updateOrCreate(
                ['nik' => $request->nik],
                [
                    'nama_lengkap' => $request->nama_lengkap,
                    'tgl_lahir' => $request->tgl_lahir,
                    'alamat_desa' => $request->alamat_desa,
                    'desa_id' => $request->desa_id,
                    'kontak' => $request->kontak,
                ]
            );

            // 2. Create Service Request for audit
            $service = ServiceRequest::create([
                'citizen_nik' => $citizen->nik,
                'service_type' => ServiceType::ARRIVAL->value,
                'status' => 'APPROVED', // Arrival from FO is immediately approved/active
                'front_office_user_id' => Auth::user()->id,
                'notes' => "DARI: {$request->previous_address}\nDATANG: ".Carbon::parse($request->arrival_date)->format('d/m/Y').($request->notes ? "\nCATATAN: {$request->notes}" : ''),
            ]);

            // 3. Create Arrival Record
            $arrival = ArrivalRecord::create([
                'citizen_nik' => $citizen->nik,
                'status' => 'ACTIVE',
                'previous_address' => $request->previous_address,
                'arrival_date' => $request->arrival_date,
                'recorded_by' => Auth::user()->id,
                'notes' => $request->notes,
            ]);

            Cache::forget("citizen_services_{$citizen->nik}");

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'REPORT_ARRIVAL',
                'target_table' => 'arrival_records',
                'target_id' => $arrival->id,
                'new_value' => $arrival->toArray(),
                'timestamp' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Laporan kedatangan warga berhasil dicatat.',
            ]);
        });
    }

    /**
     * Display a listing of pending service requests for the village.
     */
    public function index(Request $request)
    {
        Gate::authorize('service.manage');

        $user = Auth::user();
        $tab = $request->get('tab', 'pending');

        // Base query for pending requests
        $pendingQuery = ServiceRequest::with(['citizen', 'citizen.village'])->where('status', 'PENDING');
        if (! $user->isSuperAdmin()) {
            $pendingQuery->whereHas('citizen', fn ($q) => $q->where('desa_id', $user->desa_id));
        }

        // Base query for arrivals
        $arrivalQuery = ArrivalRecord::with(['citizen', 'recorder']);
        if (! $user->isSuperAdmin()) {
            $arrivalQuery->whereHas('citizen', fn ($q) => $q->where('desa_id', $user->desa_id));
        }

        // Totals for header/tabs
        $pendingCount = $pendingQuery->count();
        $arrivalsCount = $arrivalQuery->count();

        if ($tab === 'arrivals') {
            $arrivals = $arrivalQuery->latest()->paginate(15);
            $requests = $pendingQuery->paginate(1); // To avoid undefined $requests in header

            return view('services.desa.requests.index', compact('arrivals', 'requests', 'tab', 'pendingCount', 'arrivalsCount'));
        }

        // Type Filtering (only for pending tab)
        if ($request->has('type')) {
            $pendingQuery->where('service_type', $request->type);
        }

        $requests = $pendingQuery->latest()->paginate(15);
        $arrivals = new LengthAwarePaginator([], 0, 15);

        return view('services.desa.requests.index', compact('requests', 'arrivals', 'tab', 'pendingCount', 'arrivalsCount'));
    }

    /**
     * Display a listing of all service requests with filtering.
     */
    public function allRequests(Request $request): View
    {
        $user = Auth::user();
        if (! ($user->hasPermission('service.report') || $user->hasPermission('service.verify') || $user->hasPermission('service.manage'))) {
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }

        $search = $request->query('search');
        $status = $request->query('status');
        $type = $request->query('type');
        $villageId = $request->query('village_id');

        $query = ServiceRequest::with(['citizen', 'citizen.village', 'frontOfficeUser']);

        // Scope to user's village if they are Operator Desa
        if ($user->isOperatorDesa() && $user->desa_id) {
            $query->whereHas('citizen', fn ($q) => $q->where('desa_id', $user->desa_id));
        } elseif ($villageId) {
            // Non-village operators can filter by village
            $query->whereHas('citizen', fn ($q) => $q->where('desa_id', $villageId));
        }

        // Filters
        if ($status) {
            $query->where('status', $status);
        }
        if ($type) {
            $query->where('service_type', $type);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('citizen_nik', 'like', "%{$search}%")
                    ->orWhereHas('citizen', function ($qc) use ($search) {
                        $qc->where('nama_lengkap', 'like', "%{$search}%");
                    });
            });
        }

        $requests = $query->latest()->paginate(15)->withQueryString();

        // Get villages for filter dropdown if user is admin/OPD/FO
        $villages = [];
        if (! $user->isOperatorDesa()) {
            $villages = Village::orderBy('name')->get();
        }

        return view('services.admin.requests.all', compact('requests', 'villages', 'status', 'type', 'search', 'villageId'));
    }

    /**
     * Approve a service request and activate poverty record.
     */
    public function approve(Request $request, ServiceRequest $serviceRequest)
    {
        Gate::authorize('service.manage');

        // Strict protection: Only the specific village authority can approve
        if ($serviceRequest->citizen->desa_id !== Auth::user()->desa_id) {
            abort(403, 'Persetujuan hanya dapat dilakukan oleh otoritas desa setempat. Super Admin hanya memiliki akses pemantauan.');
        }

        $validationRules = [
            'signed_pdf_path' => 'nullable|string|max:2048',
            'letter_number' => 'nullable|string|max:255',
        ];

        if ($serviceRequest->service_type !== ServiceType::DEATH) {
            $validationRules['valid_until'] = 'required|date|after:today';
        }

        if ($serviceRequest->service_type === ServiceType::POVERTY) {
            $validationRules['income_range'] = 'required|string';
        }

        if ($serviceRequest->service_type === ServiceType::DOMICILE) {
            $validationRules['purpose'] = 'required|string';
        }

        if ($serviceRequest->service_type === ServiceType::MOVE) {
            $validationRules['destination_address'] = 'required|string';
            $validationRules['reason'] = 'required|string';
        }

        $request->validate($validationRules);

        return DB::transaction(function () use ($request, $serviceRequest) {
            $serviceRequest->update(['status' => 'APPROVED']);

            if ($serviceRequest->service_type === ServiceType::POVERTY) {
                $povertyData = [
                    'status' => 'ACTIVE',
                    'income_range' => $request->income_range,
                    'valid_from' => now(),
                    'valid_until' => $request->valid_until,
                    'verified_by' => Auth::user()->id,
                    'source' => 'VILLAGE_VERIFICATION',
                ];
                if ($request->filled('signed_pdf_path')) {
                    $povertyData['signed_pdf_path'] = $request->signed_pdf_path;
                }
                if ($request->filled('letter_number')) {
                    $povertyData['letter_number'] = $request->letter_number;
                }
                PovertyRecord::updateOrCreate(
                    ['citizen_nik' => $serviceRequest->citizen_nik],
                    $povertyData
                );
                Cache::forget("citizen_services_{$serviceRequest->citizen_nik}");
            } elseif ($serviceRequest->service_type === ServiceType::DOMICILE) {
                $domicileData = [
                    'status' => 'ACTIVE',
                    'purpose' => $request->purpose,
                    'valid_from' => now(),
                    'valid_until' => $request->valid_until,
                    'verified_by' => Auth::user()->id,
                    'source' => 'VILLAGE_VERIFICATION',
                ];
                if ($request->filled('signed_pdf_path')) {
                    $domicileData['signed_pdf_path'] = $request->signed_pdf_path;
                }
                if ($request->filled('letter_number')) {
                    $domicileData['letter_number'] = $request->letter_number;
                }
                DomicileRecord::updateOrCreate(
                    ['citizen_nik' => $serviceRequest->citizen_nik],
                    $domicileData
                );
                Cache::forget("citizen_services_{$serviceRequest->citizen_nik}");
            } elseif ($serviceRequest->service_type === ServiceType::MOVE) {
                $moveData = [
                    'status' => 'ACTIVE',
                    'destination_address' => $request->destination_address,
                    'reason' => $request->reason,
                    'valid_until' => $request->valid_until,
                    'verified_by' => Auth::user()->id,
                    'issued_at' => now(),
                ];
                if ($request->filled('signed_pdf_path')) {
                    $moveData['signed_pdf_path'] = $request->signed_pdf_path;
                }
                if ($request->filled('letter_number')) {
                    $moveData['letter_number'] = $request->letter_number;
                }
                MoveRecord::updateOrCreate(
                    ['citizen_nik' => $serviceRequest->citizen_nik],
                    $moveData
                );
                DomicileRecord::where('citizen_nik', $serviceRequest->citizen_nik)
                    ->update(['status' => 'EXPIRED']);

                ServiceRequest::where('citizen_nik', $serviceRequest->citizen_nik)
                    ->where('service_type', ServiceType::DOMICILE)
                    ->where('status', 'PENDING')
                    ->update(['status' => 'EXPIRED', 'notes' => 'Otomatis dibatalkan karena pelaporan keluar wilayah.']);

                Cache::forget("citizen_services_{$serviceRequest->citizen_nik}");
            } elseif ($serviceRequest->service_type === ServiceType::DEATH) {
                $deathData = [
                    'status' => 'ACTIVE',
                    'verified_by' => Auth::user()->id,
                    'issued_at' => now(),
                ];
                if ($request->filled('signed_pdf_path')) {
                    $deathData['signed_pdf_path'] = $request->signed_pdf_path;
                }
                if ($request->filled('letter_number')) {
                    $deathData['letter_number'] = $request->letter_number;
                }
                DeathRecord::updateOrCreate(
                    ['citizen_nik' => $serviceRequest->citizen_nik],
                    $deathData
                );

                // Set all other services to EXPIRED for this citizen
                PovertyRecord::where('citizen_nik', $serviceRequest->citizen_nik)
                    ->update(['status' => 'EXPIRED']);
                DomicileRecord::where('citizen_nik', $serviceRequest->citizen_nik)
                    ->update(['status' => 'EXPIRED']);
                MoveRecord::where('citizen_nik', $serviceRequest->citizen_nik)
                    ->update(['status' => 'EXPIRED']);

                // Also cancel any other pending service requests
                ServiceRequest::where('citizen_nik', $serviceRequest->citizen_nik)
                    ->where('id', '!=', $serviceRequest->id)
                    ->where('status', 'PENDING')
                    ->update(['status' => 'EXPIRED', 'notes' => 'Otomatis dibatalkan karena pelaporan kematian.']);

                Cache::forget("citizen_services_{$serviceRequest->citizen_nik}");
            }

            Audit::log('APPROVE_SERVICE', $serviceRequest, [
                'citizen_nik' => $serviceRequest->citizen_nik,
                'service_type' => $serviceRequest->service_type,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permohonan berhasil disetujui dan status warga diaktifkan.',
            ]);
        });
    }

    /**
     * Reject a service request.
     */
    public function reject(Request $request, ServiceRequest $serviceRequest)
    {
        Gate::authorize('service.manage');

        // Strict protection: Only the specific village authority can reject
        if ($serviceRequest->citizen->desa_id !== Auth::user()->desa_id) {
            abort(403, 'Penolakan hanya dapat dilakukan oleh otoritas desa setempat.');
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        return DB::transaction(function () use ($request, $serviceRequest) {
            $serviceRequest->update([
                'status' => 'REJECTED',
                'notes' => $request->reason, // Save the rejection reason to notes for easy display
            ]);

            // Update Records if they were PENDING
            if ($serviceRequest->service_type === ServiceType::POVERTY) {
                PovertyRecord::where('citizen_nik', $serviceRequest->citizen_nik)
                    ->where('status', 'PENDING')
                    ->update(['status' => 'REJECTED']);
                Cache::forget("citizen_services_{$serviceRequest->citizen_nik}");
            } elseif ($serviceRequest->service_type === ServiceType::DOMICILE) {
                DomicileRecord::where('citizen_nik', $serviceRequest->citizen_nik)
                    ->where('status', 'PENDING')
                    ->update(['status' => 'REJECTED']);
                Cache::forget("citizen_services_{$serviceRequest->citizen_nik}");
            } elseif ($serviceRequest->service_type === ServiceType::MOVE) {
                MoveRecord::where('citizen_nik', $serviceRequest->citizen_nik)
                    ->where('status', 'PENDING')
                    ->update(['status' => 'REJECTED']);
                Cache::forget("citizen_services_{$serviceRequest->citizen_nik}");
            } elseif ($serviceRequest->service_type === ServiceType::DEATH) {
                DeathRecord::where('citizen_nik', $serviceRequest->citizen_nik)
                    ->where('status', 'PENDING')
                    ->update(['status' => 'REJECTED']);
                Cache::forget("citizen_services_{$serviceRequest->citizen_nik}");
            }

            Audit::log('REJECT_SERVICE', $serviceRequest, [
                'reason' => $request->reason,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permohonan telah ditolak.',
            ]);
        });
    }
}
