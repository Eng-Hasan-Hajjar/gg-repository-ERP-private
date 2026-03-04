<?php
namespace App\Http\Controllers;

use App\Models\TaskReport;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskReportController extends Controller
{


public function index(Request $request)
{

    $query = TaskReport::with(['employee','task']);

    $user = auth()->user();

    // الموظف يرى تقاريره فقط
    if(!$user->hasRole('super_admin')){
        $query->where('employee_id',$user->employee->id);
    }

    // فلترة الموظف (للسوبر أدمن)
    if($request->filled('employee_id')){
        $query->where('employee_id',$request->employee_id);
    }

    // فلترة نوع التقرير
    if($request->filled('report_type')){
        $query->where('report_type',$request->report_type);
    }

    // فلترة المهمة
    if($request->filled('task_id')){
        $query->where('task_id',$request->task_id);
    }

    // فلترة تاريخ من
    if($request->filled('from')){
        $query->whereDate('report_date','>=',$request->from);
    }

    // فلترة تاريخ إلى
    if($request->filled('to')){
        $query->whereDate('report_date','<=',$request->to);
    }

    // البحث
    if($request->filled('search')){
        $s = $request->search;

        $query->where(function($q) use ($s){
            $q->where('title','like',"%$s%")
              ->orWhere('notes','like',"%$s%");
        });
    }

    // فلتر سريع
    if($request->filled('quick')){

        if($request->quick == 'today'){
            $query->whereDate('report_date',now());
        }

        if($request->quick == 'week'){
            $query->whereBetween('report_date',[
                now()->startOfWeek(),
                now()->endOfWeek()
            ]);
        }

        if($request->quick == 'month'){
            $query->whereMonth('report_date',now()->month);
        }

    }

    $reports = $query->latest()->paginate(20)->withQueryString();

    return view('task_reports.index',[

        'reports'=>$reports,

        'employees'=>\App\Models\Employee::orderBy('full_name')->get(),

        'tasks'=>\App\Models\Task::orderBy('title')->get()

    ]);

}
/*
    public function index()
    {

        $reports = TaskReport::with(['employee','task'])

        ->where('employee_id',auth()->user()->employee->id)

        ->latest()

        ->paginate(20);

    return view('task_reports.index',compact('reports'));
    }
*/
    public function create()
    {

        $tasks = Task::orderBy('title')->get();

        return view('task_reports.create',compact('tasks'));
    }
public function store(Request $request)
{

    $data = $request->validate([

    'task_id' => ['required','exists:tasks,id'],

    'report_type' => ['required','in:daily,weekly,monthly'],

    'report_date' => ['required','date'],

    'title' => ['required','string','max:255'],

    'notes' => ['nullable','string'],

   'file' => ['required','file','mimes:pdf,doc,docx,xlsx,xls','max:5120']
]);
    $employee = auth()->user()->employee;

    if(!$employee){

        return back()->withErrors([
            'employee' => 'يجب أن يكون الحساب مرتبط بموظف حتى يتم رفع التقرير.'
        ]);

    }

    $data['employee_id'] = $employee->id;

    if($request->hasFile('file')){

        $path = $request->file('file')
            ->store('task_reports','public');

        $data['file_path'] = $path;
    }

    TaskReport::create($data);

    return redirect()
        ->route('reports.task.index')
        ->with('success','تم رفع التقرير بنجاح');
}


    public function show(TaskReport $report)
    {
        return view('task_reports.show',compact('report'));
    }

    public function destroy(TaskReport $report)
    {

        if($report->file_path){
            Storage::disk('public')->delete($report->file_path);
        }

        $report->delete();

        return back()->with('success','تم حذف التقرير');
    }
}