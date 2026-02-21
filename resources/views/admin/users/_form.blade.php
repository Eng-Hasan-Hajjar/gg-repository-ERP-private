@csrf

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <div class="row g-3">

      {{-- الاسم --}}
      <div class="col-md-6">
        <label class="form-label fw-bold">الاسم *</label>
        <input type="text" name="name"
               value="{{ old('name',$user->name ?? '') }}"
               class="form-control @error('name') is-invalid @enderror">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- الإيميل --}}
      <div class="col-md-6">
        <label class="form-label fw-bold">البريد الإلكتروني *</label>
        <input type="email" name="email"
               value="{{ old('email',$user->email ?? '') }}"
               class="form-control @error('email') is-invalid @enderror">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- كلمة المرور --}}
      <div class="col-md-6">
        <label class="form-label fw-bold">كلمة المرور {{ isset($user) ? '' : '*' }}</label>
        <input type="password" name="password"
               class="form-control @error('password') is-invalid @enderror">
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- التأكيد --}}
      <div class="col-md-6">
        <label class="form-label fw-bold">تأكيد كلمة المرور</label>
        <input type="password" name="password_confirmation"
               class="form-control">
      </div>

      {{-- الأدوار --}}
      <div class="col-12">
        <label class="form-label fw-bold">الأدوار *</label>

        <div class="row">
          @foreach($roles as $r)
          <div class="col-md-4">
            <div class="form-check">
              <input class="form-check-input"
                     type="checkbox"
                     name="roles[]"
                     value="{{ $r->id }}"
                     {{ in_array($r->id, old('roles',$userRoles ?? [])) ? 'checked' : '' }}>
              <label class="form-check-label">{{ $r->label }}</label>
            </div>
          </div>
          @endforeach
        </div>

        @error('roles')
        <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
      </div>

    </div>
  </div>
</div>

<div class="d-flex gap-2">
  <button class="btn btn-primary fw-bold px-4">حفظ</button>
  <a href="{{ route('admin.users.index') }}"
     class="btn btn-outline-secondary fw-bold px-4">إلغاء</a>
</div>