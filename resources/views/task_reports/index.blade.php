@extends('layouts.app')

@section('title', 'تقارير الموظفين')

@section('content')

    <div class="d-flex justify-content-between mb-3">

        <h4 class="fw-bold">تقارير المهام</h4>

        <a href="{{ route('reports.task.create') }}" class="btn btn-namaa rounded-pill px-4 fw-bold">

            <i class="bi bi-upload"></i>
            رفع تقرير

        </a>

    </div>


    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">

            <form method="GET">

                <div class="row g-2">

                    <div class="col-md-2">
                        <input type="text" name="search" class="form-control" placeholder="بحث..."
                            value="{{ request('search') }}">
                    </div>


                    <div class="col-md-2">

                        <select name="report_type" class="form-select">

                            <option value="">نوع التقرير</option>

                            <option value="daily" @selected(request('report_type') == 'daily')>
                                يومي
                            </option>

                            <option value="weekly" @selected(request('report_type') == 'weekly')>
                                أسبوعي
                            </option>

                            <option value="monthly" @selected(request('report_type') == 'monthly')>
                                شهري
                            </option>

                        </select>

                    </div>


                    <div class="col-md-2">

                        <select name="task_id" class="form-select">

                            <option value="">المهمة</option>

                            @foreach($tasks as $task)

                                <option value="{{ $task->id }}" @selected(request('task_id') == $task->id)>

                                    {{ $task->title }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    @if(auth()->user()->hasRole('super_admin'))

                        <div class="col-md-2">

                            <select name="employee_id" class="form-select">

                                <option value="">الموظف</option>

                                @foreach($employees as $emp)

                                    <option value="{{ $emp->id }}" @selected(request('employee_id') == $emp->id)>

                                        {{ $emp->full_name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    @endif


                    <div class="col-md-2">

                        <input type="date" name="from" class="form-control" value="{{ request('from') }}">

                    </div>


                    <div class="col-md-2">

                        <input type="date" name="to" class="form-control" value="{{ request('to') }}">

                    </div>

                </div>


                <div class="mt-2 d-flex gap-2">

                    <button class="btn btn-namaa px-4 fw-bold">

                        تصفية

                    </button>

                    <a href="{{ route('reports.task.index') }}" class="btn btn-outline-secondary">

                        إعادة ضبط

                    </a>

                </div>

            </form>

        </div>
    </div>






    <div class="mb-3 d-flex gap-2">

<a href="?quick=today"
class="btn btn-outline-dark">

تقارير اليوم

</a>

<a href="?quick=week"
class="btn btn-outline-dark">

هذا الأسبوع

</a>

<a href="?quick=month"
class="btn btn-outline-dark">

هذا الشهر

</a>

</div>




    <table class="table">

        <thead>
            <tr>

                <th>الموظف</th>
                <th>المهمة</th>
                <th>النوع</th>
                <th>التاريخ</th>
                <th>الملف</th>

            </tr>
        </thead>

        <tbody>

            @foreach($reports as $r)

                <tr>

                    <td>{{ $r->employee->full_name }}</td>

                    <td>{{ $r->task->title ?? '-' }}</td>

                    <td>{{ $r->report_type }}</td>

                    <td>{{ $r->report_date }}</td>

                    <td>

                        @if($r->file_path)

                            <a href="{{ asset('storage/' . $r->file_path) }}" target="_blank">

                                تحميل

                            </a>

                        @endif

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

@endsection