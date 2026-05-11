<?php

namespace App\Console\Commands;

use App\Enums\ServiceType;
use App\Facades\Audit;
use App\Models\DomicileRecord;
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
    public function handle(): void
    {
        $this->info('Starting to expire pending service requests...');

        $expiredCount = DB::transaction(function () {
            $count = 0;

            ServiceRequest::where('status', 'PENDING')
                ->where('created_at', '<', now()->startOfDay())
                ->each(function (ServiceRequest $request) use (&$count) {
                    $request->update(['status' => 'EXPIRED']);

                    // Also update Records if they were PENDING to reflect the expiry
                    if ($request->service_type === ServiceType::POVERTY) {
                        PovertyRecord::where('citizen_nik', $request->citizen_nik)
                            ->where('status', 'PENDING')
                            ->update(['status' => 'EXPIRED']);
                        Cache::forget("citizen_services_{$request->citizen_nik}");
                    } elseif ($request->service_type === ServiceType::DOMICILE) {
                        DomicileRecord::where('citizen_nik', $request->citizen_nik)
                            ->where('status', 'PENDING')
                            ->update(['status' => 'EXPIRED']);
                        Cache::forget("citizen_services_{$request->citizen_nik}");
                    }

                    // Log to audit
                    Audit::log('AUTO_EXPIRE_SERVICE', $request, [
                        'citizen_nik' => $request->citizen_nik,
                        'reason' => 'SLA 1 Hari: Tidak ditanggapi oleh desa tepat waktu',
                    ]);

                    $count++;
                });

            return $count;
        });

        $this->info("Successfully expired {$expiredCount} pending service requests.");
    }
}
