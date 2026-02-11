@extends('layouts.app')
@php($activeModule='users')

@section('title','تفاصيل الدور')

@section('content')

<div class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <h4 class="fw-bold">تفاصيل الدور</h4>

    <div class="row mt-3">
      <div class="col-md-4">
        <strong>الاسم البرمجي:</strong>
        <code>{{ $role->name }}</code>
      </div>

      <div class="col-md-4">
        <strong>الاسم المعروض:</strong>
        {{ $role->label }}
      </div>

      <div class="col-md-4">
        <strong>عدد المستخدمين:</strong>
        <span class="badge bg-secondary">{{ $role->users->count() }}</span>
      </div>
    </div>

    <hr>

    <h5 class="fw-bold">الصلاحيات (Tree View)</h5>

    @foreach($permissions as $module => $items)
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-header fw-bold">
        {{ ucfirst($module) }}
      </div>
      <div class="card-body">
        <ul class="list-unstyled">
          @foreach($items as $p)
          <li class="mb-2">
            <label>
              <input type="checkbox"
                     class="perm-toggle"
                     data-role="{{ $role->id }}"
                     data-perm="{{ $p->id }}"
                     @checked($role->permissions->contains($p->id))>
              {{ $p->label }}
            </label>
          </li>
          @endforeach
        </ul>
      </div>
    </div>
    @endforeach
  </div>
</div>






<hr>

<h5 class="fw-bold">سجل التغييرات على الصلاحيات</h5>

<table class="table table-sm table-bordered">
  <thead class="table-light">
    <tr>
      <th>التاريخ</th>
      <th>المستخدم</th>
      <th>الإجراء</th>
      <th>التفاصيل</th>
      <th>IP</th>
    </tr>
  </thead>
  <tbody>
    @foreach(\App\Models\AuditLog::where('model','Role')
            ->where('model_id',$role->id)
            ->latest()->take(20)->get() as $log)

    <tr>
      <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
      <td>{{ optional($log->user)->name ?? 'System' }}</td>
      <td>
        <span class="badge bg-{{ str_contains($log->action,'added') ? 'success' : 'danger' }}">
          {{ $log->action }}
        </span>
      </td>
      <td>{{ $log->description }}</td>
      <td><code>{{ $log->ip }}</code></td>
    </tr>

    @endforeach
  </tbody>
</table>










<script>
document.querySelectorAll('.perm-toggle').forEach(el=>{
  el.addEventListener('change', function(){
    let role = this.dataset.role;
    let perm = this.dataset.perm;

    fetch(`/admin/roles/${role}/toggle-permission/${perm}`, {
      method: 'POST',
      headers:{
        'X-CSRF-TOKEN':'{{ csrf_token() }}',
        'Accept':'application/json'
      }
    });
  });
});
</script>

@endsection
