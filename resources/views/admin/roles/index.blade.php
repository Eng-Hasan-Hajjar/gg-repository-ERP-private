@extends('layouts.app')
@php($activeModule = 'users')

@section('title', 'إدارة الأدوار')

@section('content')

  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
    <div>
      <h4 class="mb-0 fw-bold">إدارة الأدوار والصلاحيات</h4>
      <div class="text-muted small">بحث متقدم + فلاتر ذكية</div>
    </div>
    @if(auth()->user()?->hasPermission('manage_roles'))
      <a class="btn btn-primary rounded-pill px-4 fw-bold" href="{{ route('admin.roles.create') }}">
        <i class="bi bi-shield-plus"></i> دور جديد
      </a>
    @endif
  </div>

  <form class="card card-body border-0 shadow-sm mb-3" method="GET">
    <div class="row g-2">

      <div class="col-12 col-md-6">
        <input name="search" value="{{ request('search') }}" class="form-control" placeholder="بحث باسم الدور أو الوصف">
      </div>

      <div class="col-8 col-md-4">
        <select name="has_users" class="form-select">
          <option value="">كل الأدوار</option>
          <option value="yes" @selected(request('has_users') == 'yes')>
            لديها مستخدمون
          </option>
          <option value="no" @selected(request('has_users') == 'no')>
            بلا مستخدمين
          </option>
        </select>
      </div>

      <div class="col-4 col-md-2 d-grid">
        <button class="btn btn-namaa fw-bold">تطبيق</button>
      </div>
    </div>
  </form>

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th class="hide-mobile">#</th>
            <th>الاسم المعروض</th>
            <th class="hide-mobile">الوصف</th>
            <th>المستخدمون</th>
            <th class="text-end">إجراءات</th>
          </tr>
        </thead>

        <tbody>
          @forelse($roles as $r)
            <tr>
              <td class="hide-mobile">{{ $r->id }}</td>
              <td class="fw-semibold">{{ $r->label }}</td>
              <td class="hide-mobile">{{ $r->description ?: '-' }}</td>
              <td>
                <span class="badge bg-secondary">
                  {{ $r->users_count }}
                </span>
              </td>

              <td class="text-end">


                <a class="btn btn-sm btn-outline-success" href="{{ route('admin.roles.show', $r) }}">
                  <i class="bi bi-eye"></i> تفاصيل
                </a>

                @if(auth()->user()?->hasPermission('assign_permissions'))
                  <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.roles.edit', $r) }}">
                    <i class="bi bi-pencil"></i> تعديل
                  </a>
                @endif

                @if(auth()->user()?->hasPermission('manage_roles'))
                  <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.roles.users', $r) }}">
                    <i class="bi bi-people"></i> مستخدمون
                  </a>
                @endif

                <form method="POST" action="{{ route('admin.roles.clone', $r) }}" class="d-inline">
                  @csrf
                  <button class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-copy"></i> نسخ
                  </button>
                </form>

                @if($r->name !== 'super_admin' && $r->users_count == 0)
                  <form method="POST" action="{{ route('admin.roles.destroy', $r) }}" class="d-inline"
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
                لا يوجد أدوار
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    {{ $roles->links() }}
  </div>

@endsection