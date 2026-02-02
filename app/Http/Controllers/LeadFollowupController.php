<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadFollowupStoreRequest;
use App\Models\Lead;
use App\Models\LeadFollowup;

class LeadFollowupController extends Controller
{
  public function store(LeadFollowupStoreRequest $request, Lead $lead)
  {
    $data = $request->validated();
    $data['created_by'] = auth()->id();
    $data['lead_id'] = $lead->id;

    LeadFollowup::create($data);

    return redirect()->route('leads.show',$lead)->with('success','تمت إضافة المتابعة.');
  }

  public function destroy(Lead $lead, LeadFollowup $followup)
  {
    abort_unless($followup->lead_id === $lead->id, 403);
    $followup->delete();

    return redirect()->route('leads.show',$lead)->with('success','تم حذف المتابعة.');
  }
}
