<?php

namespace App\Console\Commands;

use App\Facades\Audit;
use App\Models\PovertyRecord;
use App\Models\ServiceRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ExpirePendingServices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-pending-services';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire all pending service requests from previous days as per SLA policy';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to expire pending service requests...');

        $expiredCount = DB::transaction(function () {
            // Find requests created before today that are still PENDING
            $staleRequests = ServiceRequest::where('status', 'PENDING')
                ->where('created_at', '<', now()->startOfDay())
                ->get();

            $count = 0;
            foreach ($staleRequests as $request) {
                $request->update(['status' => 'EXPIRED']);

                // Also update PovertyRecord if it was PENDING to reflect the expiry
                PovertyRecord::where('citizen_nik', $request->citizen_nik)
                    ->where('status', 'PENDING')
                    ->update(['status' => 'EXPIRED']);

                // Invalidate cache
                Cache::forget("poverty_status_{$request->citizen_nik}");

                // Log to audit
                Audit::log('AUTO_EXPIRE_SERVICE', $request, [
                    'citizen_nik' => $request->citizen_nik,
                    'reason' => 'SLA 1 Hari: Tidak ditanggapi oleh desa tepat waktu',
                ]);

                $count++;
            }

            return $count;
        });

        $this->info("Successfully expired {$expiredCount} pending service requests.");
    }
}
