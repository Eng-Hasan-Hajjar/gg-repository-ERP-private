
<?php $__env->startSection('title', 'الأصول'); ?>

<?php $__env->startSection('content'); ?>
  <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
    <div>
      <h4 class="mb-1 fw-bold">اللوجستيات وإدارة الأصول</h4>
      <div class="text-muted">إدارة الأجهزة والمعدات، تتبع الحالة والموقع والتكلفة.</div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
      <a href="<?php echo e(route('asset-categories.index')); ?>" class="btn btn-outline-dark rounded-pill fw-bold px-4">
        <i class="bi bi-tags"></i> تصنيفات الأصول
      </a>

      <?php if(auth()->user()->hasPermission('create_assets')): ?>
        <a href="<?php echo e(route('assets.create')); ?>" class="btn btn-primary rounded-pill fw-bold px-4">
          <i class="bi bi-plus-lg"></i> أصل جديد
        </a>
      <?php endif; ?>

      <a href="<?php echo e(route('assets.export.excel') . '?' . http_build_query(request()->all())); ?>"
        class="btn btn-success rounded-pill fw-bold px-4">
        <i class="bi bi-file-earmark-excel"></i> تصدير Excel
      </a>


    </div>
  </div>


  

  <div class="row g-2 mb-3">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">إجمالي الأصول (القطع)</h6>
                        <h3 class="mb-0 mt-1"><?php echo e($assets->sum('quantity')); ?></h3>
                    </div>
                    <i class="bi bi-box-seam fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">إجمالي الأصول (الأنواع)</h6>
                        <h3 class="mb-0 mt-1"><?php echo e($assets->count()); ?></h3>
                    </div>
                    <i class="bi bi-grid-3x3-gap-fill fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>




  <form class="card border-0 shadow-sm mb-3" method="GET" action="<?php echo e(route('assets.index')); ?>">
    <div class="card-body">
      <div class="row g-2">
        <div class="col-12 col-lg-4">
          <input name="search" value="<?php echo e(request('search')); ?>" class="form-control"
            placeholder="بحث: الاسم / AST / سيريال">
        </div>

        <div class="col-6 col-lg-2">
          <select name="branch_id" class="form-select">
            <option value="">كل الفروع</option>
            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($b->id); ?>" <?php if(request('branch_id') == $b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <div class="col-6 col-lg-3">
          <select name="asset_category_id" class="form-select">
            <option value="">كل التصنيفات</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($c->id); ?>" <?php if(request('asset_category_id') == $c->id): echo 'selected'; endif; ?>><?php echo e($c->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <div class="col-6 col-lg-2">
          <select name="condition" class="form-select">
            <option value="">كل الحالات</option>
            <option value="good" <?php if(request('condition') == 'good'): echo 'selected'; endif; ?>>جيد</option>
            <option value="maintenance" <?php if(request('condition') == 'maintenance'): echo 'selected'; endif; ?>>صيانة</option>
            <option value="out_of_service" <?php if(request('condition') == 'out_of_service'): echo 'selected'; endif; ?>>خارج الخدمة</option>
          </select>
        </div>

        <div class="col-6 col-lg-1 d-grid">
          <button class="btn btn-namaa fw-bold">تطبيق</button>
        </div>
      </div>
    </div>
  </form>

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th class="hide-mobile">#</th>
            <th class="hide-mobile">التاغ</th>
            <th>الأصل</th>
            <th>التصنيف</th>
            <th>الفرع</th>
            <th>الحالة</th>
            <th>العدد</th>
            <th>الموقع</th>
            <th class="text-end">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="hide-mobile"><?php echo e($a->id); ?></td>
              <td class="hide-mobile"><code><?php echo e($a->asset_tag); ?></code></td>
              <td class="fw-bold"><?php echo e($a->name); ?></td>
              <td><?php echo e($a->category->name ?? '-'); ?></td>
              <td><?php echo e($a->branch->name ?? '-'); ?></td>
              <td>
                <span class="badge <?php echo e($a->condition_badge_class); ?>">
                  <?php echo e($a->condition_label); ?>

                </span>
              </td>
              <td>
                <span class="badge bg-info text-dark">
                  <i class="bi bi-layers"></i> <?php echo e($a->quantity ?? 1); ?>

                </span>
              </td>
              <td><?php echo e($a->location ?? '-'); ?></td>
              <td class="text-end">
                <?php if(auth()->user()->hasPermission('view_assets')): ?>
                  <a href="<?php echo e(route('assets.show', $a)); ?>" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye"></i> عرض
                  </a>
                <?php endif; ?>
                <?php if(auth()->user()->hasPermission('edit_assets')): ?>

                  <a href="<?php echo e(route('assets.edit', $a)); ?>" class="btn btn-sm btn-outline-dark">
                    <i class="bi bi-pencil"></i> تعديل
                  </a>
                <?php endif; ?>

                <?php if(auth()->user()->hasPermission('delete_assets')): ?>

                  <form class="d-inline" method="POST" action="<?php echo e(route('assets.destroy', $a)); ?>"
                    onsubmit="return confirm('تأكيد حذف الأصل؟')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-sm btn-outline-danger">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="8" class="text-center text-muted py-4">لا يوجد أصول</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    <?php echo e($assets->links()); ?>

  </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/assets/index.blade.php ENDPATH**/ ?>