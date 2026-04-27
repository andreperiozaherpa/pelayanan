<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Citizen;
use App\Models\VerificationLog;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;
use Spatie\Browsershot\Browsershot;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    /**
     * Display the Front Office dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Role-based Redirection
        if ($user->role->slug === 'operatordesa') {
            return redirect()->route('dashboard.desa');
        }

        // 2. Global Analytics (Blueprint 5)
        $totalVerifications = VerificationLog::count();
        $todayCount = VerificationLog::whereDate('timestamp', today())->count();

        // Verification breakdown by village
        $villageBreakdown = \App\Models\Village::withCount(['citizens as verifications_count' => function ($q) {
            $q->whereHas('verificationLogs');
        }])
            ->orderByDesc('verifications_count')
            ->take(6)
            ->get();

        // Poverty status distribution
        $statusDistribution = [
            'active' => \App\Models\PovertyRecord::where('status', 'ACTIVE')->count(),
            'expired' => \App\Models\PovertyRecord::where('status', 'EXPIRED')->count()
                + \App\Models\PovertyRecord::where('status', 'ACTIVE')->where('valid_until', '<', now())->count(),
            'pending' => Citizen::whereDoesntHave('povertyRecords')->count(),
        ];

        $stats = [
            'total_verifications' => $totalVerifications,
            'recent_requests' => ServiceRequest::with('citizen.village')->latest()->take(6)->get(),
            'today_count' => $todayCount,
            'village_breakdown' => $villageBreakdown,
            'status_distribution' => $statusDistribution
        ];

        return view('services.dashboard.index', compact('stats'));
    }

    public function desa(): View
    {
        $user = Auth::user();
        // Fallback to 0 if desa_id is magically null (though guarded by middleware)
        $desaId = $user->desa_id ?? 0;

        $stats = [
            'total_verifications' => VerificationLog::where(function ($q) use ($desaId) {
                // Actions by village operators OR regarding village citizens
                $q->whereHas('user', fn ($u) => $u->where('desa_id', $desaId))
                    ->orWhereHas('citizen', fn ($c) => $c->where('desa_id', $desaId));
            })->count(),
            'recent_requests' => ServiceRequest::whereHas('citizen', function ($q) use ($desaId) {
                $q->where('desa_id', $desaId);
            })->latest()->take(5)->get(),
            'today_count' => ServiceRequest::whereHas('citizen', function ($q) use ($desaId) {
                $q->where('desa_id', $desaId);
            })->whereDate('created_at', today())->count(),
        ];

        return view('services.dashboard.desa', compact('stats'));
    }

    /**
     * Display the verification page.
     */
    public function verify(): View
    {
        return view('services.verification.index');
    }

    /**
     * Display the unified history of verifications and system audits.
     */
    public function history(Request $request): View
    {
        $user = Auth::user();
        $isOperatorDesa = $user->role->slug === 'operatordesa';
        $desaId = $user->desa_id;

        $search = $request->query('search');
        $perPage = (int) $request->query('per_page', 15);
        $page = max(1, (int) $request->query('page', 1));

        // 1. Get Verification Logs
        $verifications = VerificationLog::with(['user', 'citizen'])
            ->when($isOperatorDesa, function ($q) use ($desaId) {
                $q->where(function ($query) use ($desaId) {
                    // Show logs performed BY village users OR involving village citizens
                    $query->whereHas('user', fn ($u) => $u->where('desa_id', $desaId))
                        ->orWhereHas('citizen', fn ($c) => $c->where('desa_id', $desaId));
                });
            })
            ->when($search, function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%");
            })
            ->latest('timestamp')
            ->take(500)
            ->get()
            ->map(function ($log) {
                return [
                    'type' => 'VERIFICATION',
                    'timestamp' => $log->timestamp,
                    'date' => $log->timestamp->format('Y-m-d'),
                    'user' => $log->user->name ?? 'Sistem',
                    'action' => 'Melakukan Verifikasi',
                    'target' => $log->nik,
                    'result' => $log->result,
                    'details' => "Metode: {$log->method}, IP: {$log->ip_address}",
                    'icon' => '🔍'
                ];
            });

        // 2. Get Audit Logs
        $audits = \App\Models\AuditLog::with('user')
            ->when($isOperatorDesa, function ($q) use ($desaId) {
                $q->where(function ($query) use ($desaId) {
                    // Logs performed by village users
                    $query->whereHas('user', fn ($u) => $u->where('desa_id', $desaId))
                        // OR Logs regarding village citizens (Print Proof, etc)
                        ->orWhere(function ($sub) use ($desaId) {
                            $sub->where('target_table', 'citizens')
                                ->whereIn('new_value->nik', Citizen::where('desa_id', $desaId)->pluck('nik'));
                        })
                        // OR Logs regarding village service requests
                        ->orWhere(function ($sub) use ($desaId) {
                            $sub->where('target_table', 'service_requests')
                                ->whereExists(function ($ex) use ($desaId) {
                                    $ex->selectRaw(1)->from('service_requests')
                                        ->join('citizens', 'service_requests.citizen_nik', '=', 'citizens.nik')
                                        ->whereColumn('service_requests.id', 'audit_logs.target_id')
                                        ->where('citizens.desa_id', $desaId);
                                });
                        });
                });
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('action', 'like', "%{$search}%")
                        ->orWhere('new_value', 'like', "%{$search}%");
                });
            })
            ->latest('timestamp')
            ->take(500)
            ->get()
            ->map(function ($log) {
                $verbs = [
                    'LOGIN' => 'Masuk ke Sistem',
                    'LOGOUT' => 'Keluar dari Sistem',
                    'PRINT_PROOF' => 'Mencetak Bukti Verifikasi',
                    'REPORT_SERVICE' => 'Melaporkan Layanan',
                ];

                $target = '-';
                if (is_array($log->new_value)) {
                    $target = $log->new_value['nik'] ?? ($log->new_value['citizen_nik'] ?? '-');
                }

                return [
                    'type' => 'AUDIT',
                    'timestamp' => $log->timestamp,
                    'date' => $log->timestamp->format('Y-m-d'),
                    'user' => $log->user->name ?? 'Sistem',
                    'action' => $verbs[$log->action] ?? $log->action,
                    'target' => $target,
                    'result' => null,
                    'details' => $log->action === 'REPORT_SERVICE' && is_array($log->new_value) ? ($log->new_value['service_type'] ?? '') : '',
                    'icon' => $log->action === 'PRINT_PROOF' ? '📄' : ($log->action === 'REPORT_SERVICE' ? '✅' : '🔒')
                ];
            });

        // 3. Merge and Sort
        $allLogs = $verifications->concat($audits)->sortByDesc('timestamp');

        // 4. Manual Pagination
        $paginatedItems = $allLogs->forPage($page, $perPage);
        $logs = new LengthAwarePaginator(
            $paginatedItems,
            $allLogs->count(),
            $perPage,
            $page,
            ['path' => $request->url()]
        );

        // Crucial fix for synchronization: Ensure all query parameters are preserved
        $logs->withQueryString();

        // 5. Group the current page's logs by date for rendering
        $groupedLogs = $paginatedItems->groupBy('date');

        return view('services.histories.index', [
            'logs' => $groupedLogs,
            'paginator' => $logs,
            'search' => $search,
            'perPage' => $perPage
        ]);
    }

    /**
     * Generate a high-fidelity PDF proof of verification.
     */
    public function proof(string $nik)
    {
        // 1. Authorization
        Gate::authorize('poverty.print_proof');

        $citizen = Citizen::with(['village', 'povertyRecords' => function ($q) {
            $q->latest();
        }])->where('nik', $nik)->firstOrFail();

        $record = $citizen->povertyRecords->first();

        // Generate a validation URL
        $validationUrl = route('dashboard.verify', ['nik' => $nik]);

        // Audit Log
        \App\Models\AuditLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'PRINT_PROOF',
            'target_table' => 'citizens',
            'target_id' => null,
            'new_value' => ['nik' => $nik],
            'timestamp' => now()
        ]);

        $html = view('documents.doc_poverty', compact('citizen', 'record', 'validationUrl'))->render();

        $pdf = Browsershot::html($html)
            ->setNodeBinary('/usr/bin/node')
            ->setNpmBinary('/usr/bin/npm')
            ->setChromePath('/usr/bin/chromium')
            ->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
            ->format('A5')
            ->margins(0, 0, 0, 0)
            ->pdf();

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="bukti-verifikasi-' . $nik . '.pdf"');
    }
}
