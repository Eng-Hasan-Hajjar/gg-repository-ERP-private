@extends('layouts.app')
@section('title','طلب ميديا جديد')

@section('content')

<div class="card shadow-sm p-4">
    <h5 class="fw-bold mb-3">إرسال طلب لقسم الميديا</h5>

    <form method="POST" action="{{ route('media.store') }}">
        @csrf

        <div class="mb-3">
            <label>الدبلومة</label>
            <select name="diploma_id" class="form-select">
                <option value="">عام</option>
                @foreach($diplomas as $d)
                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>عنوان الطلب</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>تفاصيل الطلب</label>
            <textarea name="description" class="form-control" rows="4"></textarea>
        </div>

        <button class="btn btn-namaa w-100">إرسال</button>
    </form>
</div>

@endsection