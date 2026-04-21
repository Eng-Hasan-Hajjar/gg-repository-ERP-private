
<?php ($activeModule = 'reports'); ?>

<?php $__env->startSection('title','خريطة الفروع'); ?>
<?php $__env->startPush('styles'); ?>
<style>

/* ====== Clean ERP Style ====== */

.branches-header{
    font-weight:900;
    font-size:1.4rem;
    color:#0f172a;
}

.branches-sub{
    color:#64748b;
    font-size:.9rem;
    margin-top:4px;
}

/* الكرت */
.branch-card{
    background:#ffffff;
    border:1px solid #e5e7eb;
    border-radius:16px;
    padding:20px;
    height:100%;
    transition:all .2s ease;
    position:relative;
}

/* شريط الهوية */
.branch-card::before{
    content:'';
    position:absolute;
    top:0;
    right:0;
    height:4px;
    width:100%;
    background:linear-gradient(90deg,#0ea5e9,#10b981);
    border-radius:16px 16px 0 0;
}

.branch-card:hover{
    border-color:#10b981;
    box-shadow:0 10px 25px rgba(0,0,0,.06);
    transform:translateY(-2px);
}

/* اسم الفرع */
.branch-name{
    font-weight:800;
    font-size:1.05rem;
    color:#111827;
}

/* الرقم الكبير */
.branch-number{
    font-size:2rem;
    font-weight:900;
    color:#10b981;
    margin-top:10px;
}

/* النص */
.branch-meta{
    color:#6b7280;
    font-size:.85rem;
}

/* زر التفاصيل */
.branch-link{
    margin-top:14px;
    display:inline-block;
    font-size:.8rem;
    font-weight:700;
    color:#0ea5e9;
}

.branch-link i{
    font-size:.7rem;
}

</style>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>

<div class="page-wrapper">

    <div class="mb-4">
        <div class="namaa-header">خريطة الفروع</div>
        <div class="namaa-sub">عرض تفاعلي لعدد الطلاب في كل فرع — اضغط على أي فرع لعرض طلابه</div>
    </div>

    <div class="row g-4">
        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-4">
                <a href="<?php echo e(route('students.index', ['branch_id' => $b->id])); ?>"
                   class="text-decoration-none">

               <div class="branch-card">

                  <div class="branch-name">
                      <?php echo e($b->name); ?>

                  </div>

                  <div class="branch-number">
                      <?php echo e($b->students_count); ?>

                  </div>

                  <div class="branch-meta">
                      عدد الطلاب المسجلين في هذا الفرع
                  </div>

                  <div class="branch-link">
                      عرض الطلاب <i class="bi bi-arrow-left"></i>
                  </div>

              </div>


                </a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/reports/branches-map.blade.php ENDPATH**/ ?>