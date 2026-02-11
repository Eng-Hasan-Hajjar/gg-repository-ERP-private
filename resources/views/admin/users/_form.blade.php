@csrf

<div class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <div class="row g-3">

      <div class="col-md-6">
        <label class="form-label fw-bold">الاسم</label>
        <input type="text" name="name" class="form-control"
               value="{{ old('name',$user->name ?? '') }}" required>
      </div>

      <div class="col-md-6">
        <label class="form-label fw-bold">البريد الإلكتروني</label>
        <input type="email" name="email" class="form-control"
               value="{{ old('email',$user->email ?? '') }}" required>
      </div>

      <div class="col-md-6">
        <label class="form-label fw-bold">كلمة المرور</label>
        <input type="password" name="password" class="form-control">
        @isset($user)
        <div class="text-muted small mt-1">
          اتركها فارغة إن لم ترد التغيير
        </div>
        @endisset
      </div>

      <div class="col-md-6">
        <label class="form-label fw-bold">تأكيد كلمة المرور</label>
        <input type="password" name="password_confirmation" class="form-control">
      </div>

      <div class="col-12">
        <label class="form-label fw-bold">الأدوار</label>
        <div class="row">
          @foreach($roles as $r)
          <div class="col-md-4">
            <div class="form-check">
              <input class="form-check-input"
                     type="checkbox"
                     name="roles[]"
                     value="{{ $r->id }}"
                     @checked(in_array($r->id,$userRoles ?? []))>
              <label class="form-check-label">
                {{ $r->label }}
              </label>
            </div>
          </div>
          @endforeach
        </div>
      </div>

    </div>
  </div>
</div>

@if($errors->any())
<div class="alert alert-danger">
  <ul class="mb-0">
    @foreach($errors->all() as $e)
      <li>{{ $e }}</li>
    @endforeach
  </ul>
</div>
@endif

<div class="d-flex gap-2">
  <button class="btn btn-primary fw-bold px-4">حفظ</button>
  <a href="{{ route('admin.users.index') }}"
     class="btn btn-outline-secondary fw-bold px-4">إلغاء</a>
</div>
