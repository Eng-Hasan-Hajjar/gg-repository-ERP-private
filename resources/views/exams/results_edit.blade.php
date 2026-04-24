@extends('layouts.app')
@section('title','درجات الامتحان')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-0">إدخال الدرجات — {{ $exam->title }}</h4>
    <div class="text-muted small">
     الدبلومة: {{ $exam->diploma->name }} {{ $exam->diploma->code }} — الحد الأعلى: {{ $exam->max_score }}
    </div>
  </div>
  <a class="btn btn-outline-secondary rounded-pill px-4 fw-bold" href="{{ route('exams.show',$exam) }}">
    <i class="bi bi-arrow-return-right"></i> رجوع
  </a>
</div>

<form class="card card-body border-0 shadow-sm mb-3" method="GET">
  <div class="row g-2">
    <div class="col-12 col-md-4">
      <input name="search" value="{{ request('search') }}" class="form-control" placeholder="بحث: اسم/رقم جامعي">
    </div>
    <div class="col-12 col-md-2 d-grid">
      <button class="btn btn-namaa fw-bold">بحث</button>
    </div>
  </div>
</form>

<form method="POST" action="{{ route('exams.results.update',$exam) }}">
  @csrf
  @method('PUT')

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>الطالب</th>
            <th>رقم جامعي</th>
            <th style="width:140px">الدرجة</th>
            <th style="width:160px">الحالة</th>
            <th>ملاحظات</th>
          </tr>
        </thead>
        <tbody>
       
          


@forelse($students as $i => $s)
<tr>

    <td class="fw-semibold">{{ $s->full_name }}</td>
    <td><code>{{ $s->university_id }}</code></td>

    <td>
        <input type="hidden"
               name="rows[{{ $i }}][student_id]"
               value="{{ $s->id }}">

        <input type="number"
               name="rows[{{ $i }}][score]"
               value="{{ old('rows.'.$i.'.score', optional($existing[$s->id] ?? null)->score) }}"
               class="form-control score-input"
               data-row="{{ $i }}"
               min="0"
               max="{{ $exam->max_score }}">
    </td>

    <td>
        <select name="rows[{{ $i }}][status]"
                class="form-select status-select"
                data-row="{{ $i }}"
                required>

            <option value="">-- اختر الحالة --</option>

            <option value="passed"
                {{ old('rows.'.$i.'.status', optional($existing[$s->id] ?? null)->status) == 'passed' ? 'selected' : '' }}>
                ناجح
            </option>

            <option value="failed"
                {{ old('rows.'.$i.'.status', optional($existing[$s->id] ?? null)->status) == 'failed' ? 'selected' : '' }}>
                راسب
            </option>

            <option value="absent"
                {{ old('rows.'.$i.'.status', optional($existing[$s->id] ?? null)->status) == 'absent' ? 'selected' : '' }}>
                غائب
            </option>

            <option value="excused"
                {{ old('rows.'.$i.'.status', optional($existing[$s->id] ?? null)->status) == 'excused' ? 'selected' : '' }}>
                معذور
            </option>

        </select>
    </td>

    <td>
        <input name="rows[{{ $i }}][notes]"
               value="{{ old('rows.'.$i.'.notes', optional($existing[$s->id] ?? null)->notes) }}"
               class="form-control"
               placeholder="اختياري">
    </td>

</tr>
@empty
<tr>
    <td colspan="5" class="text-center text-muted py-4">
        لا يوجد طلاب مطابقون
    </td>
</tr>
@endforelse






        </tbody>
      </table>
    </div>

    <div class="p-3">
      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
          </ul>
        </div>
      @endif

      <button class="btn btn-primary fw-bold px-4">
        <i class="bi bi-save"></i> حفظ الدرجات
      </button>
    </div>
  </div>
</form>













<script>
document.querySelectorAll('.status-select').forEach(function(select) {

    select.addEventListener('change', function() {

        let rowIndex = this.dataset.row;
        let scoreInput = document.querySelector('.score-input[data-row="'+rowIndex+'"]');
        let score = parseFloat(scoreInput.value);

        // تعطيل الدرجة في حالة غائب أو معذور
        if (this.value === 'absent' || this.value === 'excused') {
            scoreInput.value = '';
            scoreInput.disabled = true;
            return;
        } else {
            scoreInput.disabled = false;
        }

        // منع اختيار راسب إذا الدرجة >= 50
        if (this.value === 'failed' && score >= 50) {
            alert('لا يمكن وضع راسب لعلامة 50 أو أكثر');
            this.value = '';
        }

        // منع اختيار ناجح إذا الدرجة أقل من 50
        if (this.value === 'passed' && score < 50) {
            alert('لا يمكن وضع ناجح لعلامة أقل من 50');
            this.value = '';
        }

    });

});


// عند تغيير الدرجة يتم تصحيح الحالة تلقائياً
document.querySelectorAll('.score-input').forEach(function(input){

    input.addEventListener('input', function(){

        let rowIndex = this.dataset.row;
        let select = document.querySelector('.status-select[data-row="'+rowIndex+'"]');
        let score = parseFloat(this.value);

        if (isNaN(score)) return;

        if (score >= 50) {
            select.value = 'passed';
        } else {
            select.value = 'failed';
        }

    });

});
</script>



@endsection
