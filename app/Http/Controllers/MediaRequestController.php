<?php

namespace App\Http\Controllers;

use App\Models\MediaRequest;
use App\Models\Diploma;
use Illuminate\Http\Request;

class MediaRequestController extends Controller
{
    public function index()
    {
        $user  = auth()->user();
        $query = MediaRequest::with('diploma', 'user')->latest();

        // super_admin يرى الكل — الباقي يرون طلباتهم فقط
       if (!$user->hasRole('super_admin')) {
            $query->where('user_id', $user->id);
        }

        $requests = $query->paginate(15);

        return view('media.index', compact('requests'));
    }

    public function create()
    {
        $diplomas = Diploma::all();
        return view('media.create', compact('diplomas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'requester_name'              => 'required|string|max:255',
            'requester_phone'             => 'nullable|string|max:50',
            'diploma_name'                => 'nullable|string|max:255',
            'diploma_code'                => 'nullable|string|max:100',
            'trainer_name'                => 'nullable|string|max:255',
            'trainer_location'            => 'nullable|string|max:255',
            'certificate_accreditation'   => 'nullable|string|max:255',
            'customer_service_responsible' => 'nullable|string|max:255',
            'diploma_location'            => 'nullable|string|max:255',
            'details_file'                => 'nullable|file|max:20480',
            'trainer_image'               => 'nullable|image|max:10240',
            'need_other'                  => 'nullable|string|max:255',
            'editing_deadline'            => 'nullable|date',
            'notes'                       => 'nullable|string',
        ]);

        $data['user_id'] = auth()->id();

        $data['trainer_photography_available'] = $request->has('trainer_photography_available');
        $data['need_ad']           = $request->has('need_ad');
        $data['need_invitation']   = $request->has('need_invitation');
        $data['need_review_video'] = $request->has('need_review_video');
        $data['need_content']      = $request->has('need_content');
        $data['need_podcast']      = $request->has('need_podcast');
        $data['need_carousel']     = $request->has('need_carousel');

        if ($request->hasFile('details_file')) {
            $data['details_file'] = $request->file('details_file')
                ->store('media/details', 'public');
        }

        if ($request->hasFile('trainer_image')) {
            $data['trainer_image'] = $request->file('trainer_image')
                ->store('media/trainers', 'public');
        }

        MediaRequest::create($data);

        return redirect()->route('media.index')
            ->with('success', 'تم إرسال الطلب بنجاح');
    }

    public function update(Request $request, MediaRequest $media)
    {
        $data = [];

        if ($request->has('content_link')) {
            $data['content_link'] = $request->content_link;
        }

        if ($request->has('editing_deadline')) {
            $data['editing_deadline'] = $request->editing_deadline;
        }

        if ($request->has('notes')) {
            $data['notes'] = $request->notes;
        }

        if ($request->has('design_done') || $request->has('ad_done') || $request->has('invitation_done')
            || $request->has('content_done') || $request->has('podcast_done') || $request->has('reviews_done')) {
            $data['design_done']     = $request->has('design_done');
            $data['ad_done']         = $request->has('ad_done');
            $data['invitation_done'] = $request->has('invitation_done');
            $data['content_done']    = $request->has('content_done');
            $data['podcast_done']    = $request->has('podcast_done');
            $data['reviews_done']    = $request->has('reviews_done');
        }

        $media->update($data);

        return redirect()->route('media.index')
            ->with('success', 'تم تحديث الحالة بنجاح');
    }

    public function show(MediaRequest $media)
    {
        $media->load('publishEntries');
        return view('media.show', compact('media'));
    }

    public function cleanupDrafts(Request $request)
    {
        $deleted = MediaRequest::whereNull('requester_name')
            ->orWhere('requester_name', '')
            ->orWhere('requester_name', 'LIKE', '%تجريب%')
            ->orWhere('requester_name', 'LIKE', '%test%')
            ->delete();

        return back()->with('success', "تم حذف {$deleted} مسودة/إدخال تجريبي");
    }

    public function publicForm()
    {
        return view('media.public_form');
    }

    public function publicStore(Request $request)
    {
        $data = $request->validate([
            'requester_name'              => 'required|string|max:255',
            'requester_phone'             => 'nullable|string|max:50',
            'diploma_name'                => 'nullable|string|max:255',
            'diploma_code'                => 'nullable|string|max:255',
            'trainer_name'                => 'nullable|string|max:255',
            'trainer_location'            => 'nullable|string|max:255',
            'certificate_accreditation'   => 'nullable|string|max:255',
            'customer_service_responsible' => 'nullable|string|max:255',
            'diploma_location'            => 'nullable|string|max:255',
            'details_file'                => 'nullable|file|max:20480',
            'trainer_image'               => 'nullable|image|max:10240',
            'need_other'                  => 'nullable|string|max:255',
            'notes'                       => 'nullable|string',
        ]);

        $data['user_id'] = null;

        $data['trainer_photography_available'] = $request->has('trainer_photography_available');
        $data['need_ad']           = $request->has('need_ad');
        $data['need_invitation']   = $request->has('need_invitation');
        $data['need_review_video'] = $request->has('need_review_video');
        $data['need_content']      = $request->has('need_content');
        $data['need_podcast']      = $request->has('need_podcast');
        $data['need_carousel']     = $request->has('need_carousel');

        if ($request->hasFile('details_file')) {
            $data['details_file'] = $request->file('details_file')
                ->store('media/details', 'public');
        }

        if ($request->hasFile('trainer_image')) {
            $data['trainer_image'] = $request->file('trainer_image')
                ->store('media/trainers', 'public');
        }

        MediaRequest::create($data);

        return redirect()->route('media.public.thanks');
    }

    public function thanks()
    {
        return view('media.thanks');
    }
}