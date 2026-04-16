@extends('layouts.app')

@section('title', 'رفع تقرير')

@section('content')



    <h4 class="fw-bold mb-3">رفع تقرير جديد</h4>

    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(!auth()->user()->employee)

        <div class="alert alert-warning">

            هذا الحساب غير مرتبط بموظف، لذلك لا يمكن رفع تقرير.

        </div>

    @endif
    <form method="POST" action="{{ route('reports.task.store') }}" enctype="multipart/form-data">

        @csrf

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">نوع التقرير</label>
                <select name="report_type" class="form-select" required>

                    <option value="daily">يومي</option>
                    <option value="weekly">أسبوعي</option>
                    <option value="monthly">شهري</option>

                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">تاريخ التقرير</label>
                <input type="date" name="report_date" class="form-control" value="{{ now()->toDateString() }}" readonly
                    required>
            </div>

            <div class="col-md-4" hidden>
                <label class="form-label">المهمة</label>

                <select name="task_id" class="form-select">

                    <option value="">بدون مهمة</option>

                    @foreach($tasks as $task)

                        <option value="{{ $task->id }}">
                            {{ $task->title }}
                        </option>

                    @endforeach

                </select>
            </div>

            <div class="col-md-12">
                <label class="form-label">عنوان التقرير</label>
                <input name="title" class="form-control" required>
            </div>

            <div class="col-md-12">
                <label class="form-label">ملاحظات</label>
                <textarea name="notes" class="form-control" rows="4"></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">ملف التقرير</label>
                <input type="file" name="file" class="form-control">
            </div>

        </div>

        <button class="btn btn-primary mt-3">
            رفع التقرير
        </button>

    </form>

@endsection