@extends('layouts.app')
@php($activeModule='users')

@section('title','إسناد مستخدمين للدور')

@section('content')

<div class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <h4 class="fw-bold">إسناد مستخدمين للدور: {{ $role->label }}</h4>

    <form method="POST" action="{{ route('admin.roles.attachUser',$role) }}">
      @csrf
      <div class="row g-2">
        <div class="col-md-8">
          <select name="user_id" class="form-select">
            @foreach($users as $u)
            <option value="{{ $u->id }}">
              {{ $u->name }} — {{ $u->email }}
            </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-4 d-grid">
          <button class="btn btn-primary">إسناد المستخدم</button>
        </div>
      </div>
    </form>

    <hr>

    <h5 class="fw-bold">المستخدمون الحاليون في هذا الدور</h5>

    <ul class="list-group">
      @foreach($role->users as $u)
      <li class="list-group-item d-flex justify-content-between">
        <span>{{ $u->name }}</span>
        <span class="badge bg-secondary">{{ $u->email }}</span>
      </li>
      @endforeach
    </ul>

  </div>
</div>

@endsection
