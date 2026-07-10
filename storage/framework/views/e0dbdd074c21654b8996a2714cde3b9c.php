<?php $__env->startSection('title', 'طلبات اللوجستيات'); ?>

<?php $__env->startSection('content'); ?>

  <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
    <div>
      <h4 class="fw-bold mb-0">طلبات اللوجستيات</h4>
      <div class="text-muted small">طلبات الشراء والإصلاح — مرتبة حسب الأولوية</div>
    </div>
    <?php if(auth()->user()?->hasPermission('submit_asset_request')): ?>
      <a href="<?php echo e(route('asset-requests.create')); ?>" class="btn btn-namaa rounded-pill px-4 fw-bold">
        <i class="bi bi-plus-circle"></i> تقديم طلب
      </a>
    <?php endif; ?>
  </div>

  
  <form class="card border-0 shadow-sm mb-3" method="GET">
    <div class="card-body">
      <div class="row g-2">

        
        <div class="col-12 col-md-4">
          <div class="input-group">
            <span class="input-group-text bg-white border-end-0">
              <i class="bi bi-search text-muted"></i>
            </span>
            <input type="text" name="search" class="form-control border-start-0"
              placeholder="ابحث بالعنوان أو الوصف..."
              value="<?php echo e(request('search')); ?>">
          </div>
        </div>

        
        <div class="col-6 col-md-2">
          <select name="status" class="form-select">
            <option value="">الحالة (الكل)</option>
            <option value="pending"     <?php if(request('status') == 'pending'): echo 'selected'; endif; ?>>قيد المراجعة</option>
            <option value="approved"    <?php if(request('status') == 'approved'): echo 'selected'; endif; ?>>مقبول</option>
            <option value="rejected"    <?php if(request('status') == 'rejected'): echo 'selected'; endif; ?>>مرفوض</option>
            <option value="transferred" <?php if(request('status') == 'transferred'): echo 'selected'; endif; ?>>مُرحَّل</option>
          </select>
        </div>

        
        <div class="col-6 col-md-2">
          <select name="type" class="form-select">
            <option value="">النوع (الكل)</option>
            <option value="purchase" <?php if(request('type') == 'purchase'): echo 'selected'; endif; ?>>شراء</option>
            <option value="repair"   <?php if(request('type') == 'repair'): echo 'selected'; endif; ?>>إصلاح</option>
            <option value="transfer" <?php if(request('type') == 'transfer'): echo 'selected'; endif; ?>>نقل</option>
          </select>
        </div>

        
        <div class="col-6 col-md-2">
          <select name="priority" class="form-select">
            <option value="">الأولوية (الكل)</option>
            <option value="urgent" <?php if(request('priority') == 'urgent'): echo 'selected'; endif; ?>>🔴 عاجل</option>
            <option value="normal" <?php if(request('priority') == 'normal'): echo 'selected'; endif; ?>>➖ عادية</option>
            <option value="low"    <?php if(request('priority') == 'low'): echo 'selected'; endif; ?>>🔽 منخفضة</option>
          </select>
        </div>

        
        <div class="col-6 col-md-3">
          <select name="branch_id" class="form-select">
            <option value="">الفرع (الكل)</option>
            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($branch->id); ?>" <?php if(request('branch_id') == $branch->id): echo 'selected'; endif; ?>>
                <?php echo e($branch->name); ?>

              </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        
        <?php if(auth()->user()?->hasPermission('manage_assets')): ?>
          <div class="col-6 col-md-3">
            <select name="user_id" class="form-select">
              <option value="">مقدم الطلب (الكل)</option>
              <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($u->id); ?>" <?php if(request('user_id') == $u->id): echo 'selected'; endif; ?>>
                  <?php echo e($u->name); ?>

                </option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
        <?php endif; ?>

        
        <div class="col-6 col-md-2">
          <input type="date" name="date_from" class="form-control"
            placeholder="من تاريخ"
            value="<?php echo e(request('date_from')); ?>">
        </div>

        
        <div class="col-6 col-md-2">
          <input type="date" name="date_to" class="form-control"
            placeholder="إلى تاريخ"
            value="<?php echo e(request('date_to')); ?>">
        </div>

        
        <div class="col-6 col-md-2 d-grid">
          <button class="btn btn-namaa fw-bold">
            <i class="bi bi-funnel"></i> تصفية
          </button>
        </div>

        <?php if(request()->hasAny(['search','status','type','priority','branch_id','user_id','date_from','date_to'])): ?>
          <div class="col-6 col-md-1 d-grid">
            <a href="<?php echo e(route('asset-requests.index')); ?>" class="btn btn-outline-secondary" title="مسح الفلاتر">
              <i class="bi bi-x-lg"></i>
            </a>
          </div>

          
          <div class="col-12">
            <div class="d-flex flex-wrap gap-1 mt-1">
              <?php if(request('search')): ?>
                <span class="badge bg-light text-dark border">
                  🔍 <?php echo e(request('search')); ?>

                </span>
              <?php endif; ?>
              <?php if(request('status')): ?>
                <span class="badge bg-light text-dark border">
                  الحالة: <?php echo e(['pending'=>'قيد المراجعة','approved'=>'مقبول','rejected'=>'مرفوض','transferred'=>'مُرحَّل'][request('status')] ?? request('status')); ?>

                </span>
              <?php endif; ?>
              <?php if(request('type')): ?>
                <span class="badge bg-light text-dark border">
                  النوع: <?php echo e(['purchase'=>'شراء','repair'=>'إصلاح','transfer'=>'نقل'][request('type')] ?? request('type')); ?>

                </span>
              <?php endif; ?>
              <?php if(request('priority')): ?>
                <span class="badge bg-light text-dark border">
                  الأولوية: <?php echo e(['urgent'=>'عاجل','normal'=>'عادية','low'=>'منخفضة'][request('priority')] ?? request('priority')); ?>

                </span>
              <?php endif; ?>
              <?php if(request('branch_id')): ?>
                <span class="badge bg-light text-dark border">
                  الفرع: <?php echo e($branches->firstWhere('id', request('branch_id'))?->name); ?>

                </span>
              <?php endif; ?>
              <?php if(request('date_from') || request('date_to')): ?>
                <span class="badge bg-light text-dark border">
                  📅 <?php echo e(request('date_from')); ?> — <?php echo e(request('date_to')); ?>

                </span>
              <?php endif; ?>
            </div>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </form>

  
  <div class="d-flex justify-content-between align-items-center mb-2">
    <div class="text-muted small">
      إجمالي النتائج:
      <strong><?php echo e($requests->total()); ?></strong> طلب
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>العنوان</th>
            <th>النوع</th>
            <th>الأولوية</th>
            <th>مقدم الطلب</th>
            <th>الفرع</th>
            <th>الأصل المرتبط</th>
            <th class="text-center">الحالة</th>
            <th>التاريخ</th>
            <th class="text-end">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="<?php echo e($r->priority === 'urgent' && $r->status === 'pending' ? 'table-danger' : ''); ?>">
              <td class="text-muted small"><?php echo e($r->id); ?></td>
              <td>
                <div class="fw-bold">
                  <?php if($r->priority === 'urgent'): ?>
                    <i class="bi bi-exclamation-circle-fill text-danger me-1"></i>
                  <?php endif; ?>
                  <?php echo e($r->title); ?>

                </div>
                <?php if($r->description): ?>
                  <div class="text-muted small"><?php echo e(Str::limit($r->description, 60)); ?></div>
                <?php endif; ?>
              </td>
              <td>
                <span class="badge <?php echo e($r->type === 'purchase' ? 'bg-primary' : 'bg-warning text-dark'); ?>">
                  <?php echo e($r->type_label); ?>

                </span>
              </td>
              <td>
                <span class="badge bg-<?php echo e($r->priority_color); ?>">
                  <i class="bi <?php echo e($r->priority_icon); ?> me-1"></i>
                  <?php echo e($r->priority_label); ?>

                </span>
              </td>
              <td class="small"><?php echo e($r->user->name ?? '-'); ?></td>
              <td class="small"><?php echo e($r->branch->name ?? '-'); ?></td>
              <td class="small text-muted"><?php echo e($r->asset->name ?? '—'); ?></td>
              <td class="text-center">
                <span class="badge bg-<?php echo e($r->status_color); ?>"><?php echo e($r->status_label); ?></span>
              </td>
              <td class="small text-muted"><?php echo e($r->created_at->format('Y-m-d')); ?></td>
              <td class="text-end">
                <div class="d-flex gap-1 justify-content-end flex-wrap">

                  
                  <a href="<?php echo e(route('asset-requests.print', $r)); ?>"
                     class="btn btn-sm btn-outline-secondary rounded-pill"
                     target="_blank" title="طباعة">
                    <i class="bi bi-printer"></i>
                  </a>

                  
                  <a href="<?php echo e(route('asset-requests.show', $r)); ?>" class="btn btn-sm btn-outline-info rounded-pill"
                    title="عرض التفاصيل">
                    <i class="bi bi-eye"></i>
                  </a>

                  
                  <?php if($r->status === 'pending' && ($r->user_id === auth()->id() || auth()->user()?->hasPermission('manage_assets'))): ?>
                    <a href="<?php echo e(route('asset-requests.edit', $r)); ?>" class="btn btn-sm btn-outline-primary rounded-pill"
                      title="تعديل الطلب">
                      <i class="bi bi-pencil"></i>
                    </a>
                  <?php endif; ?>

                  
                  <?php if(auth()->user()?->hasPermission('manage_assets') && $r->status === 'pending'): ?>
                    <form method="POST" action="<?php echo e(route('asset-requests.approve', $r)); ?>">
                      <?php echo csrf_field(); ?>
                      <button class="btn btn-sm btn-success">
                        <i class="bi bi-check2-circle"></i> قبول
                      </button>
                    </form>

                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                      data-bs-target="#rejectModal<?php echo e($r->id); ?>">
                      <i class="bi bi-x-circle"></i> رفض
                    </button>
                  <?php endif; ?>

                  
                  <?php if($r->status === 'approved' && auth()->user()?->hasPermission('manage_assets')): ?>
                    <button class="btn btn-sm btn-namaa" data-bs-toggle="modal" data-bs-target="#transferModal<?php echo e($r->id); ?>">
                      <i class="bi bi-box-arrow-in-down"></i> ترحيل
                    </button>
                  <?php endif; ?>

                  
                  <?php if(
                      $r->status !== 'transferred' &&
                      (auth()->user()?->hasPermission('manage_assets') || $r->created_by === auth()->id() || $r->user_id === auth()->id())
                    ): ?>
                    <form method="POST" action="<?php echo e(route('asset-requests.destroy', $r)); ?>" class="d-inline"
                      onsubmit="return confirm('هل أنت متأكد من حذف هذا الطلب؟')">
                      <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                      <button class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  <?php endif; ?>

                  
                  <?php if($r->status === 'rejected' && $r->manager_notes): ?>
                    <button class="btn btn-sm btn-outline-dark" data-bs-toggle="tooltip" title="<?php echo e($r->manager_notes); ?>">
                      <i class="bi bi-chat-text"></i>
                    </button>
                  <?php endif; ?>

                  
                  <?php if($r->status === 'transferred' && $r->transferred_to): ?>
                    <a href="<?php echo e(route('assets.show', $r->transferred_to)); ?>" class="btn btn-sm btn-outline-success">
                      <i class="bi bi-box-seam"></i> عرض الأصل
                    </a>
                  <?php endif; ?>

                </div>
              </td>
            </tr>

            
            <?php if(auth()->user()?->hasPermission('manage_assets') && $r->status === 'pending'): ?>
              <div class="modal fade" id="rejectModal<?php echo e($r->id); ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <form method="POST" action="<?php echo e(route('asset-requests.reject', $r)); ?>">
                      <?php echo csrf_field(); ?>
                      <div class="modal-header">
                        <h6 class="modal-title fw-bold">رفض الطلب: <?php echo e($r->title); ?></h6>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <label class="fw-bold mb-1">سبب الرفض (اختياري)</label>
                        <textarea name="manager_notes" class="form-control" rows="3"
                          placeholder="اكتب سبب الرفض..."></textarea>
                      </div>
                      <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button class="btn btn-danger fw-bold">تأكيد الرفض</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            <?php endif; ?>

            
            <?php if($r->status === 'approved' && auth()->user()?->hasPermission('manage_assets')): ?>
              <div class="modal fade" id="transferModal<?php echo e($r->id); ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title fw-bold">
                        <i class="bi bi-box-seam"></i> ترحيل إلى أصل — <?php echo e($r->title); ?>

                      </h5>
                      <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="<?php echo e(route('asset-requests.transfer', $r)); ?>">
                      <?php echo csrf_field(); ?>
                      <div class="modal-body">
                        <div class="mb-3">
                          <label class="form-label fw-bold">
                            اسم الأصل <span class="text-danger">*</span>
                          </label>
                          <input name="asset_name" class="form-control" value="<?php echo e($r->title); ?>" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label fw-bold">الرقم التسلسلي</label>
                          <input name="serial_number" class="form-control" placeholder="اختياري">
                        </div>
                        <div class="row g-2">
                          <div class="col-6">
                            <label class="form-label fw-bold">تاريخ الشراء</label>
                            <input name="purchase_date" type="date" class="form-control">
                          </div>
                          <div class="col-4">
                            <label class="form-label fw-bold">سعر الشراء</label>
                            <input name="purchase_cost" type="number" step="0.01" class="form-control" placeholder="0.00">
                          </div>
                          <div class="col-2">
                            <label class="form-label fw-bold">العملة</label>
                            <select name="currency" class="form-select">
                              <option value="USD">USD</option>
                              <option value="EUR">EUR</option>
                              <option value="TRY">TRY</option>
                              <option value="GBP">GBP</option>
                              <option value="SAR">SAR</option>
                            </select>
                          </div>
                        </div>
                        <div class="mb-3 mt-2">
                          <label class="form-label fw-bold">ملاحظات</label>
                          <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-namaa fw-bold">
                          <i class="bi bi-check2-circle"></i> تأكيد الترحيل
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            <?php endif; ?>

          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="10" class="text-center text-muted py-4">
                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                لا توجد طلبات
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3"><?php echo e($requests->links()); ?></div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('[data-bs-toggle="tooltip"]')
        .forEach(function (el) { new bootstrap.Tooltip(el); });
    });
  </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\customers\namaa\laravel11-auth\resources\views/asset_requests/index.blade.php ENDPATH**/ ?>