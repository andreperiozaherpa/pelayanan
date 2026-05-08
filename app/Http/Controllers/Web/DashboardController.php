<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Citizen;
use App\Models\PovertyRecord;
use App\Models\ServiceRequest;
use App\Models\VerificationLog;
use App\Models\Village;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Front Office dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Role-based Redirection
        if ($user->isOperatorDesa()) {
            return redirect()->route('dashboard.desa');
        }

        // 2. Global Analytics (Blueprint 5)
        $totalVerifications = VerificationLog::count();
        $todayCount = VerificationLog::whereDate('timestamp', today())->count();

        // Verification breakdown by village
        $villageBreakdown = Village::withCount(['citizens as verifications_count' => function ($q) {
            $q->whereHas('verificationLogs');
        }])
            ->orderByDesc('verifications_count')
            ->take(6)
            ->get();

        // Poverty status distribution
        $statusDistribution = [
            'active' => PovertyRecord::where('status', 'ACTIVE')->count(),
            'expired' => PovertyRecord::where('status', 'EXPIRED')->count()
                + PovertyRecord::where('status', 'ACTIVE')->where('valid_until', '<', now())->count(),
            'pending' => Citizen::whereDoesntHave('povertyRecords')->count(),
        ];

        $stats = [
            'total_verifications' => $totalVerifications,
            'recent_requests' => ServiceRequest::with('citizen.village')->latest()->take(6)->get(),
            'today_count' => $todayCount,
            'village_breakdown' => $villageBreakdown,
            'status_distribution' => $statusDistribution,
        ];

        return view('services.admin.dashboard.index', compact('stats'));
    }

    public function desa(): View
    {
        $user = Auth::user();
        // Fallback to 0 if desa_id is magically null (though guarded by middleware)
        $desaId = $user->desa_id ?? 0;

        $stats = [
            'total_verifications' => VerificationLog::where(function ($q) use ($desaId) {
                // Actions by village operators OR regarding village citizens
                $q->whereHas('user', fn ($u) => $u->where('desa_id', $desaId))
                    ->orWhereHas('citizen', fn ($c) => $c->where('desa_id', $desaId));
            })->count(),
            'recent_requests' => ServiceRequest::whereHas('citizen', function ($q) use ($desaId) {
                $q->where('desa_id', $desaId);
            })->latest()->take(5)->get(),
            'today_count' => ServiceRequest::whereHas('citizen', function ($q) use ($desaId) {
                $q->where('desa_id', $desaId);
            })->whereDate('created_at', today())->count(),
        ];

        return view('services.desa.dashboard.index', compact('stats'));
    }
}
