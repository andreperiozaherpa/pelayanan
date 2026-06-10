<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\OpdRequest;
use App\Models\Opd;
use Illuminate\Http\Request;

class OpdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Opd::withCount('users');

        if ($request->has('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('code', 'like', '%'.$request->search.'%')
                ->orWhere('description', 'like', '%'.$request->search.'%');
        }

        $opds = $query->latest()->paginate(10);

        return view('master-data.opds.index', compact('opds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master-data.opds.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OpdRequest $request)
    {
        $opd = Opd::create($request->validated());

        Audit::log('CREATE_OPD', $opd, $opd->toArray());

        return redirect()->route('opds.index')->with('success', "OPD {$opd->name} berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Opd $opd)
    {
        return view('master-data.opds.edit', compact('opd'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OpdRequest $request, Opd $opd)
    {
        $oldValue = $opd->toArray();
        $opd->update($request->validated());

        Audit::log('UPDATE_OPD', $opd, $opd->fresh()->toArray(), $oldValue);

        return redirect()->route('opds.index')->with('success', "Data OPD {$opd->name} berhasil diperbarui.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Opd $opd)
    {
        if ($opd->users()->exists()) {
            return redirect()->back()->with('error', 'OPD tidak dapat dihapus karena masih memiliki pengguna terkait.');
        }

        $oldValue = $opd->toArray();
        $opd->delete();

        Audit::log('DELETE_OPD', $opd, null, $oldValue);

        return redirect()->route('opds.index')->with('success', "OPD {$opd->name} telah dihapus.");
    }
}
