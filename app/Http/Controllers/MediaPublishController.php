<?php

namespace App\Http\Controllers;

use App\Models\MediaPublish;
use App\Models\MediaRequest;
use App\Models\Branch;
use Illuminate\Http\Request;

class MediaPublishController extends Controller
{
    /**
     * قائمة النشر الكاملة
     */
    public function index(Request $request)
    {
        $query = MediaPublish::with('mediaRequest')->latest();

        if ($request->filled('diploma_name')) {
            $query->where('diploma_name', 'LIKE', '%' . $request->diploma_name . '%');
        }

        if ($request->filled('content_category')) {
            $query->where('content_category', $request->content_category);
        }

        if ($request->filled('content_type')) {
            $query->where('content_type', $request->content_type);
        }

        if ($request->filled('branch')) {
            $query->where('branch', $request->branch);
        }

        $entries  = $query->paginate(20);
        $branches = class_exists(Branch::class) ? Branch::pluck('name') : collect();

        return view('media.publish_index', compact('entries', 'branches'));
    }

    /**
     * فورم إضافة سجل نشر جديد
     */
    public function create()
    {
        $mediaRequests = MediaRequest::latest()->get();
        $branches      = class_exists(Branch::class) ? Branch::pluck('name') : collect();

        return view('media.publish_create', compact('mediaRequests', 'branches'));
    }

    /**
     * حفظ سجل النشر
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'media_request_id' => 'nullable|exists:media_requests,id',
            'diploma_name'     => 'required|string|max:255',
            'content_category' => 'required|in:ad,invitation,content,review,general_content',
            'content_type'     => 'required|in:design,video,carousel',
            'branch'           => 'nullable|string|max:255',
            'caption'          => 'nullable|string',
            'publish_date'     => 'nullable|date',
        ]);

        $data['published_meta']    = $request->has('published_meta');
        $data['published_tiktok']  = $request->has('published_tiktok');
        $data['published_youtube'] = $request->has('published_youtube');

        MediaPublish::create($data);

        return redirect()->route('media.publish.index')
            ->with('success', 'تم إضافة سجل النشر بنجاح');
    }

    /**
     * تعديل سجل نشر
     */
    public function edit(MediaPublish $publish)
    {
        $mediaRequests = MediaRequest::latest()->get();
        $branches      = class_exists(Branch::class) ? Branch::pluck('name') : collect();

        return view('media.publish_edit', compact('publish', 'mediaRequests', 'branches'));
    }

    /**
     * تحديث سجل النشر
     */
    public function update(Request $request, MediaPublish $publish)
    {
        $data = $request->validate([
            'media_request_id' => 'nullable|exists:media_requests,id',
            'diploma_name'     => 'required|string|max:255',
            'content_category' => 'required|in:ad,invitation,content,review,general_content',
            'content_type'     => 'required|in:design,video,carousel',
            'branch'           => 'nullable|string|max:255',
            'caption'          => 'nullable|string',
            'publish_date'     => 'nullable|date',
        ]);

        $data['published_meta']    = $request->has('published_meta');
        $data['published_tiktok']  = $request->has('published_tiktok');
        $data['published_youtube'] = $request->has('published_youtube');

        $publish->update($data);

        return redirect()->route('media.publish.index')
            ->with('success', 'تم تحديث سجل النشر');
    }

    /**
     * حذف سجل نشر
     */
    public function destroy(MediaPublish $publish)
    {
        $publish->delete();

        return back()->with('success', 'تم حذف سجل النشر');
    }
}