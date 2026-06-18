
<?php $__env->startSection('title', 'مراقبة الموارد'); ?>

<?php $__env->startSection('content'); ?>

  <div class="namaa-hero mb-4">
    <h1>مراقبة موارد السيرفر</h1>
    <p class="section-note">
      متابعة حية للمعالج والذاكرة والتخزين وقاعدة البيانات — تحديث تلقائي كل 10 ثوانٍ
    </p>
  </div>
  <?php if(session('success')): ?>
    <div class="alert alert-success py-2 mb-3"><?php echo e(session('success')); ?></div>
  <?php endif; ?>
  
  <div id="resourceAlerts" class="mb-3"></div>

  <div class="row g-4">

    
    <div class="col-md-4">
      <div class="module-card p-3">
        <h6>المعالج (CPU Load)</h6>
        <h3 id="cpu-percent"><?php echo e($cpu['percent']); ?>%</h3>
        <div class="progress" style="height:8px;">
          <div class="progress-bar" id="cpu-bar" style="width: <?php echo e($cpu['percent']); ?>%"></div>
        </div>
        <small class="text-muted">
          Load: <span id="cpu-load1"><?php echo e($cpu['load1']); ?></span> /
          <span id="cpu-load5"><?php echo e($cpu['load5']); ?></span> /
          <span id="cpu-load15"><?php echo e($cpu['load15']); ?></span>
          — <?php echo e($cpu['cores']); ?> core(s)
        </small>
      </div>
    </div>

    
    <div class="col-md-4">
      <div class="module-card p-3">
        <h6>الذاكرة (RAM)</h6>
        <h3 id="mem-percent"><?php echo e($memory['percent']); ?>%</h3>
        <div class="progress" style="height:8px;">
          <div class="progress-bar" id="mem-bar" style="width: <?php echo e($memory['percent']); ?>%"></div>
        </div>
        <small class="text-muted">
          <span id="mem-used"><?php echo e($memory['used_mb']); ?></span> MB /
          <span id="mem-total"><?php echo e($memory['total_mb']); ?></span> MB
        </small>
      </div>
    </div>

    
    <div class="col-md-4">
      <div class="module-card p-3">
        <h6>التخزين (Disk)</h6>
        <h3 id="disk-percent"><?php echo e($disk['percent']); ?>%</h3>
        <div class="progress" style="height:8px;">
          <div class="progress-bar" id="disk-bar" style="width: <?php echo e($disk['percent']); ?>%"></div>
        </div>
        <small class="text-muted">
          <span id="disk-used"><?php echo e($disk['used_gb']); ?></span> GB /
          <span id="disk-total"><?php echo e($disk['total_gb']); ?></span> GB
        </small>
      </div>
    </div>

  </div>

  <div class="row g-4 mt-1">

    
    <div class="col-md-3">
      <div class="module-card p-3">
        <h6>قاعدة البيانات</h6>
        <p class="mb-1">اتصالات: <span id="db-connections"><?php echo e($db['connections']); ?></span> / <span
            id="db-max"><?php echo e($db['max_connections']); ?></span></p>
        <p class="mb-1">استعلامات بطيئة: <span id="db-slow"><?php echo e($db['slow_queries']); ?></span></p>
        <p class="mb-0">حجم القاعدة: <span id="db-size"><?php echo e($db['size_mb']); ?></span> MB</p>
      </div>
    </div>

    
    <div class="col-md-3">
      <div class="module-card p-3">
        <h6>سجل الأخطاء (laravel.log)</h6>
        <p class="mb-1">الحجم: <span id="log-size"><?php echo e($logs['size_mb']); ?></span> MB</p>
        <p class="mb-0">أخطاء (آخر 1000 سطر): <span id="log-errors"><?php echo e($logs['error_count']); ?></span></p>
      </div>
    </div>

    
    <div class="col-md-3">
      <div class="module-card p-3">
        <h6>قائمة المهام (Queue)</h6>
        <p class="mb-1">قيد الانتظار: <span id="queue-pending"><?php echo e($queue['pending']); ?></span></p>
        <p class="mb-0">فاشلة: <span id="queue-failed"><?php echo e($queue['failed']); ?></span></p>
      </div>
    </div>

    
    <div class="col-md-3">
      <div class="module-card p-3">
        <h6>الجلسات والـ PHP</h6>
        <p class="mb-1">متصلون الآن: <span id="sessions-online"><?php echo e($sessions['online_users']); ?></span></p>
        <p class="mb-0">PHP Memory: <span id="php-memory"><?php echo e($php['memory_usage_mb']); ?></span> MB /
          <?php echo e($php['memory_limit']); ?>

        </p>
      </div>
    </div>






    <form method="POST" action="<?php echo e(route('system.resources.clearLog')); ?>"
      onsubmit="return confirm('سيتم حذف كل محتوى ملف السجل الحالي، هل أنت متأكد؟')" class="mt-2">
      <?php echo csrf_field(); ?>
      <button class="btn btn-sm btn-outline-danger w-100">
        <i class="bi bi-trash"></i> تفريغ السجل الآن
      </button>
    </form>




  </div>

  <script>
    function updateBar(id, value) {
      const bar = document.getElementById(id);
      if (!bar) return;
      bar.style.width = value + '%';
      bar.classList.remove('bg-success', 'bg-warning', 'bg-danger');
      if (value >= 90) bar.classList.add('bg-danger');
      else if (value >= 70) bar.classList.add('bg-warning');
      else bar.classList.add('bg-success');
    }

    function refreshResources() {
      fetch("<?php echo e(route('system.resources.data')); ?>")
        .then(r => r.json())
        .then(data => {
          document.getElementById('cpu-percent').textContent = data.cpu.percent + '%';
          document.getElementById('cpu-load1').textContent = data.cpu.load1;
          document.getElementById('cpu-load5').textContent = data.cpu.load5;
          document.getElementById('cpu-load15').textContent = data.cpu.load15;
          updateBar('cpu-bar', data.cpu.percent);

          document.getElementById('mem-percent').textContent = data.memory.percent + '%';
          document.getElementById('mem-used').textContent = data.memory.used_mb;
          document.getElementById('mem-total').textContent = data.memory.total_mb;
          updateBar('mem-bar', data.memory.percent);

          document.getElementById('disk-percent').textContent = data.disk.percent + '%';
          document.getElementById('disk-used').textContent = data.disk.used_gb;
          document.getElementById('disk-total').textContent = data.disk.total_gb;
          updateBar('disk-bar', data.disk.percent);

          document.getElementById('db-connections').textContent = data.db.connections;
          document.getElementById('db-max').textContent = data.db.max_connections;
          document.getElementById('db-slow').textContent = data.db.slow_queries;
          document.getElementById('db-size').textContent = data.db.size_mb;

          document.getElementById('log-size').textContent = data.logs.size_mb;
          document.getElementById('log-errors').textContent = data.logs.error_count;

          document.getElementById('queue-pending').textContent = data.queue.pending;
          document.getElementById('queue-failed').textContent = data.queue.failed;

          document.getElementById('sessions-online').textContent = data.sessions.online_users;
          document.getElementById('php-memory').textContent = data.php.memory_usage_mb;

          // تنبيهات لحظية
          let alerts = [];
          if (data.cpu.percent >= 90) alerts.push('⚠️ استهلاك المعالج مرتفع جداً (' + data.cpu.percent + '%)');
          if (data.memory.percent >= 90) alerts.push('⚠️ استهلاك الذاكرة مرتفع جداً (' + data.memory.percent + '%)');
          if (data.disk.percent >= 90) alerts.push('⚠️ مساحة التخزين شارفت على الامتلاء (' + data.disk.percent + '%)');
          if (data.queue.failed > 0) alerts.push('⚠️ يوجد ' + data.queue.failed + ' مهمة فاشلة في قائمة المهام');
          if (data.logs.size_mb > 50) alerts.push('⚠️ ملف laravel.log كبير جداً (' + data.logs.size_mb + ' MB) — يستحسن تفريغه');

          document.getElementById('resourceAlerts').innerHTML =
            alerts.map(a => `<div class="alert alert-danger py-2 mb-2">${a}</div>`).join('');
        })
        .catch(() => { });
    }

    document.addEventListener('DOMContentLoaded', function () {
      refreshResources();
      setInterval(refreshResources, 10000);
    });
  </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\customers\namaa\laravel11-auth\resources\views/system/resources.blade.php ENDPATH**/ ?>