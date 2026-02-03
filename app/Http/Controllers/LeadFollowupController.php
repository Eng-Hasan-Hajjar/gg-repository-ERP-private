<?php
// app/Http/Controllers/LeadFollowupController.php
namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class LeadFollowupController extends Controller
{
  public function store(Request $request, Lead $lead)
  {
    $data = $request->validate([
      'followup_date' => 'nullable|date',
      'result' => 'nullable|string|max:255',
      'notes' => 'nullable|string',
    ]);

    $lead->followups()->create($data + ['created_by'=>auth()->id()]);

    // تحديث stage تلقائياً (اختياري)
    if ($lead->stage === 'new') {
      $lead->update(['stage'=>'follow_up']);
    }

    return back()->with('success','تمت إضافة متابعة.');
  }
}
