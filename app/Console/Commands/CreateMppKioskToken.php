<?php

namespace App\Console\Commands;

use App\Models\MppKioskDevice;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateMppKioskToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mpp:kiosk-token {name : Nama unik kiosk} {--rotate : Ganti token perangkat yang sudah ada}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Terbitkan atau rotasi token perangkat MPP kiosk';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = trim((string) $this->argument('name'));
        $device = MppKioskDevice::query()->where('name', $name)->first();

        if ($device && ! $this->option('rotate')) {
            $this->error('Kiosk sudah ada. Gunakan --rotate untuk menerbitkan token baru.');

            return self::FAILURE;
        }

        $token = Str::random(64);
        MppKioskDevice::query()->updateOrCreate(['name' => $name], [
            'token_hash' => hash('sha256', $token),
            'is_active' => true,
        ]);

        $this->line($token);

        return self::SUCCESS;
    }
}
