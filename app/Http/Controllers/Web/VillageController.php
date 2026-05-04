<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\VillageRequest;
use App\Models\District;
use App\Models\Village;
use Illuminate\Http\Request;

class VillageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Village::with('district');

        if ($request->has('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('code', 'like', '%'.$request->search.'%')
                ->orWhereHas('district', function ($q) use ($request) {
                    $q->where('name', 'like', '%'.$request->search.'%');
                });
        }

        $villages = $query->latest()->paginate(10);

        return view('master-data.villages.index', compact('villages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $districts = District::orderBy('name')->get();

        return view('master-data.villages.create', compact('districts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VillageRequest $request)
    {
        $village = Village::create($request->validated());

        Audit::log('CREATE_VILLAGE', $village, $village->toArray());

        return redirect()->route('villages.index')->with('success', "Desa {$village->name} berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Village $village)
    {
        $districts = District::orderBy('name')->get();

        return view('master-data.villages.edit', compact('village', 'districts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VillageRequest $request, Village $village)
    {
        $oldValue = $village->toArray();
        $village->update($request->validated());

        Audit::log('UPDATE_VILLAGE', $village, $village->fresh()->toArray(), $oldValue);

        return redirect()->route('villages.index')->with('success', "Data desa {$village->name} berhasil diperbarui.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Village $village)
    {
        if ($village->citizens()->exists() || $village->users()->exists()) {
            return redirect()->back()->with('error', 'Desa tidak dapat dihapus karena masih memiliki data warga atau pengguna terkait.');
        }

        $oldValue = $village->toArray();
        $village->delete();

        Audit::log('DELETE_VILLAGE', $village, null, $oldValue);

        return redirect()->route('villages.index')->with('success', "Desa {$village->name} telah dihapus.");
    }
}
