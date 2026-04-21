
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
      <a  hidden href="<?php echo e(route('cashboxes.show', $cashbox)); ?>" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
        رجوع للصندوق
      </a>
      <?php if(auth()->user()?->hasPermission('add_transaction')): ?>
        <a href="<?php echo e(route('cashboxes.transactions.create', $cashbox)); ?>" class="btn btn-namaa rounded-pill px-4 fw-bold">
          <i class="bi bi-plus-circle"></i> إضافة حركة
        </a>
      <?php endif; ?>
      <a href="<?php echo e(route('cashboxes.transactions.pdf', $cashbox)); ?>" class="btn btn-danger rounded-pill px-4 fw-bold"
        target="_blank">
        <i class="bi bi-file-earmark-pdf"></i> تصدير PDF
      </a>
      <a href="<?php echo e(route('cashboxes.transactions.excel', $cashbox)); ?>?<?php echo e(http_build_query(request()->all())); ?>"
        class="btn btn-success rounded-pill px-4 fw-bold">
        <i class="bi bi-file-earmark-excel"></i> تصدير Excel
      </a>
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

        
        <div class="col-12 col-md-3">
          <input name="search" value="<?php echo e(request('search')); ?>" class="form-control"
            placeholder="بحث: تصنيف / مرجع / ملاحظات">
        </div>

        
        <div class="col-6 col-md-2">
          <select name="type" class="form-select">
            <option value="">النوع (الكل)</option>
            <option value="in" <?php if(request('type') == 'in'): echo 'selected'; endif; ?>>مقبوض</option>
            <option value="out" <?php if(request('type') == 'out'): echo 'selected'; endif; ?>>مدفوع</option>
            <option value="transfer" <?php if(request('type') == 'transfer'): echo 'selected'; endif; ?>>مناقلة</option>
            <option value="exchange" <?php if(request('type') == 'exchange'): echo 'selected'; endif; ?>>تصريف</option>
          </select>
        </div>

        
        <div class="col-6 col-md-2">
          <select name="category" class="form-select">
            <option value="">التصنيف (الكل)</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($key); ?>" <?php if(request('category') == $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
            <option value="1" <?php if(request('only_students')): echo 'selected'; endif; ?>>دفعات الطلاب فقط</option>
          </select>
        </div>

        
        <div class="col-6 col-md-2">
          <select name="sort" class="form-select">
            <option value="trx_date" <?php if(request('sort', 'trx_date') == 'trx_date'): echo 'selected'; endif; ?>>ترتيب: التاريخ</option>
            <option value="amount" <?php if(request('sort') == 'amount'): echo 'selected'; endif; ?>>ترتيب: المبلغ</option>
            <option value="id" <?php if(request('sort') == 'id'): echo 'selected'; endif; ?>>ترتيب: رقم الحركة</option>
          </select>
        </div>

        
        <div class="col-6 col-md-2">
          <select name="direction" class="form-select">
            <option value="desc" <?php if(request('direction', 'desc') == 'desc'): echo 'selected'; endif; ?>>تنازلي</option>
            <option value="asc" <?php if(request('direction') == 'asc'): echo 'selected'; endif; ?>>تصاعدي</option>
          </select>
        </div>

        
        <div class="col-12 col-md-2 d-grid">
          <button class="btn btn-namaa fw-bold">تطبيق</button>
        </div>

      </div>

      
      <div class="mt-3 small text-muted fw-semibold">
        إجمالي المقبوض (posted): <b class="text-success"><?php echo e(number_format($postedIn, 2)); ?> <?php echo e($cashbox->currency); ?></b>
        — إجمالي المدفوع (posted): <b class="text-danger"><?php echo e(number_format($postedOut, 2)); ?> <?php echo e($cashbox->currency); ?></b>
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
              <a
                href="?sort=trx_date&direction=<?php echo e(request('direction', 'desc') == 'asc' ? 'desc' : 'asc'); ?>&<?php echo e(http_build_query(request()->except(['sort', 'direction']))); ?>">
                التاريخ
              </a>
            </th>
            <th>الطالب</th>
            <th>الدبلومة</th>
            <th>النوع</th>
            <th>المبلغ</th>
            <th>التصنيف</th>
            <th>التصنيف الثانوي</th>
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
              <td><?php echo e(optional(optional($t->account)->accountable)->full_name ?? '-'); ?></td>
              <td><?php echo e(optional($t->diploma)->name ?? '-'); ?></td>

              
              <td>
                <span class="badge bg-<?php echo e($typeMeta[$t->type]['color'] ?? 'secondary'); ?>">
                  <?php echo e($typeMeta[$t->type]['label'] ?? $t->type); ?>

                </span>
              </td>

              
              <td class="fw-bold">
                <?php echo e($t->amount); ?> <?php echo e($t->currency); ?>

                <?php if($t->foreign_amount && $t->foreign_currency): ?>
                  <br><small class="text-muted fw-normal">
                    (<?php echo e($t->foreign_amount); ?> <?php echo e($t->foreign_currency); ?>)
                  </small>
                <?php endif; ?>
              </td>

              <td><?php echo e($t->category ?? '-'); ?></td>
              <td>
                <?php if($t->sub_category): ?>
                  <span class="text-muted small"><?php echo e($t->sub_category); ?></span>
                <?php else: ?>
                  -
                <?php endif; ?>
              </td>
              <td><?php echo e($t->reference ?? '-'); ?></td>

              
              <td>
                <span class="badge bg-<?php echo e($t->status == 'posted' ? 'primary' : 'secondary'); ?>">
                  <?php echo e($t->status == 'posted' ? 'مُرحّل' : 'معلّق'); ?>

                </span>
              </td>

              
              <td class="text-end">
                <div class="d-flex gap-1 justify-content-end flex-wrap">

                  
                  <button type="button" class="btn btn-sm btn-outline-info btn-detail" data-id="<?php echo e($t->id); ?>"
                    data-url="<?php echo e(route('cashboxes.transactions.detail', [$cashbox, $t])); ?>" title="تفاصيل الحركة">
                    <i class="bi bi-eye"></i> تفاصيل
                  </button>

                  <?php if(auth()->user()?->hasPermission('approve_transaction')): ?>
                    <?php if($t->status === 'draft'): ?>
                      <form method="POST" action="<?php echo e(route('cashboxes.transactions.post', [$cashbox, $t])); ?>">
                        <?php echo csrf_field(); ?>
                        <button class="btn btn-sm btn-outline-success">
                          <i class="bi bi-check2-circle"></i> ترحيل
                        </button>
                      </form>
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

                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="11" class="text-center text-muted py-4">لا يوجد حركات</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3"><?php echo e($transactions->links()); ?></div>


  
  <div class="modal fade" id="transactionDetailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content border-0 shadow">

        
        <div class="modal-header" id="detail-modal-header" style="background: linear-gradient(135deg,#11998e,#38ef7d);">
          <h5 class="modal-title text-white fw-bold" id="detailModalLabel">
            <i class="bi bi-receipt"></i> تفاصيل الحركة
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        
        <div class="modal-body p-0">

          
          <div id="detail-loading" class="text-center py-5">
            <div class="spinner-border text-success" role="status"></div>
            <div class="mt-2 text-muted small">جارٍ التحميل...</div>
          </div>

          
          <div id="detail-content" style="display:none;">

            
            <div id="detail-summary-bar"
              class="d-flex align-items-center justify-content-between flex-wrap gap-2 px-4 py-3"
              style="background:#f8f9fa; border-bottom:1px solid #eee;">
              <div>
                <span class="text-muted small">رقم الحركة</span>
                <div class="fw-bold fs-5" id="d-id"></div>
              </div>
              <div>
                <span class="text-muted small">النوع</span>
                <div><span class="badge fs-6 px-3" id="d-type-badge"></span></div>
              </div>
              <div>
                <span class="text-muted small">المبلغ</span>
                <div class="fw-bold fs-5" id="d-amount"></div>
              </div>
              <div>
                <span class="text-muted small">الحالة</span>
                <div><span class="badge fs-6 px-3" id="d-status-badge"></span></div>
              </div>
            </div>

            
            <div class="px-4 py-3">
              <div class="row g-3">

                <div class="col-6 col-md-4">
                  <div class="detail-field">
                    <div class="detail-label"><i class="bi bi-calendar3 text-success"></i> التاريخ</div>
                    <div class="detail-value" id="d-date"></div>
                  </div>
                </div>

                <div class="col-6 col-md-4">
                  <div class="detail-field">
                    <div class="detail-label"><i class="bi bi-building text-primary"></i> الصندوق</div>
                    <div class="detail-value" id="d-cashbox"></div>
                  </div>
                </div>

                <div class="col-6 col-md-4">
                  <div class="detail-field">
                    <div class="detail-label"><i class="bi bi-geo-alt text-warning"></i> الفرع</div>
                    <div class="detail-value" id="d-branch"></div>
                  </div>
                </div>

                <div class="col-6 col-md-4">
                  <div class="detail-field">
                    <div class="detail-label"><i class="bi bi-tag text-info"></i> التصنيف الرئيسي</div>
                    <div class="detail-value" id="d-category"></div>
                  </div>
                </div>

                <div class="col-6 col-md-4">
                  <div class="detail-field">
                    <div class="detail-label"><i class="bi bi-tags text-secondary"></i> التصنيف الثانوي</div>
                    <div class="detail-value" id="d-sub-category"></div>
                  </div>
                </div>

                <div class="col-6 col-md-4">
                  <div class="detail-field">
                    <div class="detail-label"><i class="bi bi-hash text-dark"></i> المرجع</div>
                    <div class="detail-value" id="d-reference"></div>
                  </div>
                </div>

                <div class="col-6 col-md-4">
                  <div class="detail-field">
                    <div class="detail-label"><i class="bi bi-person text-success"></i> الطالب</div>
                    <div class="detail-value" id="d-student"></div>
                  </div>
                </div>

                <div class="col-6 col-md-4">
                  <div class="detail-field">
                    <div class="detail-label"><i class="bi bi-mortarboard text-primary"></i> الدبلومة</div>
                    <div class="detail-value" id="d-diploma"></div>
                  </div>
                </div>

                <div class="col-6 col-md-4" id="d-foreign-wrap" style="display:none;">
                  <div class="detail-field">
                    <div class="detail-label"><i class="bi bi-currency-exchange text-info"></i> مبلغ التصريف</div>
                    <div class="detail-value" id="d-foreign"></div>
                  </div>
                </div>

                <div class="col-6 col-md-4">
                  <div class="detail-field">
                    <div class="detail-label"><i class="bi bi-clock text-muted"></i> تاريخ الترحيل</div>
                    <div class="detail-value" id="d-posted-at"></div>
                  </div>
                </div>

                <div class="col-12" id="d-notes-wrap">
                  <div class="detail-field">
                    <div class="detail-label"><i class="bi bi-chat-left-text text-secondary"></i> ملاحظات</div>
                    <div class="detail-value" id="d-notes"></div>
                  </div>
                </div>

                <div class="col-12" id="d-attachment-wrap" style="display:none;">
                  <div class="detail-field">
                    <div class="detail-label"><i class="bi bi-paperclip text-danger"></i> المرفق</div>
                    <div class="detail-value">
                      <a id="d-attachment-link" href="#" target="_blank" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-file-earmark-pdf"></i> عرض المرفق
                      </a>
                    </div>
                  </div>
                </div>

              </div>
            </div>

          </div>
          

        </div>
        

        <div class="modal-footer bg-light">
          <a id="d-edit-link" href="#" class="btn btn-outline-dark rounded-pill px-4">
            <i class="bi bi-pencil"></i> تعديل
          </a>
          <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">إغلاق</button>
        </div>

      </div>
    </div>
  </div>
  












  <div class="mt-3">
    <?php echo e($transactions->links()); ?>

  </div>









  <?php $__env->startPush('styles'); ?>
    <style>
      .detail-field {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 10px 14px;
        height: 100%;
        border: 1px solid #eee;
      }

      .detail-label {
        font-size: 11px;
        color: #888;
        font-weight: 600;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
      }

      .detail-value {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a2e;
      }
    </style>
  <?php $__env->stopPush(); ?>





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









      document.addEventListener('DOMContentLoaded', () => {

        const modal = new bootstrap.Modal(document.getElementById('transactionDetailModal'));
        const loading = document.getElementById('detail-loading');
        const content = document.getElementById('detail-content');

        // ── مساعد: ضع قيمة في العنصر ──
        const set = (id, val) => {
          const el = document.getElementById(id);
          if (el) el.textContent = val || '-';
        };

        // ── عند الضغط على أي زر تفاصيل ──
        document.querySelectorAll('.btn-detail').forEach(btn => {
          btn.addEventListener('click', () => {
            const url = btn.dataset.url;
            const id = btn.dataset.id;

            // إعادة تعيين المودال
            loading.style.display = 'block';
            content.style.display = 'none';
            modal.show();

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
              .then(r => r.json())
              .then(d => {
                // ── شريط الملخص ──
                set('d-id', '#' + d.id);
                document.getElementById('d-type-badge').textContent = d.type_label;
                document.getElementById('d-type-badge').className = `badge fs-6 px-3 bg-${d.type_color}`;
                document.getElementById('d-amount').textContent = d.amount + ' ' + d.currency;
                document.getElementById('d-status-badge').textContent = d.status_label;
                document.getElementById('d-status-badge').className = `badge fs-6 px-3 bg-${d.status_color}`;

                // ── حقول التفاصيل ──
                set('d-date', d.trx_date);
                set('d-cashbox', d.cashbox_name);
                set('d-branch', d.branch_name);
                set('d-category', d.category);
                set('d-sub-category', d.sub_category);
                set('d-reference', d.reference);
                set('d-student', d.student);
                set('d-diploma', d.diploma);
                set('d-posted-at', d.posted_at);
                set('d-notes', d.notes);

                // ── تصريف ──
                const foreignWrap = document.getElementById('d-foreign-wrap');
                if (d.foreign_amount && d.foreign_currency) {
                  set('d-foreign', d.foreign_amount + ' ' + d.foreign_currency);
                  foreignWrap.style.display = 'block';
                } else {
                  foreignWrap.style.display = 'none';
                }

                // ── مرفق ──
                const attachWrap = document.getElementById('d-attachment-wrap');
                if (d.attachment_url) {
                  document.getElementById('d-attachment-link').href = d.attachment_url;
                  attachWrap.style.display = 'block';
                } else {
                  attachWrap.style.display = 'none';
                }

                // ── رابط تعديل ──
                // نبني رابط التعديل من URL الحالي
                const editUrl = window.location.pathname.replace('/transactions', '/transactions/') + d.id + '/edit';
                document.getElementById('d-edit-link').href = editUrl;

                loading.style.display = 'none';
                content.style.display = 'block';
              })
              .catch(() => {
                loading.innerHTML = '<div class="text-danger py-4 text-center">⚠️ تعذّر تحميل البيانات</div>';
              });
          });
        });

      });







    </script>
  <?php $__env->stopPush(); ?>






<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/cashboxes/show.blade.php ENDPATH**/ ?>