@extends('layouts.app')
@php($activeModule = 'finance')
@section('title', 'ملف الصندوق')

@section('content')
  <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
    <div>
      <h4 class="mb-1 fw-bold">{{ $cashbox->name }}</h4>
      <div class="text-muted fw-semibold">
        كود: <code>{{ $cashbox->code }}</code>
        — فرع: <b>{{ $cashbox->branch->name ?? '-' }}</b>
        — عملة: <b>{{ $cashbox->currency }}</b>
      </div>
    </div>

    <div class="d-flex flex-wrap gap-2">

      <a href="{{ route('cashboxes.transactions.pdf', $cashbox) }}" class="btn btn-danger rounded-pill px-4 fw-bold"
        target="_blank">

        <i class="bi bi-file-earmark-pdf"></i>
        تصدير PDF

      </a>

      @if(auth()->user()?->hasPermission('add_transaction'))
        <a href="{{ route('cashboxes.transactions.create', $cashbox) }}" class="btn btn-namaa rounded-pill px-4 fw-bold">
          <i class="bi bi-plus-circle"></i> إضافة حركة
        </a>
      @endif



      <a href="{{ route('cashboxes.transactions.index', $cashbox) }}" class="btn btn-namaa rounded-pill px-4 fw-bold"
        hidden>
        <i class="bi bi-arrow-left-right"></i> الحركات
      </a>
      <a href="{{ route('cashboxes.edit', $cashbox) }}" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
        <i class="bi bi-pencil"></i> تعديل
      </a>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-12 col-lg-12">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h6 class="fw-bold mb-3">البيانات</h6>
          <div class="row g-2 small">
            <div class="col-6"><b>الحالة:</b>
              <span class="badge bg-{{ $cashbox->status == 'active' ? 'success' : 'secondary' }}">
                {{ $cashbox->status == 'active' ? 'نشط' : 'غير نشط' }}
              </span>
            </div>
            <div class="col-6"><b>الرصيد الافتتاحي:</b> {{ $cashbox->opening_balance }} {{ $cashbox->currency }}</div>
            <div class="col-12 mt-2"><b>الرصيد الحالي (posted):</b> {{ $cashbox->current_balance }}
              {{ $cashbox->currency }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>









  <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">


  </div>

  <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
    <div>
      <h4 class="mb-0 fw-bold">حركات الصندوق</h4>
      <div class="text-muted fw-semibold">
        {{ $cashbox->name }} — <code>{{ $cashbox->code }}</code>
        — الرصيد الحالي: <b>{{ $cashbox->current_balance }} {{ $cashbox->currency }}</b>
      </div>
    </div>


  </div>

  <form class="card border-0 shadow-sm mb-3">
    <div class="card-body">
      <div class="row g-2">
        <div class="col-12 col-md-2">
          <input name="search" value="{{ request('search') }}" class="form-control" placeholder="بحث: تصنيف/مرجع/ملاحظات">
        </div>

        <div class="col-6 col-md-2">
          <select name="type" class="form-select">
            <option value="">النوع (الكل)</option>
            <option value="in" @selected(request('type') == 'in')>مقبوض</option>
            <option value="out" @selected(request('type') == 'out')>مدفوع</option>
          </select>
        </div>

        <div class="col-6 col-md-2">
          <select name="status" class="form-select">
            <option value="">الحالة (الكل)</option>
            <option value="draft" @selected(request('status') == 'draft')>معلّق</option>
            <option value="posted" @selected(request('status') == 'posted')>مُرحّل</option>
          </select>
        </div>



        <div class="col-6 col-md-2">
          <select name="only_students" class="form-select">
            <option value="">كل الحركات</option>
            <option value="1" @selected(request('only_students'))>
              دفعات الطلاب فقط
            </option>
          </select>
        </div>






        <div class="col-6 col-md-2">
          <select name="sort" class="form-select">
            <option value="trx_date" @selected(request('sort') == 'trx_date')>ترتيب حسب التاريخ</option>
            <option value="amount" @selected(request('sort') == 'amount')>ترتيب حسب المبلغ</option>
            <option value="id" @selected(request('sort') == 'id')>ترتيب حسب رقم الحركة</option>
          </select>
        </div>

        <div class="col-6 col-md-2">
          <select name="direction" class="form-select">
            <option value="desc" @selected(request('direction') == 'desc')>تنازلي</option>
            <option value="asc" @selected(request('direction') == 'asc')>تصاعدي</option>
          </select>
        </div>






        <div class="col-12 col-md-3 d-grid">
          <button class="btn btn-namaa fw-bold">تطبيق</button>
        </div>
































      </div>

      <div class="mt-3 small text-muted fw-semibold">
        إجمالي المقبوض (posted): <b>{{ number_format($postedIn, 2) }}</b>
        — إجمالي المدفوع (posted): <b>{{ number_format($postedOut, 2) }}</b>
      </div>










<!-- أزرار التحكم في الأعمدة -->
<div class="mt-3 mb-3 d-flex justify-content-end">
    <div class="dropdown">
        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="columnsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-layout-three-columns me-1"></i> الأعمدة
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow p-3" style="min-width: 240px;" aria-labelledby="columnsDropdown">
            <li class="mb-2 fw-bold text-center">إظهار / إخفاء الأعمدة</li>
            <li><hr class="dropdown-divider"></li>

            <li class="form-check mb-2">
                <input class="form-check-input column-toggle" type="checkbox" value="0" id="col-0" checked>
                <label class="form-check-label" for="col-0">#</label>
            </li>
            <li class="form-check mb-2">
                <input class="form-check-input column-toggle" type="checkbox" value="1" id="col-1" checked>
                <label class="form-check-label" for="col-1">التاريخ</label>
            </li>
            <li class="form-check mb-2">
                <input class="form-check-input column-toggle" type="checkbox" value="2" id="col-2" checked>
                <label class="form-check-label" for="col-2">الطالب</label>
            </li>
            <li class="form-check mb-2">
                <input class="form-check-input column-toggle" type="checkbox" value="3" id="col-3" checked>
                <label class="form-check-label" for="col-3">الدبلومة</label>
            </li>
            <li class="form-check mb-2">
                <input class="form-check-input column-toggle" type="checkbox" value="4" id="col-4" checked>
                <label class="form-check-label" for="col-4">النوع</label>
            </li>
            <li class="form-check mb-2">
                <input class="form-check-input column-toggle" type="checkbox" value="5" id="col-5" checked>
                <label class="form-check-label" for="col-5">المبلغ</label>
            </li>
            <li class="form-check mb-2">
                <input class="form-check-input column-toggle" type="checkbox" value="6" id="col-6" checked>
                <label class="form-check-label" for="col-6">تصنيف</label>
            </li>
            <li class="form-check mb-2">
                <input class="form-check-input column-toggle" type="checkbox" value="7" id="col-7" checked>
                <label class="form-check-label" for="col-7">مرجع</label>
            </li>
            <li class="form-check mb-2">
                <input class="form-check-input column-toggle" type="checkbox" value="8" id="col-8" checked>
                <label class="form-check-label" for="col-8">حالة</label>
            </li>
            <li class="form-check mb-2">
                <input class="form-check-input column-toggle" type="checkbox" value="9" id="col-9" checked disabled>
                <label class="form-check-label text-muted" for="col-9">إجراءات (ثابت)</label>
            </li>

            <li><hr class="dropdown-divider"></li>
            <li>
                <button type="button" class="btn btn-sm btn-outline-danger w-100" id="resetColumns">
                    <i class="bi bi-arrow-counterclockwise"></i> إعادة تعيين الكل
                </button>
            </li>
        </ul>
    </div>
</div>







        
    </div>
  </form>

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>

              <a href="?sort=trx_date&direction={{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                التاريخ
              </a>
            </th>



            <th>الطالب</th>
            <th>الدبلومة</th>
            <th>النوع</th>
            <th>المبلغ</th>
            <th>تصنيف</th>
            <th>مرجع</th>
            <th>حالة</th>
            <th class="text-end">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          @forelse($transactions as $t)
            <tr>
              <td>{{ $t->id }}</td>
              <td>{{ $t->trx_date->format('Y-m-d') }}</td>

              <td>

                {{optional(optional($t->account)->accountable)->full_name ?? '-' }}



              </td>

              <td>
                {{ optional($t->diploma)->name ?? '-' }}
              </td>



              <td>
   
                <span class="badge bg-{{ $t->type == 'in' ? 'success' : 'danger' }}">
                  {{ $t->type == 'in' ? 'مقبوض' : 'مدفوع' }}
                </span>
              </td>
              <td class="fw-bold">{{ $t->amount }} {{ $t->currency }}</td>
              <td>{{ $t->category ?? '-' }}</td>
              <td>{{ $t->reference ?? '-' }}</td>
              <td>
                <span class="badge bg-{{ $t->status == 'posted' ? 'primary' : 'secondary' }}">
                  {{ $t->status == 'posted' ? 'مُرحّل' : 'معلّق' }}
                </span>
              </td>
              <td class="text-end d-flex gap-1 justify-content-end flex-wrap">
                @if(auth()->user()?->hasPermission('approve_transaction'))
                  @if($t->status != 'posted')
                    <form method="POST" action="{{ route('cashboxes.transactions.post', [$cashbox, $t]) }}">
                      @csrf
                      <button class="btn btn-sm btn-outline-success">
                        <i class="bi bi-check2-circle"></i> ترحيل
                      </button>
                    </form>
                  @endif
                @endif
                <a class="btn btn-sm btn-outline-dark" href="{{ route('cashboxes.transactions.edit', [$cashbox, $t]) }}">
                  <i class="bi bi-pencil"></i> تعديل
                </a>

                <form method="POST" action="{{ route('cashboxes.transactions.destroy', [$cashbox, $t]) }}"
                  onsubmit="return confirm('حذف الحركة؟');">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i> حذف
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-4">لا يوجد حركات</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    {{ $transactions->links() }}
  </div>











<!-- جافاسكريبت محسّن -->
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const table = document.querySelector('.table');
    if (!table) return;

    const theadThs = table.querySelectorAll('thead th');
    const tbodyRows = table.querySelectorAll('tbody tr');

    const STORAGE_KEY = `hiddenColumns_cashbox_${{{ $cashbox->id }}}`;

    // تحميل الإعدادات المحفوظة
    let hiddenCols = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];

    // دالة لإخفاء / إظهار عمود
    const toggleColumn = (colIndex, show) => {
        const display = show ? '' : 'none';

        // العنوان
        if (theadThs[colIndex]) theadThs[colIndex].style.display = display;

        // الخلايا في جميع الصفوف
        tbodyRows.forEach(row => {
            const cell = row.cells[colIndex];
            if (cell) cell.style.display = display;
        });
    };

    // تطبيق الإعدادات عند التحميل
    const applySavedSettings = () => {
        hiddenCols.forEach(col => toggleColumn(col, false));

        // تحديث الـ checkboxes
        document.querySelectorAll('.column-toggle').forEach(chk => {
            const idx = parseInt(chk.value, 10);
            chk.checked = !hiddenCols.includes(idx);
        });
    };

    applySavedSettings();

    // التعامل مع تغيير الـ checkbox
    document.querySelectorAll('.column-toggle').forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const colIndex = parseInt(checkbox.value, 10);
            const isVisible = checkbox.checked;

            if (isVisible) {
                hiddenCols = hiddenCols.filter(c => c !== colIndex);
            } else {
                if (!hiddenCols.includes(colIndex)) hiddenCols.push(colIndex);
            }

            toggleColumn(colIndex, isVisible);
            localStorage.setItem(STORAGE_KEY, JSON.stringify(hiddenCols));
        });
    });

    // زر إعادة التعيين
    document.getElementById('resetColumns')?.addEventListener('click', () => {
        hiddenCols = [];
        localStorage.removeItem(STORAGE_KEY);
        location.reload(); // أسهل طريقة لإعادة بناء الجدول بشكل نظيف
    });
});
</script>
@endpush






@endsection