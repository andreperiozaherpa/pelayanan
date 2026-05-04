<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Citizen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Export the citizen poverty list to CSV.
     */
    public function exportCitizens(Request $request): StreamedResponse
    {
        Gate::authorize('reports.export');

        $fileName = 'daftar-warga-kemiskinan-'.now()->format('Y-m-d').'.csv';

        $query = Citizen::with(['village', 'povertyRecords' => fn ($q) => $q->latest()])
            ->when($request->village_id, fn ($q) => $q->where('desa_id', $request->village_id));

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = ['NIK', 'Nama', 'Desa', 'Status Kemiskinan', 'Pendapatan', 'Valid Sampai'];

        $callback = function () use ($query, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            $query->chunk(100, function ($citizens) use ($file) {
                foreach ($citizens as $citizen) {
                    $record = $citizen->povertyRecords->first();
                    fputcsv($file, [
                        $citizen->nik,
                        $citizen->nama_lengkap,
                        $citizen->village->name ?? '-',
                        $record->status ?? 'PENDING',
                        $record->income_range ?? '-',
                        ($record && $record->valid_until) ? $record->valid_until->format('d/m/Y') : '-',
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export the system audit logs to CSV.
     */
    public function exportAuditLogs(): StreamedResponse
    {
        Gate::authorize('reports.export');

        $fileName = 'audit-logs-pelayanan-dokumen-'.now()->format('Y-m-d').'.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = ['Timestamp', 'User', 'Aksi', 'Tabel', 'Data Baru'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            AuditLog::with('user')->orderByDesc('timestamp')->chunk(200, function ($logs) use ($file) {
                foreach ($logs as $log) {
                    fputcsv($file, [
                        $log->timestamp->format('Y-m-d H:i:s'),
                        $log->user->name ?? 'System',
                        $log->action,
                        $log->target_table,
                        json_encode($log->new_value),
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
