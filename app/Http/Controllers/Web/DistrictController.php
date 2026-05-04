<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\DistrictRequest;
use App\Models\District;
use Illuminate\Http\Request;

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

        Audit::log('CREATE_DISTRICT', $district, $district->toArray());

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

        Audit::log('UPDATE_DISTRICT', $district, $district->fresh()->toArray(), $oldValue);

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

        Audit::log('DELETE_DISTRICT', $district, null, $oldValue);

        return redirect()->route('districts.index')->with('success', "Kecamatan {$district->name} telah dihapus.");
    }
}
