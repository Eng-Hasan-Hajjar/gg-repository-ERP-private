@csrf
@if(isset($task)) @method('PUT') @endif

<div class="row g-3">
  <div class="col-12 col-md-8">
    <label class="form-label fw-bold">عنوان المهمة</label>
    <input name="title" class="form-control" required
           value="{{ old('title', $task->title ?? '') }}"
           placeholder="مثال: تحضير تقرير المصاريف / متابعة تسجيل الطلاب">
  </div>

  <div class="col-6 col-md-2">
    <label class="form-label fw-bold">الأولوية</label>
    <select name="priority" class="form-select" required>
      @foreach(['low'=>'منخفض','medium'=>'متوسط','high'=>'عال','urgent'=>'عاجل'] as $k=>$v)
        <option value="{{ $k }}" @selected(old('priority', $task->priority ?? 'medium')==$k)>{{ $v }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-6 col-md-2">
    <label class="form-label fw-bold">الحالة</label>
    <select name="status" class="form-select" required>
      @foreach(['todo'=>'قيد الانتظار','in_progress'=>'قيد التنفيذ','done'=>'منجزة','blocked'=>'متوقفة','archived'=>'مؤرشفة'] as $k=>$v)
        <option value="{{ $k }}" @selected(old('status', $task->status ?? 'todo')==$k)>{{ $v }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-12 col-md-4">
    <label class="form-label fw-bold">الفرع</label>
    <select name="branch_id" class="form-select">
      <option value="">—</option>
      @foreach($branches as $b)
        <option value="{{ $b->id }}" @selected(old('branch_id', $task->branch_id ?? '')==$b->id)>{{ $b->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-12 col-md-4">
    <label class="form-label fw-bold">المسند له (موظف/مدرب)</label>
    <select name="assigned_to" class="form-select">
      <option value="">—</option>
      @foreach($employees as $e)
        <option value="{{ $e->id }}" @selected(old('assigned_to', $task->assigned_to ?? '')==$e->id)>
          {{ $e->full_name }} — {{ $e->branch->name ?? 'بدون فرع' }}
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-12 col-md-4">
    <label class="form-label fw-bold">تاريخ الاستحقاق</label>
    <input type="date" name="due_date" class="form-control"
           value="{{ old('due_date', isset($task) && $task->due_date ? $task->due_date->format('Y-m-d') : '') }}">
  </div>

  <div class="col-12">
    <label class="form-label fw-bold">الوصف</label>
    <textarea name="description" rows="4" class="form-control"
              placeholder="تفاصيل المهمة...">{{ old('description', $task->description ?? '') }}</textarea>
  </div>
</div>

@if($errors->any())
  <div class="alert alert-danger mt-3">
    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif

<div class="mt-3 d-flex flex-wrap gap-2">
  <button class="btn btn-namaa rounded-pill px-4 fw-bold">حفظ</button>
  <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">إلغاء</a>
</div>
