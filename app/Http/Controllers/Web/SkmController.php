<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Opd;
use App\Models\SkmResponse;
use App\Services\SkmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SkmController extends Controller
{
    public function __construct(private readonly SkmService $skm) {}

    public function index(Request $request): View
    {
        [$from, $to] = $this->periodRange($request->query('period'));

        $query = SkmResponse::query();

        if ($request->filled('opd_id')) {
            $query->where('opd_id', $request->query('opd_id'));
        }
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        $grouped = $query->with('opd')->get()->groupBy('opd_id');

        $rows = $grouped->map(function ($responses) {
            return [
                'opd' => $responses->first()->opd,
                'rekap' => $this->skm->rekap($responses),
            ];
        })->sortByDesc(fn ($r) => $r['rekap']['ikm'])->values();

        $opds = Opd::orderBy('name')->get();

        return view('services.admin.mpp.survei.index', [
            'rows' => $rows,
            'opds' => $opds,
            'totalResponden' => $query->count() === 0 ? 0 : $grouped->flatten(1)->count(),
            'period' => $request->query('period'),
            'opdId' => $request->query('opd_id'),
            'from' => $from,
            'to' => $to,
        ]);
    }

    public function show(Opd $opd, Request $request): View
    {
        [$from, $to] = $this->periodRange($request->query('period'));

        $query = SkmResponse::where('opd_id', $opd->id);

        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        $responses = (clone $query)->latest()->paginate(15)->withQueryString();
        $rekap = $this->skm->rekap($query->get());

        return view('services.admin.mpp.survei.show', [
            'opd' => $opd,
            'responses' => $responses,
            'rekap' => $rekap,
            'period' => $request->query('period'),
            'from' => $from,
            'to' => $to,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        [$from, $to] = $this->periodRange($request->query('period'));

        $query = SkmResponse::query();

        if ($request->filled('opd_id')) {
            $query->where('opd_id', $request->query('opd_id'));
        }
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        $grouped = $query->with('opd')->get()->groupBy('opd_id');

        $rows = $grouped->map(function ($responses) {
            return [
                'opd' => $responses->first()->opd,
                'rekap' => $this->skm->rekap($responses),
            ];
        })->sortByDesc(fn ($r) => $r['rekap']['ikm'])->values();

        $unsurKeys = collect(SkmService::unsur())->pluck('key')->all();
        $fileName = 'skm-'.($from ?: 'awal').'-'.($to ?: 'akhir').'.csv';

        $callback = function () use ($rows, $unsurKeys, $from, $to) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            $header = ['No', 'Instansi', 'Kode', 'Periode', 'Jumlah Responden'];
            foreach (SkmService::unsur() as $u) {
                $header[] = $u['kode'].' '.$u['nama'].' (IKM)';
            }
            $header[] = 'IKM Komposit';
            $header[] = 'Mutu';
            $header[] = 'Kinerja';
            fputcsv($handle, $header);

            $periode = $from ? ($from.' s.d. '.($to ?? 'sekarang')) : 'Semua waktu';

            foreach ($rows as $index => $row) {
                $rekap = $row['rekap'];
                $line = [
                    $index + 1,
                    $row['opd']->name,
                    $row['opd']->code,
                    $periode,
                    $rekap['total_responden'],
                ];
                foreach ($unsurKeys as $key) {
                    $line[] = $rekap['unsur'][$key]['ikm'];
                }
                $line[] = $rekap['ikm'];
                $line[] = $rekap['mutu']['nilai'];
                $line[] = $rekap['mutu']['kinerja'];
                fputcsv($handle, $line);
            }

            fclose($handle);
        };

        return Response::streamDownload($callback, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * @return array{0: ?string, 1: ?string}
     */
    private function periodRange(?string $period): array
    {
        return match ($period) {
            'today' => [today()->toDateString(), today()->toDateString()],
            'this_month' => [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()],
            'last_month' => [now()->subMonth()->startOfMonth()->toDateString(), now()->subMonth()->endOfMonth()->toDateString()],
            'this_quarter' => [now()->startOfQuarter()->toDateString(), now()->endOfQuarter()->toDateString()],
            'this_semester' => now()->month <= 6
                ? [now()->startOfYear()->toDateString(), now()->startOfYear()->addMonths(5)->endOfMonth()->toDateString()]
                : [now()->startOfYear()->addMonths(6)->toDateString(), now()->endOfYear()->toDateString()],
            'this_year' => [now()->startOfYear()->toDateString(), now()->endOfYear()->toDateString()],
            default => [null, null],
        };
    }
}
