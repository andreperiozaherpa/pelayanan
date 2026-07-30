<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Models\MppService;
use App\Models\MppServiceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MppPengajuanController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        if (! ($user->hasPermission('service.report') || $user->hasPermission('service.verify') || $user->hasPermission('service.manage'))) {
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }

        $search = $request->query('search');
        $status = $request->query('status');
        $mppServiceId = $request->query('mpp_service_id');

        $query = MppServiceRequest::with(['mppService', 'frontOfficeUser'])->latest();

        if ($status) {
            $query->where('status', $status);
        }
        if ($mppServiceId) {
            $query->where('mpp_service_id', $mppServiceId);
        }
        if ($search) {
            $query->whereHas('mppService', fn ($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $mppRequests = $query->paginate(15)->withQueryString();
        $mppServices = MppService::orderBy('name')->get();

        return view('services.admin.mpp.daftar-pengajuan.index', compact('mppRequests', 'mppServices', 'status', 'mppServiceId', 'search'));
    }

    public function create(MppService $mppService): View
    {
        if (! $mppService->is_active) {
            abort(404, 'Pelayanan tidak aktif.');
        }

        return view('services.admin.mpp.daftar-pengajuan.create', compact('mppService'));
    }

    public function store(Request $request, MppService $mppService): RedirectResponse
    {
        if (! $mppService->is_active) {
            abort(404, 'Pelayanan tidak aktif.');
        }

        $rules = [
            'notes' => 'nullable|string',
        ];

        foreach ($mppService->fields as $field) {
            $fieldName = $field['name'];
            $fieldRules = [];

            if ($field['required'] ?? false) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            if ($field['type'] === 'number') {
                $fieldRules[] = 'numeric';
            } elseif ($field['type'] === 'file') {
                if ($request->hasFile("form_data.{$fieldName}")) {
                    $fieldRules[] = 'file';
                    $fieldRules[] = 'max:5120';
                } else {
                    $fieldRules[] = 'string';
                    $fieldRules[] = 'max:255';
                }
            } elseif ($field['type'] === 'select') {
                $fieldRules[] = 'in:'.implode(',', $field['options'] ?? []);
            } else {
                $fieldRules[] = 'string';
            }

            $rules['form_data.'.$fieldName] = $fieldRules;
        }

        $validated = $request->validate($rules);

        $submittedData = [];
        $formData = $request->input('form_data', []);

        foreach ($mppService->fields as $field) {
            $fieldName = $field['name'];

            if ($field['type'] === 'file') {
                if ($request->hasFile("form_data.{$fieldName}")) {
                    $file = $request->file("form_data.{$fieldName}");
                    $path = $file->store('mpp-attachments', 'public');
                    $submittedData[$fieldName] = [
                        'label' => $field['label'],
                        'type' => 'file',
                        'value' => $path,
                        'original_name' => $file->getClientOriginalName(),
                    ];
                } elseif (is_string($request->input("form_data.{$fieldName}")) && ! empty($request->input("form_data.{$fieldName}"))) {
                    $path = $request->input("form_data.{$fieldName}");
                    $submittedData[$fieldName] = [
                        'label' => $field['label'],
                        'type' => 'file',
                        'value' => $path,
                        'original_name' => basename($path),
                    ];
                } else {
                    $submittedData[$fieldName] = [
                        'label' => $field['label'],
                        'type' => 'file',
                        'value' => null,
                    ];
                }
            } else {
                $submittedData[$fieldName] = [
                    'label' => $field['label'],
                    'type' => $field['type'],
                    'value' => $formData[$fieldName] ?? null,
                ];
            }
        }

        $mppServiceRequest = DB::transaction(function () use ($request, $mppService, $submittedData) {
            return MppServiceRequest::create([
                'mpp_service_id' => $mppService->id,
                'front_office_user_id' => Auth::id(),
                'notes' => $request->notes,
                'submitted_form_data' => $submittedData,
                'status' => 'PENDING',
            ]);
        });

        Audit::log('SUBMIT_MPP_SERVICE_REQUEST', $mppServiceRequest, $mppServiceRequest->toArray());

        return redirect()->route('mpp-requests.index')
            ->with('success', "Permohonan untuk pelayanan {$mppService->name} berhasil dikirim.");
    }

    public function show(MppServiceRequest $mppServiceRequest): View
    {
        return view('services.admin.mpp.daftar-pengajuan.show', compact('mppServiceRequest'));
    }
}
