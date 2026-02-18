@extends('layouts.app')
@section('title','المدربين والموظفين')

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
  <div>
    <h4 class="mb-1 fw-bold">المدربين والموظفين</h4>
    <div class="text-muted fw-semibold">ملفات — عقود — مستحقات — ربط بالدبلومات</div>
  </div>
  <a href="{{ route('employees.create') }}" class="btn btn-namaa rounded-pill px-4 fw-bold">
    <i class="bi bi-person-plus"></i> إضافة جديد
  </a>
</div>

<form class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <div class="row g-2">
      <div class="col-12 col-md-4">
        <input name="search" value="{{ request('search') }}" class="form-control" placeholder="بحث: الاسم / الكود / الهاتف">
      </div>

      <div class="col-6 col-md-2">
        <select name="type" class="form-select">
          <option value="">النوع (الكل)</option>
          <option value="trainer" @selected(request('type')=='trainer')>مدرب</option>
          <option value="employee" @selected(request('type')=='employee')>موظف</option>
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="status" class="form-select">
          <option value="">الحالة (الكل)</option>
          <option value="active" @selected(request('status')=='active')>نشط</option>
          <option value="inactive" @selected(request('status')=='inactive')>غير نشط</option>
        </select>
      </div>

      <div class="col-12 col-md-2">
        <select name="branch_id" class="form-select">
          <option value="">الفرع (الكل)</option>
          @foreach($branches as $b)
            <option value="{{ $b->id }}" @selected(request('branch_id')==$b->id)>{{ $b->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-12 col-md-2 d-grid">
        <button class="btn btn-namaa fw-bold">تطبيق</button>
      </div>
    </div>
  </div>
</form>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th class="hide-mobile">#</th>
          <th class="hide-mobile">الكود</th>
          <th>الاسم</th>
          <th>النوع</th>
          <th>الفرع</th>
          <th>الحالة</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($employees as $e)
          <tr>
            <td class="hide-mobile">{{ $e->id }}</td>
            <td class="hide-mobile"><code>{{ $e->code }}</code></td>
            <td class="fw-bold">{{ $e->full_name }}</td>
            <td>
              <span class="badge bg-{{ $e->type=='trainer' ? 'primary':'secondary' }}">
                {{ $e->type=='trainer' ? 'مدرب' : 'موظف' }}
              </span>
            </td>
            <td>{{ $e->branch->name ?? '-' }}</td>
            <td>
              <span class="badge bg-{{ $e->status=='active' ? 'success':'secondary' }}">
                {{ $e->status=='active' ? 'نشط' : 'غير نشط' }}
              </span>
            </td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('employees.show',$e) }}">
                <i class="bi bi-eye"></i> عرض
              </a>
              <a class="btn btn-sm btn-outline-dark" href="{{ route('employees.edit',$e) }}">
                <i class="bi bi-pencil"></i> تعديل
              </a>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-center text-muted py-4">لا يوجد بيانات</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">
  {{ $employees->links() }}
</div>
@endsection
