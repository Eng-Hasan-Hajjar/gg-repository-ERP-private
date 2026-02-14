<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $q = Task::query()->with(['assignee.branch','branch','creator']);

        if ($request->filled('branch_id')) $q->where('branch_id',$request->branch_id);
        if ($request->filled('assigned_to')) $q->where('assigned_to',$request->assigned_to);
        if ($request->filled('status')) $q->where('status',$request->status);
        if ($request->filled('priority')) $q->where('priority',$request->priority);
        if ($request->filled('due')) $q->whereDate('due_date',$request->due);

        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->where('title','like',"%$s%")->orWhere('description','like',"%$s%");
        }
        
        if ($request->filled('late')) {
            $q->whereDate('due_date', '<', now())
            ->where('status', '!=', 'done');
        }

        return view('tasks.index', [
            'tasks' => $q->latest()->paginate(20)->withQueryString(),
            'branches' => Branch::orderBy('name')->get(),
            'employees' => Employee::orderBy('full_name')->get(),
        ]);
    }

    public function create()
    {
        return view('tasks.create', [
            'branches' => Branch::orderBy('name')->get(),
            'employees'=> Employee::orderBy('full_name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'description' => ['nullable','string','max:5000'],
            'assigned_to' => ['nullable','exists:employees,id'],
            'branch_id' => ['nullable','exists:branches,id'],
            'priority' => ['required','in:low,medium,high,urgent'],
            'status' => ['required','in:todo,in_progress,done,blocked,archived'],
            'due_date' => ['nullable','date'],
        ]);

        $data['created_by'] = auth()->id();

        if ($data['status'] === 'done') $data['completed_at'] = now();

        $task = Task::create($data);
        return redirect()->route('tasks.show',$task)->with('success','تم إنشاء المهمة.');
    }

    public function show(Task $task)
    {
        return view('tasks.show', ['task'=>$task->load(['assignee','branch','creator','comments.user'])]);
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', [
            'task'=>$task,
            'branches'=> Branch::orderBy('name')->get(),
            'employees'=> Employee::orderBy('full_name')->get(),
        ]);
    }

    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'description' => ['nullable','string','max:5000'],
            'assigned_to' => ['nullable','exists:employees,id'],
            'branch_id' => ['nullable','exists:branches,id'],
            'priority' => ['required','in:low,medium,high,urgent'],
            'status' => ['required','in:todo,in_progress,done,blocked,archived'],
            'due_date' => ['nullable','date'],
        ]);

        $data['completed_at'] = ($data['status']==='done') ? now() : null;

        $task->update($data);
        return redirect()->route('tasks.show',$task)->with('success','تم تحديث المهمة.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success','تم حذف المهمة.');
    }

    // زر سريع لتغيير الحالة
    public function quickStatus(Request $request, Task $task)
    {
        $data = $request->validate([
            'status' => ['required','in:todo,in_progress,done,blocked,archived'],
        ]);

        $task->update([
            'status' => $data['status'],
            'completed_at' => $data['status']==='done' ? now() : null,
        ]);

        return back()->with('success','تم تحديث حالة المهمة.');
    }
}
