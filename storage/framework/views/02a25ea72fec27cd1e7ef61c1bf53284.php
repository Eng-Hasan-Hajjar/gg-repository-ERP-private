<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>انتهت صلاحية الجلسة — Namaa ERP</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    @font-face {
      font-family: 'Hacen Tunisia';
      src: url('/fonts/hacen-tunisia/Hacen-Tunisia-Bd.ttf') format('truetype');
      font-weight: normal; font-style: normal; font-display: swap;
    }
    * { font-family: 'Hacen Tunisia', sans-serif !important; }
    body {
      min-height: 100vh;
      display: flex; align-items: center; justify-content: center;
      background: linear-gradient(135deg, #f0f9ff 0%, #ecfdf5 100%);
      margin: 0;
    }
    .error-card {
      background: #fff;
      border-radius: 24px;
      padding: 48px 40px;
      max-width: 480px;
      width: 90%;
      text-align: center;
      box-shadow: 0 20px 60px rgba(2,6,23,.10);
      border: 1px solid rgba(226,232,240,.9);
    }
    .error-icon {
      width: 90px; height: 90px;
      background: linear-gradient(135deg, rgba(245,158,11,.15), rgba(251,191,36,.10));
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 24px;
      font-size: 40px;
    }
    h2 { font-weight: 900; color: #0b1220; margin-bottom: 12px; }
    p  { color: #64748b; line-height: 1.8; font-size: 15px; margin-bottom: 28px; }
    .btn-primary-custom {
      background: linear-gradient(90deg, #0ea5e9, #10b981);
      border: 0; border-radius: 14px; padding: 12px 32px;
      color: #fff; font-weight: 900; font-size: 15px;
      text-decoration: none; display: inline-block;
      box-shadow: 0 8px 20px rgba(14,165,233,.25);
      transition: .2s;
    }
    .btn-primary-custom:hover { filter: brightness(.95); color: #fff; }
    .btn-secondary-custom {
      background: transparent;
      border: 1px solid #e2e8f0;
      border-radius: 14px; padding: 10px 24px;
      color: #64748b; font-weight: 700; font-size: 14px;
      text-decoration: none; display: inline-block;
      margin-top: 10px; transition: .2s;
    }
    .btn-secondary-custom:hover { border-color: #0ea5e9; color: #0ea5e9; }
    .counter { font-size: 13px; color: #94a3b8; margin-top: 16px; }
  </style>
</head>
<body>
  <div class="error-card">
    <div class="error-icon">⏰</div>
    <h2>انتهت صلاحية الجلسة</h2>
    <p>
      مضى وقت طويل على آخر نشاط لك في النظام.<br>
      يرجى الضغط على "تحديث الصفحة" للمتابعة من حيث توقفت.
    </p>
    <div class="d-flex flex-column align-items-center gap-2">
      <a href="javascript:history.back()" class="btn-primary-custom">
        <i class="bi bi-arrow-clockwise"></i> تحديث الصفحة
      </a>
      <a href="/dashboard" class="btn-secondary-custom">
        <i class="bi bi-grid-fill"></i> لوحة التحكم
      </a>
    </div>
    <div class="counter" id="countdown">سيتم التحديث تلقائياً خلال <span id="sec">10</span> ثواني</div>
  </div>

  <script>
    // عد تنازلي وإعادة توجيه تلقائية
    let sec = 10;
    const el = document.getElementById('sec');
    const timer = setInterval(function() {
      sec--;
      el.textContent = sec;
      if (sec <= 0) {
        clearInterval(timer);
        history.back();
      }
    }, 1000);
  </script>
</body>
</html><?php /**PATH C:\Users\engya\Desktop\customers\namaa\laravel11-auth\resources\views/errors/419.blade.php ENDPATH**/ ?>