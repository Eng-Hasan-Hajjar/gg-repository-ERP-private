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
            'diploma_id' => 'nullable|exists:diplomas,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data['user_id'] = auth()->id();

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
}