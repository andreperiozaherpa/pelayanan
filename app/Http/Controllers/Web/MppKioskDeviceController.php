<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Models\MppKioskDevice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MppKioskDeviceController extends Controller
{
    public function index(): View
    {
        return view('services.admin.mpp.kiosk.index', [
            'devices' => MppKioskDevice::query()->latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(['name' => ['required', 'string', 'max:100', 'unique:mpp_kiosk_devices,name']]);
        [$device, $token] = $this->createToken($validated['name']);
        Audit::log('CREATE_MPP_KIOSK_DEVICE', $device, ['name' => $device->name]);

        return redirect()->route('mpp-kiosks.index')->with('kiosk_token', $token)->with('kiosk_name', $device->name);
    }

    public function rotate(MppKioskDevice $mppKioskDevice): RedirectResponse
    {
        $token = Str::random(64);
        $mppKioskDevice->update(['token_hash' => hash('sha256', $token), 'is_active' => true]);
        Audit::log('ROTATE_MPP_KIOSK_TOKEN', $mppKioskDevice, ['name' => $mppKioskDevice->name]);

        return redirect()->route('mpp-kiosks.index')->with('kiosk_token', $token)->with('kiosk_name', $mppKioskDevice->name);
    }

    public function toggle(MppKioskDevice $mppKioskDevice): RedirectResponse
    {
        $mppKioskDevice->update(['is_active' => ! $mppKioskDevice->is_active]);
        Audit::log('TOGGLE_MPP_KIOSK_DEVICE', $mppKioskDevice, ['name' => $mppKioskDevice->name, 'is_active' => $mppKioskDevice->is_active]);

        return back();
    }

    /** @return array{0: MppKioskDevice, 1: string} */
    private function createToken(string $name): array
    {
        $token = Str::random(64);
        $device = MppKioskDevice::create(['name' => $name, 'token_hash' => hash('sha256', $token)]);

        return [$device, $token];
    }
}
