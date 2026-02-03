<?php
// app/Http/Controllers/LeadController.php
namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Branch;
use App\Models\Diploma;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Requests\LeadStoreRequest;
use App\Http\Requests\LeadUpdateRequest;

class LeadController extends Controller
{
  public function index(Request $request)
  {
    $q = Lead::query()->with(['branch','diplomas','followups']);

    if ($request->filled('branch_id')) $q->where('branch_id',$request->branch_id);
    if ($request->filled('stage')) $q->where('stage',$request->stage);
    if ($request->filled('registration_status')) $q->where('registration_status',$request->registration_status);

    if ($request->filled('diploma_id')) {
      $did = $request->diploma_id;
      $q->whereHas('diplomas', fn($x)=>$x->where('diplomas.id',$did));
    }

    if ($request->filled('search')) {
      $s = trim($request->search);
      $q->where(fn($x)=>$x
        ->where('full_name','like',"%$s%")
        ->orWhere('phone','like',"%$s%")
        ->orWhere('whatsapp','like',"%$s%")
      );
    }

    return view('crm.leads.index', [
      'leads' => $q->latest()->paginate(15)->withQueryString(),
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
    $data['created_by'] = auth()->id();

    $lead = DB::transaction(function() use ($data,$request){
      $lead = Lead::create($data);

      $diplomaIds = $request->input('diploma_ids', []);
      if (!empty($diplomaIds)) {
        $sync = [];
        foreach ($diplomaIds as $i=>$id) {
          $sync[$id] = ['is_primary'=>$i===0];
        }
        $lead->diplomas()->sync($sync);
      }

      return $lead;
    });

    return redirect()->route('crm.leads.show',$lead)->with('success','تم إنشاء العميل المحتمل.');
  }

  public function show(Lead $lead)
  {
    $lead->load(['branch','diplomas','followups']);
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

    DB::transaction(function() use ($data,$request,$lead){
      $lead->update($data);

      $diplomaIds = $request->input('diploma_ids', null);
      if (is_array($diplomaIds)) {
        $sync = [];
        foreach ($diplomaIds as $i=>$id) {
          $sync[$id] = ['is_primary'=>$i===0];
        }
        $lead->diplomas()->sync($sync);
      }
    });

    return redirect()->route('crm.leads.show',$lead)->with('success','تم تحديث العميل المحتمل.');
  }

  public function destroy(Lead $lead)
  {
    $lead->delete();
    return redirect()->route('crm.leads.index')->with('success','تم حذف العميل المحتمل.');
  }

  // ✅ تحويل إلى طالب
  public function convertToStudent(Lead $lead)
  {
    abort_unless($lead->registration_status === 'pending', 403);

    $student = DB::transaction(function () use ($lead) {

      $student = Student::create([
        'university_id' => 'NMA-'.now()->format('Y').'-'.Str::upper(Str::random(6)),
        'first_name' => explode(' ', trim($lead->full_name))[0] ?? $lead->full_name,
        'last_name'  => '-',
        'full_name'  => $lead->full_name,

        'phone'    => $lead->phone,
        'whatsapp' => $lead->whatsapp,
        'email'    => null,

        'branch_id' => $lead->branch_id,
        'mode'      => 'onsite',
        'status'    => 'active',

        'registration_status' => 'confirmed',
        'is_confirmed' => true,
        'confirmed_at' => now(),
      ]);

      // ✅ نقل دبلومات الـ lead إلى الطالب
      $leadDiplomas = $lead->diplomas()->get();
      $sync = [];
      foreach ($leadDiplomas as $i=>$d) {
        $sync[$d->id] = [
          'is_primary'  => (bool)($d->pivot->is_primary) || $i===0,
          'enrolled_at' => now()->toDateString(),
          'status'      => 'active',
        ];
      }
      if (!empty($sync)) $student->diplomas()->sync($sync);

      // ✅ حفظ CRM info داخل student_crm_infos
      $student->crmInfo()->updateOrCreate(
        ['student_id'=>$student->id],
        [
          'first_contact_date' => $lead->first_contact_date,
          'residence' => $lead->residence,
          'age' => $lead->age,
          'organization' => $lead->organization,
          'source' => $lead->source,
          'need' => $lead->need,
          'stage' => $lead->stage,
          'notes' => $lead->notes,
          'converted_at' => now(),
        ]
      );

      // ✅ إنشاء profile مبدئي (اختياري)
      $student->profile()->updateOrCreate(['student_id'=>$student->id], [
        'arabic_full_name' => null,
        'address' => $lead->residence,
        'notes' => 'تم إنشاء الطالب من CRM',
      ]);

      // تحديث الـ lead
      $lead->update([
        'registration_status' => 'converted',
        'registered_at' => now()->toDateString(),
        'student_id' => $student->id,
        'stage' => 'registered',
      ]);

      return $student;
    });

    return redirect()->route('students.show',$student)->with('success','تم تحويل العميل إلى طالب رسمي.');
  }
}
