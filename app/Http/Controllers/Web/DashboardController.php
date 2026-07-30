<?php

namespace App\Http\Controllers\Web;

use App\Enums\ServiceType;
use App\Http\Controllers\Controller;
use App\Models\DeathRecord;
use App\Models\DomicileRecord;
use App\Models\MoveRecord;
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
        $activeCount = PovertyRecord::where('status', 'ACTIVE')->count() +
            DomicileRecord::where('status', 'ACTIVE')->count() +
            MoveRecord::where('status', 'ACTIVE')->count() +
            DeathRecord::where('status', 'ACTIVE')->count();

        $expiredCount = PovertyRecord::where('status', 'EXPIRED')->count() +
            DomicileRecord::where('status', 'EXPIRED')->count() +
            MoveRecord::where('status', 'EXPIRED')->count() +
            DeathRecord::where('status', 'EXPIRED')->count();

        $statusDistribution = [
            'active' => $activeCount,
            'expired' => $expiredCount,
            'pending' => ServiceRequest::where('status', 'PENDING')->count(),
        ];

        // 3. Service Type counts
        $serviceTypeCounts = [];
        foreach (ServiceType::cases() as $case) {
            $serviceTypeCounts[$case->value] = ServiceRequest::where('service_type', $case->value)->count();
        }

        // 4. Records detail breakdown
        $recordsBreakdown = [
            'poverty' => PovertyRecord::count(),
            'domicile' => DomicileRecord::count(),
            'move' => MoveRecord::count(),
            'death' => DeathRecord::count(),
        ];

        // 5. Monthly Service Requests trend (last 6 months)
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        $monthlyTrendRaw = ServiceRequest::where('created_at', '>=', $sixMonthsAgo)
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($request) {
                return $request->created_at->format('M Y');
            });

        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthKey = now()->subMonths($i)->format('M Y');
            $monthlyTrend[$monthKey] = isset($monthlyTrendRaw[$monthKey]) ? $monthlyTrendRaw[$monthKey]->count() : 0;
        }

        $stats = [
            'total_verifications' => $totalVerifications,
            'recent_requests' => ServiceRequest::with('citizen.village')->latest()->take(6)->get(),
            'today_count' => $todayCount,
            'village_breakdown' => $villageBreakdown,
            'status_distribution' => $statusDistribution,
            'service_type_counts' => $serviceTypeCounts,
            'records_breakdown' => $recordsBreakdown,
            'monthly_trend' => $monthlyTrend,
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
