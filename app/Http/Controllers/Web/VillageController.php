<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\VillageRequest;
use App\Models\District;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return view('villages.index', compact('villages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $districts = District::orderBy('name')->get();

        return view('villages.create', compact('districts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VillageRequest $request)
    {
        $village = Village::create($request->validated());

        Auth::user()->recordAuditLog(
            action: 'CREATE_VILLAGE',
            table: 'villages',
            targetId: $village->id,
            newValue: $village->toArray()
        );

        return redirect()->route('villages.index')->with('success', "Desa {$village->name} berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Village $village)
    {
        $districts = District::orderBy('name')->get();

        return view('villages.edit', compact('village', 'districts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VillageRequest $request, Village $village)
    {
        $oldValue = $village->toArray();
        $village->update($request->validated());

        Auth::user()->recordAuditLog(
            action: 'UPDATE_VILLAGE',
            table: 'villages',
            targetId: $village->id,
            oldValue: $oldValue,
            newValue: $village->fresh()->toArray()
        );

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

        Auth::user()->recordAuditLog(
            action: 'DELETE_VILLAGE',
            table: 'villages',
            targetId: $village->id,
            oldValue: $oldValue
        );

        return redirect()->route('villages.index')->with('success', "Desa {$village->name} telah dihapus.");
    }
}
