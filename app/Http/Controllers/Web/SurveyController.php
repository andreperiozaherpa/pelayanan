<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Opd;
use App\Models\SkmResponse;
use App\Services\SkmService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SurveyController extends Controller
{
    public function __construct(private readonly SkmService $skm) {}

    public function index(): View
    {
        $instansis = Opd::query()
            ->whereHas('gerais', fn ($q) => $q->where('is_active', true))
            ->orderBy('name')
            ->get();

        return view('public.survey.index', compact('instansis'));
    }

    public function show(Opd $opd): View
    {
        $hasActiveGerai = $opd->gerais()->where('is_active', true)->exists();

        if (! $hasActiveGerai) {
            abort(404, 'Instansi tidak aktif.');
        }

        return view('public.survey.show', [
            'instansi' => $opd,
            'unsur' => $this->skm->unsur(),
        ]);
    }

    public function store(Request $request, Opd $opd): RedirectResponse
    {
        $hasActiveGerai = $opd->gerais()->where('is_active', true)->exists();

        if (! $hasActiveGerai) {
            abort(404, 'Instansi tidak aktif.');
        }

        $validated = $request->validate($this->rules());

        SkmResponse::create([
            'opd_id' => $opd->id,
            'nama' => $validated['nama'] ?? null,
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
            'umur' => $validated['umur'] ?? null,
            'pendidikan' => $validated['pendidikan'] ?? null,
            'pekerjaan' => $validated['pekerjaan'] ?? null,
            ...collect(SkmService::unsur())->mapWithKeys(fn ($u) => [$u['key'] => $validated[$u['key']]])->all(),
            'saran' => $validated['saran'] ?? null,
            'source' => SkmResponse::SOURCE_WEB,
        ]);

        return redirect()->route('survey.index')
            ->with('success', 'Terima kasih atas partisipasi Anda. Hasil survei telah kami terima.');
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(): array
    {
        $rules = [
            'nama' => ['nullable', 'string', 'max:255'],
            'jenis_kelamin' => ['nullable', Rule::in(['L', 'P'])],
            'umur' => ['nullable', 'integer', 'min:10', 'max:120'],
            'pendidikan' => ['nullable', 'string', 'max:255'],
            'pekerjaan' => ['nullable', 'string', 'max:255'],
            'saran' => ['nullable', 'string', 'max:2000'],
        ];

        foreach (SkmService::unsur() as $u) {
            $rules[$u['key']] = ['required', Rule::in([1, 2, 3, 4])];
        }

        return $rules;
    }
}
