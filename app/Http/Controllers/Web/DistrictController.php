<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\DistrictRequest;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = District::withCount('villages');

        if ($request->has('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('code', 'like', '%'.$request->search.'%')
                ->orWhere('regency_name', 'like', '%'.$request->search.'%');
        }

        $districts = $query->latest()->paginate(10);

        return view('master-data.districts.index', compact('districts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master-data.districts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DistrictRequest $request)
    {
        $district = District::create($request->validated());

        Auth::user()->recordAuditLog(
            action: 'CREATE_DISTRICT',
            table: 'districts',
            targetId: $district->id,
            newValue: $district->toArray()
        );

        return redirect()->route('districts.index')->with('success', "Kecamatan {$district->name} berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(District $district)
    {
        return view('master-data.districts.edit', compact('district'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DistrictRequest $request, District $district)
    {
        $oldValue = $district->toArray();
        $district->update($request->validated());

        Auth::user()->recordAuditLog(
            action: 'UPDATE_DISTRICT',
            table: 'districts',
            targetId: $district->id,
            oldValue: $oldValue,
            newValue: $district->fresh()->toArray()
        );

        return redirect()->route('districts.index')->with('success', "Data kecamatan {$district->name} berhasil diperbarui.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(District $district)
    {
        if ($district->villages()->exists()) {
            return redirect()->back()->with('error', 'Kecamatan tidak dapat dihapus karena masih memiliki desa terkait.');
        }

        $oldValue = $district->toArray();
        $district->delete();

        Auth::user()->recordAuditLog(
            action: 'DELETE_DISTRICT',
            table: 'districts',
            targetId: $district->id,
            oldValue: $oldValue
        );

        return redirect()->route('districts.index')->with('success', "Kecamatan {$district->name} telah dihapus.");
    }
}
