@extends('layouts.app')
@section('title', 'تعديل خطة الدفع')

@section('content')

  {{-- ── أخطاء السيرفر ── --}}
  @if($errors->any())
    <div class="alert alert-danger fw-semibold mb-3">
      <i class="bi bi-exclamation-triangle"></i>
      @foreach($errors->all() as $e)
        <div>{{ $e }}</div>
      @endforeach
    </div>
  @endif

  <div class="container">

    <div class="section-header mb-3">
      <i class="bi bi-pencil"></i>
      تعديل خطة الدفع
    </div>

    <div class="glass-card p-4">

      <h5 class="fw-bold mb-3">{{ $plan->diploma->name }}</h5>

      <form method="POST" action="{{ route('payment.plan.update', $plan->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
        <input type="hidden" name="diploma_id" value="{{ $plan->diploma_id }}">

        <div class="row g-3">

          {{-- المبلغ الإجمالي ── نضيف id هنا ── --}}
          <div class="col-md-4">
            <label class="fw-bold">المبلغ الإجمالي</label>
            <input type="number" step="0.01" name="total_amount" id="edit-total-amount" value="{{ $plan->total_amount }}"
              class="form-control" required>
          </div>

          <div class="col-md-4">
            <label class="fw-bold">العملة</label>
            <select name="currency" class="form-select">
              <option value="USD" @selected($plan->currency == 'USD')>USD</option>
              <option value="EUR" @selected($plan->currency == 'EUR')>EUR</option>
              <option value="TRY" @selected($plan->currency == 'TRY')>TRY</option>
            </select>
          </div>

          <div class="col-md-4">
            <label class="fw-bold">نوع الدفع</label>
            <select name="payment_type" id="payment_type" class="form-select">
              <option value="full" @selected($plan->payment_type == 'full')>كامل</option>
              <option value="installments" @selected($plan->payment_type == 'installments')>دفعات</option>
            </select>
          </div>

          <div class="col-md-4 installments-box {{ $plan->payment_type == 'installments' ? '' : 'd-none' }}">
            <label class="fw-bold">عدد الدفعات</label>
            <select name="installments_count" id="installments_count" class="form-select">
              <option value="">اختر</option>
              <option value="2" @selected($plan->installments_count == 2)>2</option>
              <option value="3" @selected($plan->installments_count == 3)>3</option>
              <option value="4" @selected($plan->installments_count == 4)>4</option>
            </select>
          </div>

        </div>

        <hr>

        {{-- حقول الدفعات الحالية ── نضيف class هنا ── --}}
        <div id="installments_container">
          @if($plan->installments->count())
            @foreach($plan->installments as $i => $installment)
              <div class="row g-3 mt-2">
                <div class="col-md-6">
                  <label class="fw-bold">قيمة الدفعة {{ $loop->iteration }}</label>
                  <input type="number" step="0.01" min="0.01" name="installments[{{ $i }}][amount]"
                    value="{{ $installment->amount }}" class="form-control edit-installment-amount" required>
                  <small class="text-danger d-none" id="edit-err-{{ $loop->iteration }}"></small>
                </div>
                <div class="col-md-6">
                  <label class="fw-bold">تاريخ الدفعة {{ $loop->iteration }}</label>
                  <input type="date" name="installments[{{ $i }}][due_date]" value="{{ $installment->due_date }}"
                    class="form-control" required>
                </div>
              </div>
            @endforeach
          @endif
        </div>

        {{-- ملخص التحقق ── --}}
        <div id="edit-summary" class="mt-2"></div>

        <div class="text-end mt-4">
          <button class="btn btn-namaa btn-pill" id="edit-submit-btn">
            <i class="bi bi-check2-circle"></i> تحديث الخطة
          </button>
          {{-- زر الرجوع الصحيح ── --}}
          @if($plan->lead_id)
            <a href="{{ route('leads.show', $plan->lead_id) }}" class="btn btn-outline-secondary btn-pill">رجوع</a>
          @else
            <a href="{{ route('students.show', $plan->student_id) }}" class="btn btn-outline-secondary btn-pill">رجوع</a>
          @endif
        </div>

      </form>
    </div>
  </div>

@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {

      const totalInput = document.getElementById('edit-total-amount');
      const submitBtn = document.getElementById('edit-submit-btn');
      const summaryEl = document.getElementById('edit-summary');

      // ══ التحقق ══
      function validate() {
        const total = parseFloat(totalInput?.value) || 0;
        const inputs = document.querySelectorAll('.edit-installment-amount');
        if (!inputs.length) return true;

        let sum = 0;
        let allOk = true;

        inputs.forEach((inp, idx) => {
          const val = parseFloat(inp.value) || 0;
          const errEl = document.getElementById(`edit-err-${idx + 1}`);
          if (val <= 0 && inp.value !== '') {
            if (errEl) { errEl.textContent = 'يجب أن تكون القيمة أكبر من صفر'; errEl.classList.remove('d-none'); }
            inp.classList.add('is-invalid');
            allOk = false;
          } else {
            if (errEl) errEl.classList.add('d-none');
            inp.classList.remove('is-invalid');
          }
          sum += val;
        });

        const over = sum > total && total > 0;
        const remaining = total - sum;

        summaryEl.innerHTML = `
        <div class="mt-3 p-3 rounded border ${over ? 'border-danger bg-danger bg-opacity-10' : 'border-success bg-success bg-opacity-10'}">
          <div class="row text-center g-2">
            <div class="col-4">
              <div class="small text-muted">الإجمالي</div>
              <div class="fw-bold">${total.toFixed(2)}</div>
            </div>
            <div class="col-4">
              <div class="small text-muted">مجموع الدفعات</div>
              <div class="fw-bold ${over ? 'text-danger' : ''}">${sum.toFixed(2)}</div>
            </div>
            <div class="col-4">
              <div class="small text-muted">المتبقي</div>
              <div class="fw-bold ${remaining < 0 ? 'text-danger' : 'text-success'}">${remaining.toFixed(2)}</div>
            </div>
          </div>
          ${over ? '<div class="text-danger text-center small mt-2 fw-bold">⚠️ مجموع الدفعات يتجاوز الإجمالي!</div>' : ''}
        </div>`;

        const isValid = allOk && !over;
        submitBtn.disabled = !isValid;
        submitBtn.classList.toggle('opacity-50', !isValid);
        return isValid;
      }

      // ── ربط الأحداث على الحقول الموجودة ──
      function bindInputs() {
        document.querySelectorAll('.edit-installment-amount').forEach(inp => {
          inp.addEventListener('input', validate);
        });
      }

      if (totalInput) totalInput.addEventListener('input', validate);
      bindInputs();
      validate(); // تشغيل أول مرة

      // ══ إظهار/إخفاء قسم الدفعات ══
      document.getElementById('payment_type').addEventListener('change', function () {
        const isInst = this.value === 'installments';
        document.querySelector('.installments-box').classList.toggle('d-none', !isInst);
        if (!isInst) {
          document.getElementById('installments_container').innerHTML = '';
          summaryEl.innerHTML = '';
          submitBtn.disabled = false;
          submitBtn.classList.remove('opacity-50');
        }
      });

      // ══ بناء حقول الدفعات عند تغيير العدد ══
      document.getElementById('installments_count').addEventListener('change', function () {
        const count = parseInt(this.value);
        const container = document.getElementById('installments_container');
        container.innerHTML = '';
        summaryEl.innerHTML = '';

        if (!count) return;

        for (let i = 1; i <= count; i++) {
          container.innerHTML += `
          <div class="row g-3 mt-1">
            <div class="col-md-6">
              <label class="fw-bold">قيمة الدفعة ${i}</label>
              <input type="number" step="0.01" min="0.01"
                     name="installments[${i}][amount]"
                     class="form-control edit-installment-amount"
                     required placeholder="0.00">
              <small class="text-danger d-none" id="edit-err-${i}"></small>
            </div>
            <div class="col-md-6">
              <label class="fw-bold">تاريخ الدفعة ${i}</label>
              <input type="date" name="installments[${i}][due_date]"
                     class="form-control" required>
            </div>
          </div>`;
        }

        // إعادة ربط الأحداث على الحقول الجديدة
        bindInputs();
        validate();
      });

    });
  </script>
@endpush