
<?php ($activeModule = 'finance'); ?>
<?php $__env->startSection('title', 'ملف الصندوق'); ?>

<?php $__env->startSection('content'); ?>
  <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
    <div>
      <h4 class="mb-1 fw-bold"><?php echo e($cashbox->name); ?></h4>
      <div class="text-muted fw-semibold">
        كود: <code><?php echo e($cashbox->code); ?></code>
        — فرع: <b><?php echo e($cashbox->branch->name ?? '-'); ?></b>
        — عملة: <b><?php echo e($cashbox->currency); ?></b>
      </div>
    </div>

    <div class="d-flex flex-wrap gap-2">

      <a href="<?php echo e(route('cashboxes.transactions.pdf', $cashbox)); ?>" class="btn btn-danger rounded-pill px-4 fw-bold"
        target="_blank">

        <i class="bi bi-file-earmark-pdf"></i>
        تصدير PDF

      </a>

      <?php if(auth()->user()?->hasPermission('add_transaction')): ?>
        <a href="<?php echo e(route('cashboxes.transactions.create', $cashbox)); ?>" class="btn btn-namaa rounded-pill px-4 fw-bold">
          <i class="bi bi-plus-circle"></i> إضافة حركة
        </a>
      <?php endif; ?>



      <a href="<?php echo e(route('cashboxes.transactions.index', $cashbox)); ?>" class="btn btn-namaa rounded-pill px-4 fw-bold"
        hidden>
        <i class="bi bi-arrow-left-right"></i> الحركات
      </a>
      <a href="<?php echo e(route('cashboxes.edit', $cashbox)); ?>" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
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
              <span class="badge bg-<?php echo e($cashbox->status == 'active' ? 'success' : 'secondary'); ?>">
                <?php echo e($cashbox->status == 'active' ? 'نشط' : 'غير نشط'); ?>

              </span>
            </div>
            <div class="col-6"><b>الرصيد الافتتاحي:</b> <?php echo e($cashbox->opening_balance); ?> <?php echo e($cashbox->currency); ?></div>
            <div class="col-12 mt-2"><b>الرصيد الحالي (posted):</b> <?php echo e($cashbox->current_balance); ?>

              <?php echo e($cashbox->currency); ?>

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
        <?php echo e($cashbox->name); ?> — <code><?php echo e($cashbox->code); ?></code>
        — الرصيد الحالي: <b><?php echo e($cashbox->current_balance); ?> <?php echo e($cashbox->currency); ?></b>
      </div>
    </div>


  </div>

  <form class="card border-0 shadow-sm mb-3">
    <div class="card-body">
      <div class="row g-2">
        <div class="col-12 col-md-2">
          <input name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="بحث: تصنيف/مرجع/ملاحظات">
        </div>

        <div class="col-6 col-md-2">
          <select name="type" class="form-select">
            <option value="">النوع (الكل)</option>
            <option value="in" <?php if(request('type') == 'in'): echo 'selected'; endif; ?>>مقبوض</option>
            <option value="out" <?php if(request('type') == 'out'): echo 'selected'; endif; ?>>مدفوع</option>
          </select>
        </div>

        <div class="col-6 col-md-2">
          <select name="status" class="form-select">
            <option value="">الحالة (الكل)</option>
            <option value="draft" <?php if(request('status') == 'draft'): echo 'selected'; endif; ?>>معلّق</option>
            <option value="posted" <?php if(request('status') == 'posted'): echo 'selected'; endif; ?>>مُرحّل</option>
          </select>
        </div>



        <div class="col-6 col-md-2">
          <select name="only_students" class="form-select">
            <option value="">كل الحركات</option>
            <option value="1" <?php if(request('only_students')): echo 'selected'; endif; ?>>
              دفعات الطلاب فقط
            </option>
          </select>
        </div>






        <div class="col-6 col-md-2">
          <select name="sort" class="form-select">
            <option value="trx_date" <?php if(request('sort') == 'trx_date'): echo 'selected'; endif; ?>>ترتيب حسب التاريخ</option>
            <option value="amount" <?php if(request('sort') == 'amount'): echo 'selected'; endif; ?>>ترتيب حسب المبلغ</option>
            <option value="id" <?php if(request('sort') == 'id'): echo 'selected'; endif; ?>>ترتيب حسب رقم الحركة</option>
          </select>
        </div>

        <div class="col-6 col-md-2">
          <select name="direction" class="form-select">
            <option value="desc" <?php if(request('direction') == 'desc'): echo 'selected'; endif; ?>>تنازلي</option>
            <option value="asc" <?php if(request('direction') == 'asc'): echo 'selected'; endif; ?>>تصاعدي</option>
          </select>
        </div>






        <div class="col-12 col-md-3 d-grid">
          <button class="btn btn-namaa fw-bold">تطبيق</button>
        </div>
































      </div>

      <div class="mt-3 small text-muted fw-semibold">
        إجمالي المقبوض (posted): <b><?php echo e(number_format($postedIn, 2)); ?></b>
        — إجمالي المدفوع (posted): <b><?php echo e(number_format($postedOut, 2)); ?></b>
      </div>










      <!-- أزرار التحكم في الأعمدة -->
      <div class="mt-3 mb-3 d-flex justify-content-end">
        <div class="dropdown">
          <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="columnsDropdown"
            data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-layout-three-columns me-1"></i> الأعمدة
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow p-3" style="min-width: 240px;"
            aria-labelledby="columnsDropdown">
            <li class="mb-2 fw-bold text-center">إظهار / إخفاء الأعمدة</li>
            <li>
              <hr class="dropdown-divider">
            </li>

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

            <li>
              <hr class="dropdown-divider">
            </li>
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

              <a href="?sort=trx_date&direction=<?php echo e(request('direction') == 'asc' ? 'desc' : 'asc'); ?>">
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
          <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td><?php echo e($t->id); ?></td>
              <td><?php echo e($t->trx_date->format('Y-m-d')); ?></td>

              <td>

                <?php echo e(optional(optional($t->account)->accountable)->full_name ?? '-'); ?>




              </td>

              <td>
                <?php echo e(optional($t->diploma)->name ?? '-'); ?>

              </td>



              <td>
                <span class="badge bg-<?php echo e(($typeMeta[$t->type]['color'] ?? 'secondary')); ?>">
                  <?php echo e($typeMeta[$t->type]['label'] ?? $t->type); ?>

                </span>
              </td>
              <td class="fw-bold"><?php echo e($t->amount); ?> <?php echo e($t->currency); ?></td>
              <td><?php echo e($t->category ?? '-'); ?></td>
              <td><?php echo e($t->reference ?? '-'); ?></td>
              <td>
                <span class="badge bg-<?php echo e($t->status == 'posted' ? 'primary' : 'secondary'); ?>">
                  <?php echo e($t->status == 'posted' ? 'مُرحّل' : 'معلّق'); ?>

                </span>
              </td>
              <td class="text-end d-flex gap-1 justify-content-end flex-wrap">
                <?php if(auth()->user()?->hasPermission('approve_transaction')): ?>



                  <?php if(auth()->user()?->hasPermission('approve_transaction')): ?>

                    <?php if($t->status === 'draft'): ?>

                      <form method="POST" action="<?php echo e(route('transactions.post', $t)); ?>">
                        <?php echo csrf_field(); ?>
                        <button class="btn btn-sm btn-outline-success">
                          <i class="bi bi-check2-circle"></i> ترحيل
                        </button>
                      </form>

                    <?php endif; ?>

                  <?php endif; ?>


                <?php endif; ?>
                <a class="btn btn-sm btn-outline-dark" href="<?php echo e(route('cashboxes.transactions.edit', [$cashbox, $t])); ?>">
                  <i class="bi bi-pencil"></i> تعديل
                </a>

                <form method="POST" action="<?php echo e(route('cashboxes.transactions.destroy', [$cashbox, $t])); ?>"
                  onsubmit="return confirm('حذف الحركة؟');">
                  <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                  <button class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i> حذف
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="8" class="text-center text-muted py-4">لا يوجد حركات</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    <?php echo e($transactions->links()); ?>

  </div>











  <!-- جافاسكريبت محسّن -->
  <?php $__env->startPush('scripts'); ?>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const table = document.querySelector('.table');
        if (!table) return;

        const theadThs = table.querySelectorAll('thead th');
        const tbodyRows = table.querySelectorAll('tbody tr');

        const STORAGE_KEY = `hiddenColumns_cashbox_$<?php echo e($cashbox->id); ?>`;

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
  <?php $__env->stopPush(); ?>






<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/cashboxes/show.blade.php ENDPATH**/ ?>