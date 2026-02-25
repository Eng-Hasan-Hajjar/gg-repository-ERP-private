@extends('layouts.app')
@section('title','تفاصيل الامتحان')

@section('content')




@php
    if ($successRate >= 70) {
        $progressColor = 'bg-success';
        $textColor = 'text-success';
    } elseif ($successRate >= 50) {
        $progressColor = 'bg-warning';
        $textColor = 'text-warning';
    } else {
        $progressColor = 'bg-danger';
        $textColor = 'text-danger';
    }
@endphp

<div class="row g-4 mb-4">

    {{-- 🔹 كرت الإحصائيات --}}
    <div class="col-lg-4">

        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">

                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <div class="text-muted small">نسبة النجاح</div>
                        <h3 class="fw-bold {{ $textColor }}">
                            {{ $successRate }}%
                        </h3>
                    </div>
                    <div class="text-end">
                        <div class="text-success fw-bold">
                            {{ $passed }}
                        </div>
                        <small class="text-muted">ناجح</small>

                        <div class="text-danger fw-bold mt-2">
                            {{ $failed }}
                        </div>
                        <small class="text-muted">راسب</small>
                    </div>
                </div>

                <div class="progress" style="height:10px;">
                    <div class="progress-bar {{ $progressColor }}"
                         style="width: {{ $successRate }}%">
                    </div>
                </div>

            </div>
        </div>

    </div>


    {{-- 🔹 معلومات الامتحان --}}
    <div class="col-lg-8">

        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                    <div>
                        <h4 class="fw-bold mb-1">{{ $exam->title }}</h4>
                        <div class="text-muted small">
                            {{ $exam->exam_date?->format('Y-m-d') ?? '-' }}
                            — {{ $exam->type }}
                            — كود: {{ $exam->code ?? '-' }}
                        </div>
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                      
                        <a class="btn btn-namaa fw-bold rounded-pill px-4" href="{{ route('exams.results.edit',$exam) }}">
                          <i class="bi bi-pencil-square"></i> إدخال/تعديل الدرجات
                        </a>
                        <a href="{{ route('exams.index') }}"
                           class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
                            رجوع
                        </a>
                    </div>
                </div>

                <hr>

                <div class="row g-3">
                    <div class="col-md-6">
                        <b>الدبلومة:</b>
                        {{ $exam->diploma->name ?? '-' }}     {{ $exam->diploma->code ?? '-' }}
                    </div>

                    <div class="col-md-6">
                        <b>الفرع:</b>
                        {{ $exam->branch->name ?? '-' }}
                    </div>

                    <div class="col-md-6">
                        <b>المدرب:</b>
                        {{ $exam->trainer->full_name ?? '-' }}
                    </div>

                    <div class="col-md-6">
                        <b>الحد الأعلى:</b>
                        {{ $exam->max_score }}
                    </div>
                </div>

                @if($exam->notes)
                    <hr>
                    <div class="text-muted">
                        {{ $exam->notes }}
                    </div>
                @endif

            </div>
        </div>

    </div>

</div>




<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h6 class="fw-bold mb-3">النتائج المدخلة</h6>

    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light">
          <tr>
            <th>الطالب</th>
            <th>رقم جامعي</th>
            <th>الدرجة</th>
            <th>الحالة</th>
            <th>ملاحظات</th>
          </tr>
        </thead>
        <tbody>
          @forelse($exam->results as $r)
            <tr>
              <td class="fw-semibold">
                <a href="{{ route('students.show',$r->student) }}" class="text-decoration-none">
                  {{ $r->student->full_name }}
                </a>
              </td>
              <td><code>{{ $r->student->university_id }}</code></td>
                 {{-- ✅ عرض الدرجة --}}
              <td class="fw-bold">
                  {{ $r->score ?? '-' }}
              </td>

              {{-- ✅ عرض الحالة --}}
              <td>
                  <span class="badge 
                      @if($r->status === 'passed') bg-success
                      @elseif($r->status === 'failed') bg-danger
                      @elseif($r->status === 'absent') bg-dark
                      @elseif($r->status === 'excused') bg-info
                      @else bg-secondary
                      @endif">
                      {{ 
                          $r->status === 'passed' ? 'ناجح' :
                          ($r->status === 'failed' ? 'راسب' :
                          ($r->status === 'absent' ? 'غائب' :
                          ($r->status === 'excused' ? 'معذور' : 'لم تحدد')))
                      }}
                  </span>
              </td>
              <td>{{ $r->notes ?? '-' }}</td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center text-muted py-4">لا يوجد نتائج بعد</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
