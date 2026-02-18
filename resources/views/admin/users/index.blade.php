@extends('layouts.app')
@php($activeModule = 'users')

@section('title','إدارة المستخدمين')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
  <div>
    <h4 class="mb-0 fw-bold">إدارة المستخدمين</h4>
    <div class="text-muted small">بحث + تصفية حسب الدور</div>
  </div>

  <a class="btn btn-primary rounded-pill px-4 fw-bold" href="{{ route('admin.users.create') }}">
    <i class="bi bi-person-plus"></i> مستخدم جديد
  </a>
</div>

<form class="card card-body border-0 shadow-sm mb-3" method="GET">
  <div class="row g-2">

    <div class="col-12 col-md-6">
      <input name="search" value="{{ request('search') }}"
             class="form-control"
             placeholder="بحث بالاسم أو البريد">
    </div>

    <div class="col-8 col-md-4">
      <select name="role_id" class="form-select">
        <option value="">كل الأدوار</option>
        @foreach($roles as $r)
          <option value="{{ $r->id }}" @selected(request('role_id')==$r->id)>
            {{ $r->label }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="col-4 col-md-2 d-grid">
      <button class="btn btn-dark fw-bold">تطبيق</button>
    </div>

  </div>
</form>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light">
        <tr>
          <th class="hide-mobile">#</th>
          <th>الاسم</th>
          <th>ساعات اليوم</th>
          <th class="hide-mobile">البريد</th>
          <th>الأدوار</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>

      <tbody>
        @forelse($users as $u)
        <tr>
          <td class="hide-mobile">{{ $u->id }}</td>


            <td class="fw-semibold">
                <div class="user-cell">
                    <span class="status-dot {{ $u->isOnline() ? 'online' : 'offline' }}"></span>
                    <span class="user-name">{{ $u->name }}</span>
                </div>
            </td>

            <td>
              {{ round(($u->todaySession->online_minutes ?? 0) / 60, 2) }} ساعة
              </td>


          <td class="hide-mobile"><code>{{ $u->email }}</code></td>
          <td>
            @foreach($u->roles as $r)
              <span class="badge bg-secondary">{{ $r->label }}</span>
            @endforeach
          </td>

          <td class="text-end">
            <a class="btn btn-sm btn-outline-primary"
               href="{{ route('admin.users.edit',$u) }}">
              <i class="bi bi-pencil"></i> تعديل
            </a>

            <a class="btn btn-sm btn-outline-dark"
               href="{{ route('admin.roles.index',['user'=>$u->id]) }}">
              <i class="bi bi-shield-lock"></i> الصلاحيات
            </a>

            @if(!$u->hasRole('super_admin'))
            <form method="POST"
                  action="{{ route('admin.users.destroy',$u) }}"
                  class="d-inline"
                  onsubmit="return confirm('هل أنت متأكد؟')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">
                <i class="bi bi-trash"></i>
              </button>
            </form>
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center text-muted py-4">
            لا يوجد مستخدمون
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">
  {{ $users->links() }}
</div>

@endsection
