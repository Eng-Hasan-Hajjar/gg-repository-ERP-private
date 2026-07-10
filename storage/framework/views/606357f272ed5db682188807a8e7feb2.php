<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>الصفحة غير موجودة — Namaa ERP</title>
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
      min-height: 100vh; display: flex; align-items: center; justify-content: center;
      background: radial-gradient(800px 500px at 20% 20%, rgba(99,102,241,.08), transparent 60%),
                  radial-gradient(800px 500px at 80% 80%, rgba(14,165,233,.08), transparent 60%),
                  linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
      margin: 0;
    }
    .error-card {
      background: rgba(255,255,255,.92); border: 1px solid rgba(226,232,240,.95);
      border-radius: 24px; padding: 52px 44px; max-width: 500px; width: 90%;
      text-align: center; box-shadow: 0 20px 60px rgba(2,6,23,.10);
    }
    .error-icon-wrap {
      width: 100px; height: 100px;
      background: linear-gradient(135deg, rgba(99,102,241,.12), rgba(99,102,241,.06));
      border: 2px solid rgba(99,102,241,.15); border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 28px; font-size: 44px; color: #6366f1;
    }
    .error-code {
      font-size: 72px; font-weight: 900; line-height: 1; margin-bottom: 8px;
      background: linear-gradient(135deg, #6366f1, #0ea5e9);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    h2 { font-weight: 900; color: #0b1220; font-size: 1.4rem; margin-bottom: 12px; }
    p  { color: #64748b; line-height: 1.8; font-size: 15px; margin-bottom: 32px; }
    .btn-primary-custom {
      background: linear-gradient(90deg, #0ea5e9, #10b981); border: 0;
      border-radius: 14px; padding: 12px 32px; color: #fff; font-weight: 900;
      font-size: 15px; text-decoration: none; display: inline-block;
      box-shadow: 0 8px 20px rgba(14,165,233,.25); transition: .2s;
    }
    .btn-primary-custom:hover { filter: brightness(.95); color: #fff; }
    .btn-secondary-custom {
      background: transparent; border: 1px solid #e2e8f0; border-radius: 14px;
      padding: 10px 24px; color: #64748b; font-weight: 700; font-size: 14px;
      text-decoration: none; display: inline-block; transition: .2s;
    }
    .btn-secondary-custom:hover { border-color: #0ea5e9; color: #0ea5e9; }
    .divider { border: none; border-top: 1px solid rgba(226,232,240,.9); margin: 28px 0; }
    .help-text { font-size: 13px; color: #94a3b8; }
  </style>
</head>
<body>
  <div class="error-card">
    <div class="error-icon-wrap"><i class="bi bi-search"></i></div>
    <div class="error-code">404</div>
    <h2>الصفحة غير موجودة</h2>
    <p>الصفحة التي تبحث عنها غير موجودة أو ربما تم نقلها أو حذفها.</p>
    <div class="d-flex flex-column align-items-center gap-2">
      <a href="/dashboard" class="btn-primary-custom">
        <i class="bi bi-grid-fill"></i> العودة للوحة التحكم
      </a>
      <a href="javascript:history.back()" class="btn-secondary-custom">
        <i class="bi bi-arrow-right"></i> الرجوع للصفحة السابقة
      </a>
    </div>
    <hr class="divider">
    <div class="help-text"><i class="bi bi-info-circle"></i> إذا كنت تعتقد أن هذا خطأ، تواصل مع مدير النظام.</div>
  </div>
</body>
</html><?php /**PATH C:\Users\engya\Desktop\customers\namaa\laravel11-auth\resources\views/errors/404.blade.php ENDPATH**/ ?>