<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\DomicileRecord;
use App\Models\PovertyRecord;
use App\Models\ServiceRequest;
use App\Models\VerificationLog;
use App\Models\Village;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Front Office dashboard.
     */
    public function index(): View|RedirectResponse
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

        // Record distribution
        $statusDistribution = [
            'poverty_active' => PovertyRecord::where('status', 'ACTIVE')->count(),
            'domicile_active' => DomicileRecord::where('status', 'ACTIVE')->count(),
            'total_pending' => ServiceRequest::where('status', 'PENDING')->count(),
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

    /**
     * Display the Village Operator dashboard.
     */
    public function desa(): View
    {
        $user = Auth::user();
        $desaId = $user->desa_id ?? 0;

        $villageRequests = ServiceRequest::whereHas('citizen', fn ($q) => $q->where('desa_id', $desaId));

        $stats = [
            'total_verifications' => VerificationLog::whereHas('user', fn ($u) => $u->where('desa_id', $desaId))
                ->orWhereHas('citizen', fn ($c) => $c->where('desa_id', $desaId))
                ->count(),
            'recent_requests' => (clone $villageRequests)->latest()->take(5)->get(),
            'today_count' => (clone $villageRequests)->whereDate('created_at', today())->count(),
        ];

        return view('services.desa.dashboard.index', compact('stats'));
    }
}
