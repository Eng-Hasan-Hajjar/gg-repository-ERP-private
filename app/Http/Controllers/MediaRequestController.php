<?php


namespace App\Http\Controllers;

use App\Models\MediaRequest;
use App\Models\Diploma;
use Illuminate\Http\Request;

class MediaRequestController extends Controller
{
    public function index()
    {
        $query = MediaRequest::with('diploma','user');

        // المدير يرى طلباته فقط
        if (!auth()->user()->hasRole('media_team')) {
            $query->where('user_id', auth()->id());
        }

        $requests = $query->latest()->paginate(15);

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

        'requester_name' => 'required|string|max:255',
        'requester_phone' => 'nullable|string|max:50',

        'diploma_name' => 'nullable|string|max:255',
        'diploma_code' => 'nullable|string|max:100',
        'trainer_name' => 'nullable|string|max:255',
        'trainer_location' => 'nullable|string|max:255',
        'certificate_accreditation' => 'nullable|string|max:255',
        'customer_service_responsible' => 'nullable|string|max:255',
        'diploma_location' => 'nullable|string|max:255',

        'details_file' => 'nullable|file|max:20480',
        'trainer_image' => 'nullable|image|max:10240',

        'need_other' => 'nullable|string|max:255',

        'notes' => 'nullable|string',
    ]);

    $data['user_id'] = auth()->id();

    $data['trainer_photography_available'] = $request->has('trainer_photography_available');

    $data['need_ad'] = $request->has('need_ad');
    $data['need_invitation'] = $request->has('need_invitation');
    $data['need_review_video'] = $request->has('need_review_video');
    $data['need_content'] = $request->has('need_content');
    $data['need_podcast'] = $request->has('need_podcast');
    $data['need_carousel'] = $request->has('need_carousel');

    if ($request->hasFile('details_file')) {
        $data['details_file'] = $request->file('details_file')
            ->store('media/details','public');
    }

    if ($request->hasFile('trainer_image')) {
        $data['trainer_image'] = $request->file('trainer_image')
            ->store('media/trainers','public');
    }

    MediaRequest::create($data);

    return redirect()->route('media.index')
        ->with('success','تم إرسال الطلب بنجاح');
}
    public function update(Request $request, MediaRequest $media)
    {
        // فقط الميديا يعدل التنفيذ
        if (!auth()->user()->hasRole('media_team')) {
            abort(403);
        }

        $media->update([
            'design_done' => $request->has('design_done'),
            'ad_done' => $request->has('ad_done'),
            'invitation_done' => $request->has('invitation_done'),
            'content_done' => $request->has('content_done'),
            'podcast_done' => $request->has('podcast_done'),
            'reviews_done' => $request->has('reviews_done'),
            'notes' => $request->notes,
        ]);

        return back()->with('success','تم تحديث الحالة');
    }



    public function show(MediaRequest $media)
{
    if (!auth()->user()->hasRole('media_team')) {
        if ($media->user_id !== auth()->id()) {
            abort(403);
        }
    }

    return view('media.show', compact('media'));
}







public function publicForm()
{
    return view('media.public_form');
}

public function publicStore(Request $request)
{
    $data = $request->validate([
        'requester_name' => 'required|string|max:255',
        'requester_phone' => 'nullable|string|max:50',
        'diploma_name' => 'nullable|string|max:255',
        'diploma_code' => 'nullable|string|max:255',
        'trainer_name' => 'nullable|string|max:255',
        'trainer_location' => 'nullable|string|max:255',
        'certificate_accreditation' => 'nullable|string|max:255',
        'customer_service_responsible' => 'nullable|string|max:255',
        'diploma_location' => 'nullable|string|max:255',
        'details_file' => 'nullable|file|max:20480',
        'trainer_image' => 'nullable|image|max:10240',
        'need_other' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
    ]);

    $data['user_id'] = null; // لأنه فورم عام

    $data['trainer_photography_available'] = $request->has('trainer_photography_available');

    $data['need_ad'] = $request->has('need_ad');
    $data['need_invitation'] = $request->has('need_invitation');
    $data['need_review_video'] = $request->has('need_review_video');
    $data['need_content'] = $request->has('need_content');
    $data['need_podcast'] = $request->has('need_podcast');
    $data['need_carousel'] = $request->has('need_carousel');

    if ($request->hasFile('details_file')) {
        $data['details_file'] = $request->file('details_file')
            ->store('media/details','public');
    }

    if ($request->hasFile('trainer_image')) {
        $data['trainer_image'] = $request->file('trainer_image')
            ->store('media/trainers','public');
    }

    MediaRequest::create($data);

    return redirect()->route('media.public.thanks');
}

public function thanks()
{
    return view('media.thanks');
}







}