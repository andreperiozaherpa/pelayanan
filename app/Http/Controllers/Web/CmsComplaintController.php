<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Models\CmsComplaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CmsComplaintController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('cms.complaints.view');

        $query = CmsComplaint::latest();

        // Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $complaints = $query->paginate(10)->withQueryString();

        return view('cms.complaints.index', compact('complaints'));
    }

    public function show(CmsComplaint $cmsComplaint)
    {
        Gate::authorize('cms.complaints.view');

        return view('cms.complaints.show', compact('cmsComplaint'));
    }

    public function update(Request $request, CmsComplaint $cmsComplaint)
    {
        Gate::authorize('cms.complaints.reply');

        $request->validate([
            'status' => 'required|in:pending,processed,resolved',
            'reply' => 'nullable|string',
        ]);

        $oldValue = $cmsComplaint->toArray();

        $updateData = [
            'status' => $request->status,
            'reply' => $request->reply,
        ];

        if ($request->filled('reply') || $request->status !== 'pending') {
            $updateData['replied_by'] = auth()->id();
            $updateData['replied_at'] = now();
        }

        $cmsComplaint->update($updateData);

        Audit::log('CMS_UPDATE_COMPLAINT', $cmsComplaint, $cmsComplaint->toArray(), $oldValue);

        return redirect()->route('cms-complaints.index')
            ->with('success', 'Pengaduan berhasil diperbarui dan ditindaklanjuti.');
    }

    public function destroy(CmsComplaint $cmsComplaint)
    {
        Gate::authorize('cms.complaints.delete');

        $oldValue = $cmsComplaint->toArray();
        $cmsComplaint->delete();

        Audit::log('CMS_DELETE_COMPLAINT', $cmsComplaint, null, $oldValue);

        return redirect()->route('cms-complaints.index')
            ->with('success', 'Pengaduan berhasil dihapus.');
    }
}
