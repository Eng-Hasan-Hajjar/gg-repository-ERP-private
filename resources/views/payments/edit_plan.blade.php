@extends('layouts.app')

@section('title','تعديل خطة الدفع')

@section('content')

<div class="container">

<div class="section-header mb-3">
<i class="bi bi-pencil"></i>
تعديل خطة الدفع
</div>

<div class="glass-card p-4">

<h5 class="fw-bold mb-3">
{{ $plan->diploma->name }}
</h5>

<form method="POST" action="{{ route('payment.plan.update',$plan->id) }}">

@csrf
@method('PUT')

<input type="hidden" name="student_id" value="{{ $student->id }}">
<input type="hidden" name="diploma_id" value="{{ $plan->diploma_id }}">

<div class="row g-3">

<div class="col-md-4">
<label class="fw-bold">المبلغ الإجمالي</label>
<input
type="number"
step="0.01"
name="total_amount"
value="{{ $plan->total_amount }}"
class="form-control"
required>
</div>

<div class="col-md-4">
<label class="fw-bold">العملة</label>

<select name="currency" class="form-select">

<option value="USD" {{ $plan->currency=='USD'?'selected':'' }}>
USD
</option>

<option value="EUR" {{ $plan->currency=='EUR'?'selected':'' }}>
EUR
</option>

<option value="TRY" {{ $plan->currency=='TRY'?'selected':'' }}>
TRY
</option>

</select>
</div>

<div class="col-md-4">
<label class="fw-bold">نوع الدفع</label>

<select name="payment_type" id="payment_type" class="form-select">

<option value="full" {{ $plan->payment_type=='full'?'selected':'' }}>
كامل
</option>

<option value="installments" {{ $plan->payment_type=='installments'?'selected':'' }}>
دفعات
</option>

</select>
</div>

<div class="col-md-4 installments-box {{ $plan->payment_type=='installments' ? '' : 'd-none' }}">

<label class="fw-bold">عدد الدفعات</label>

<select name="installments_count" id="installments_count" class="form-select">

<option value="">اختر</option>

<option value="2" {{ $plan->installments_count==2?'selected':'' }}>2</option>
<option value="3" {{ $plan->installments_count==3?'selected':'' }}>3</option>
<option value="4" {{ $plan->installments_count==4?'selected':'' }}>4</option>

</select>

</div>

</div>

<hr>

<div id="installments_container">

@if($plan->installments)

@foreach($plan->installments as $i => $installment)

<div class="row g-3 mt-2">

<div class="col-md-6">

<label class="fw-bold">
قيمة الدفعة {{ $loop->iteration }}
</label>

<input
type="number"
step="0.01"
name="installments[{{ $i }}][amount]"
value="{{ $installment->amount }}"
class="form-control">

</div>

<div class="col-md-6">

<label class="fw-bold">
تاريخ الدفعة {{ $loop->iteration }}
</label>

<input
type="date"
name="installments[{{ $i }}][due_date]"
value="{{ $installment->due_date }}"
class="form-control">

</div>

</div>

@endforeach

@endif

</div>

<div class="text-end mt-4">

<button class="btn btn-namaa btn-pill">

<i class="bi bi-check2-circle"></i>
تحديث الخطة

</button>

<a href="{{ route('students.show',$student->id) }}"
class="btn btn-outline-secondary btn-pill">

رجوع

</a>

</div>

</form>

</div>

</div>




























      @push('scripts')
        <script>

          document.getElementById('payment_type').addEventListener('change', function () {
            if (this.value === 'installments') {
              document.querySelector('.installments-box').classList.remove('d-none');
            }
            else {
              document.querySelector('.installments-box').classList.add('d-none');
                document.getElementById('installments_container').innerHTML = '';
            }

          });

          document.getElementById('installments_count').addEventListener('change', function () {

            let count = this.value;

            let container = document.getElementById('installments_container');

            container.innerHTML = '';

            for (let i = 1; i <= count; i++) {

                container.innerHTML += `

                  <div class="row g-3 mt-1">

                  <div class="col-md-6">

                  <label class="fw-bold">
                  قيمة الدفعة ${i}
                  </label>

                  <input type="number" step="0.01" name="installments[${i}][amount]" class="form-control" required>

                  </div>

                  <div class="col-md-6">

                  <label class="fw-bold">
                  تاريخ الدفعة ${i}
                  </label>

                  <input type="date" name="installments[${i}][due_date]" class="form-control" required>

                  </div>

                  </div>

                `;

            }

            });















function selectDiploma(id)
{
document.getElementById('selected_diploma').value = id;
}


document.getElementById('payment_type').addEventListener('change', function(){

if(this.value === 'installments')
{
document.querySelector('.installments-box').classList.remove('d-none');
}
else
{
document.querySelector('.installments-box').classList.add('d-none');
document.getElementById('installments_container').innerHTML='';
}

});


document.getElementById('installments_count').addEventListener('change', function(){

let count = this.value;
let container = document.getElementById('installments_container');

container.innerHTML = '';

for(let i=1;i<=count;i++)
{

container.innerHTML += `

<div class="row g-3 mt-1">

<div class="col-md-6">

<label class="fw-bold">
قيمة الدفعة ${i}
</label>

<input
type="number"
step="0.01"
name="installments[${i}][amount]"
class="form-control"
required>

</div>

<div class="col-md-6">

<label class="fw-bold">
تاريخ الدفعة ${i}
</label>

<input
type="date"
name="installments[${i}][due_date]"
class="form-control"
required>

</div>

</div>

`;

}

});






























          function cannotEditPlan(diploma)
          {

          document.getElementById('modalDiplomaName').innerText = diploma;

          const modal = new bootstrap.Modal(
          document.getElementById('planLockModal')
          );

          modal.show();

          }




          </script>
      @endpush



@endsection