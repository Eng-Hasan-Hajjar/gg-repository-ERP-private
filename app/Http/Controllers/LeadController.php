<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadStoreRequest;
use App\Http\Requests\LeadUpdateRequest;
use App\Models\Branch;
use App\Models\Diploma;
use App\Models\Lead;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LeadController extends Controller
{
  public function index(Request $request)
  {
    $q = Lead::query()->with(['branch','diplomas','followups'])
      ->orderByDesc('id');

    // افتراضياً CRM يعرض فقط pending
    if (!$request->filled('registration_status')) {
      $q->where('registration_status','pending');
    }

    if ($request->filled('registration_status')) {
      $q->where('registration_status', $request->registration_status);
    }

    if ($request->filled('branch_id')) {
      $q->where('branch_id', $request->branch_id);
    }

    if ($request->filled('stage')) {
      $q->where('stage', $request->stage);
    }

    if ($request->filled('source')) {
      $q->where('source', $request->source);
    }

    if ($request->filled('search')) {
      $s = trim($request->search);
      $q->where(function($x) use ($s){
        $x->where('full_name','like',"%$s%")
          ->orWhere('phone','like',"%$s%")
          ->orWhere('whatsapp','like',"%$s%");
      });
    }

    return view('crm.leads.index', [
      'leads' => $q->paginate(15)->withQueryString(),
      'branches' => Branch::orderBy('name')->get(),
      'diplomas' => Diploma::orderBy('name')->get(),
    ]);
  }

  public function create()
  {
    return view('crm.leads.create', [
      'branches' => Branch::orderBy('name')->get(),
      'diplomas' => Diploma::orderBy('name')->get(),
    ]);
  }

  public function store(LeadStoreRequest $request)
  {
    $data = $request->validated();

    $diplomaIds = $data['diploma_ids'] ?? [];
    $primaryDiplomaId = $data['primary_diploma_id'] ?? null;

    unset($data['diploma_ids'], $data['primary_diploma_id']);

    $data['created_by'] = auth()->id();
    $data['registration_status'] = 'pending';

    $lead = Lead::create($data);

    // sync diplomas with primary
    $syncData = [];
    foreach ($diplomaIds as $id) {
      $syncData[$id] = ['is_primary' => ($primaryDiplomaId && (int)$primaryDiplomaId === (int)$id)];
    }
    if (!$primaryDiplomaId && count($diplomaIds)) {
      $syncData[$diplomaIds[0]] = ['is_primary' => true];
    }

    $lead->diplomas()->sync($syncData);

    return redirect()->route('leads.show',$lead)->with('success','تم إضافة العميل المحتمل بنجاح.');
  }

  public function show(Lead $lead)
  {
    $lead->load(['branch','diplomas','followups.creator','student']);
    return view('crm.leads.show', compact('lead'));
  }

  public function edit(Lead $lead)
  {
    $lead->load('diplomas');

    return view('crm.leads.edit', [
      'lead' => $lead,
      'branches' => Branch::orderBy('name')->get(),
      'diplomas' => Diploma::orderBy('name')->get(),
    ]);
  }

  public function update(LeadUpdateRequest $request, Lead $lead)
  {
    $data = $request->validated();

    $diplomaIds = $data['diploma_ids'] ?? [];
    $primaryDiplomaId = $data['primary_diploma_id'] ?? null;

    unset($data['diploma_ids'], $data['primary_diploma_id']);

    $lead->update($data);

    $syncData = [];
    foreach ($diplomaIds as $id) {
      $syncData[$id] = ['is_primary' => ($primaryDiplomaId && (int)$primaryDiplomaId === (int)$id)];
    }
    if (!$primaryDiplomaId && count($diplomaIds)) {
      $syncData[$diplomaIds[0]] = ['is_primary' => true];
    }

    $lead->diplomas()->sync($syncData);

    return redirect()->route('leads.show',$lead)->with('success','تم تحديث بيانات العميل المحتمل.');
  }

  public function destroy(Lead $lead)
  {
    $lead->delete();
    return redirect()->route('leads.index')->with('success','تم حذف العميل المحتمل.');
  }

  // ✅ التحويل إلى طالب رسمي
  public function convertToStudent(Lead $lead)
  {
    abort_unless($lead->registration_status === 'pending', 403);

    return DB::transaction(function () use ($lead) {

      // توليد رقم جامعي مضمون
      do {
        $univId = 'NMA-' . now()->format('Y') . '-' . Str::upper(Str::random(6));
      } while (Student::where('university_id',$univId)->exists());

      $student = Student::create([
        'university_id' => $univId,
        'first_name' => explode(' ', trim($lead->full_name))[0] ?? $lead->full_name,
        'last_name'  => '-',
        'full_name'  => $lead->full_name,
        'phone'      => $lead->phone,
        'whatsapp'   => $lead->whatsapp,
        'email'      => null,
        'branch_id'  => $lead->branch_id,
        'mode'       => 'onsite',
        'status'     => 'active',

        // ✅ الطالب لا يظهر عندك بقسم الطلاب إلا إذا confirmed
        'registration_status' => 'confirmed',
        'is_confirmed' => true,
        'confirmed_at' => now(),
      ]);

      // primary diploma (اختياري) لجدول students حتى يظل نظامك القديم شغال
      $primary = $lead->diplomas()->wherePivot('is_primary', true)->first();
      if ($primary) {
        $student->update(['diploma_id' => $primary->id]);
      }

      // نقل كل الدبلومات إلى pivot diploma_student
      $leadDiplomas = $lead->diplomas()->get();
      foreach ($leadDiplomas as $i => $d) {
        $student->diplomas()->attach($d->id, [
          'is_primary' => (bool)$d->pivot->is_primary || $i === 0,
          'enrolled_at' => now()->toDateString(),
          'status' => 'active',
        ]);
      }

      $lead->update([
        'registration_status' => 'converted',
        'registered_at' => now()->toDateString(),
        'student_id' => $student->id,
        'stage' => 'registered',
      ]);

      return redirect()->route('students.show',$student)->with('success','تم تحويل العميل إلى طالب رسمي.');
    });
  }
}
