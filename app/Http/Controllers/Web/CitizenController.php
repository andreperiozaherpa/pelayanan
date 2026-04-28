<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CitizenFormRequest;
use App\Models\Citizen;
use App\Models\Village;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CitizenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $search = $request->query('search');

        $query = Citizen::with(['village', 'povertyRecords']);

        // Data Isolation Rule: OperatorDesa only sees their own village.
        if ($user->role->slug === 'operatordesa') {
            $query->where('desa_id', $user->desa_id);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                    ->orWhere('nama_lengkap', 'like', "%{$search}%");
            });
        }

        $citizens = $query->latest()->paginate(15)->withQueryString();

        return view('master-data.citizens.index', compact('citizens', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = Auth::user();
        $villages = [];

        // Super Admin needs to select a village manually. Operator Desa uses their assigned village.
        if ($user->role->slug === 'superadmin') {
            $villages = Village::orderBy('name')->get();
        }

        return view('master-data.citizens.form', compact('villages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CitizenFormRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $validated = $request->validated();

        // Enforce Data Isolation
        if ($user->role->slug === 'operatordesa') {
            $validated['desa_id'] = $user->desa_id;
        } else {
            // Super Admin must provide it
            $request->validate(['desa_id' => 'required|exists:villages,id']);
            $validated['desa_id'] = $request->input('desa_id');
        }

        // Create Citizen
        $citizen = Citizen::create($validated);

        // Handle nested Poverty Record if required
        if (! empty($validated['has_poverty_record'])) {
            $this->syncPovertyRecord($citizen, $validated, $user->id);
        }

        return redirect()->route('citizens.index')
            ->with('success', 'Data Warga berhasil ditambah.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Citizen $citizen): View
    {
        $user = Auth::user();

        // Enforce Data Isolation
        if ($user->role->slug === 'operatordesa') {
            abort_if($citizen->desa_id !== $user->desa_id, 403, 'Anda tidak diizinkan mengubah data warga di luar wilayah Anda.');
        }

        $villages = [];
        if ($user->role->slug === 'superadmin') {
            $villages = Village::orderBy('name')->get();
        }

        // Preload Poverty status if exists
        $povertyRecord = $citizen->povertyRecords()->latest()->first();

        return view('master-data.citizens.form', compact('citizen', 'villages', 'povertyRecord'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CitizenFormRequest $request, Citizen $citizen): RedirectResponse
    {
        $user = Auth::user();

        // Enforce Data Isolation
        if ($user->role->slug === 'operatordesa') {
            abort_if($citizen->desa_id !== $user->desa_id, 403, 'Akses ditolak.');
        }

        $validated = $request->validated();

        if ($user->role->slug === 'operatordesa') {
            // Re-enforce avoiding overrides via manipulated payloads
            $validated['desa_id'] = $user->desa_id;
        } else {
            $request->validate(['desa_id' => 'required|exists:villages,id']);
            $validated['desa_id'] = $request->input('desa_id');
        }

        $citizen->update($validated);

        if (! empty($validated['has_poverty_record'])) {
            $this->syncPovertyRecord($citizen, $validated, $user->id);
        }

        return redirect()->route('citizens.index')
            ->with('success', 'Data Warga berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Citizen $citizen): RedirectResponse
    {
        $user = Auth::user();

        if ($user->role->slug === 'operatordesa') {
            abort_if($citizen->desa_id !== $user->desa_id, 403, 'Akses ditolak.');
        }

        $nik = $citizen->nik;
        $nama = $citizen->nama_lengkap;

        $citizen->delete();

        return redirect()->route('citizens.index')
            ->with('success', 'Data Warga berhasil dihapus.');
    }

    /**
     * Helper to sync poverty records ensuring the 3 month validity.
     */
    private function syncPovertyRecord(Citizen $citizen, array $data, int $userId): void
    {
        $validFrom = Carbon::parse($data['valid_from']);
        // Crucial Business Rule: Force exact 3 month validity from start date.
        $validUntil = $validFrom->copy()->addMonths(3);

        $citizen->povertyRecords()->updateOrCreate(
            [
                // If there's an existing active record, we might just create a new one, but for simplicity:
                // We update the latest one, or we just insert a new line. Given updateOrCreate requires unique constraints,
                // let's do a simple update or create based on NIK. If one exists, update it. If we want history, we should create new.
                // Let's just update the most recent one or create.
                'citizen_nik' => $citizen->nik,
            ],
            [
                'status' => $data['poverty_status'],
                'income_range' => $data['income_range'],
                'valid_from' => $validFrom->format('Y-m-d'),
                'valid_until' => $validUntil->format('Y-m-d'),
                'verified_by' => $userId,
                'source' => $data['source'],
            ]
        );
    }
}
