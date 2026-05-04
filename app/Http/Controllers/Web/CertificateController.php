<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    public function index(Request $request, CertificateService $service)
    {
        if (! Auth::user() || ! Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $leaders = $service->getPaginatedLeaders($request->search);

        return view('master-data.certificates.index', compact('leaders'));
    }

    public function generate(Request $request, $user_id, CertificateService $service)
    {
        if (! Auth::user() || ! Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $result = $service->generateForUser($user_id);

            return back()->with('success', $result['message']);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
