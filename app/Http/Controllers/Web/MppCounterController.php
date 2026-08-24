<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Gerai;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MppCounterController extends Controller
{
    public function index(Request $request): View
    {
        $query = Counter::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('code', 'like', '%'.$request->search.'%')
                ->orWhere('description', 'like', '%'.$request->search.'%');
        }

        $counters = $query->with('gerai.opd')->withCount('users')->orderByRaw('CAST(code AS UNSIGNED)')->paginate(10);

        return view('services.admin.mpp.daftar-loket.index', compact('counters'));
    }

    public function create(): View
    {
        $gerais = Gerai::with('opd')->orderBy('code')->get();

        return view('services.admin.mpp.daftar-loket.create', compact('gerais'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'gerai_id' => 'required|exists:mpp_gerais,id',
            'code' => 'required|string|max:10|unique:mpp_counters,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $counter = Counter::create($validated);

        Audit::log('CREATE_LOKET', $counter, $counter->toArray());

        return redirect()->route('counters.index')
            ->with('success', "Loket {$counter->name} berhasil ditambahkan.");
    }

    public function edit(Counter $counter): View
    {
        $gerais = Gerai::with('opd')->orderBy('code')->get();

        return view('services.admin.mpp.daftar-loket.edit', compact('counter', 'gerais'));
    }

    public function update(Request $request, Counter $counter): RedirectResponse
    {
        $validated = $request->validate([
            'gerai_id' => 'required|exists:mpp_gerais,id',
            'code' => 'required|string|max:10|unique:mpp_counters,code,'.$counter->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $counter->update($validated);

        Audit::log('UPDATE_LOKET', $counter, $counter->toArray());

        return redirect()->route('counters.index')
            ->with('success', "Loket {$counter->name} berhasil diperbarui.");
    }

    public function destroy(Counter $counter): RedirectResponse
    {
        $name = $counter->name;
        $counter->delete();

        Audit::log('DELETE_LOKET', null, ['name' => $name]);

        return redirect()->route('counters.index')
            ->with('success', "Loket {$name} berhasil dihapus.");
    }
}
