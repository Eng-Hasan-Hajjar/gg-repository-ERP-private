<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: "DejaVu Sans", Arial, sans-serif; direction: rtl; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 6px; text-align: center; font-size: 11px; }
    th { background: #f3f4f6; }
    .emp { text-align: right; font-weight: bold; }
  </style>
</head>
<body>
  <h3 style="margin:0 0 10px 0;">تقويم الدوام الشهري — <?php echo e($month); ?></h3>

  <table>
    <thead>
      <tr>
        <th style="width:220px">الموظف</th>
        <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <th><?php echo e(\Carbon\Carbon::parse($date)->day); ?></th>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tr>
    </thead>
    <tbody>
      <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
          <td class="emp"><?php echo e($emp->full_name); ?> — <?php echo e($emp->branch->name ?? '-'); ?></td>
          <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php ($status = $recordsMap[$emp->id][$date] ?? null); ?>
            <td><?php echo e($letterMap[$status] ?? '-'); ?></td>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
  </table>

  <div style="margin-top:10px;font-size:12px;">
    P=حضور | L=تأخير | A=غياب | O=عطلة | V=إجازة | S=مجدول
  </div>
</body>
</html>
<?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/attendance/calendar_pdf.blade.php ENDPATH**/ ?>