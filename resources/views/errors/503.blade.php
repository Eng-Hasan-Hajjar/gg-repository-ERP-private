<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>النظام تحت الصيانة — Namaa ERP</title>
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
      background: radial-gradient(800px 500px at 50% 0%, rgba(99,102,241,.10), transparent 60%),
                  linear-gradient(180deg, #f8faff 0%, #ffffff 100%);
      margin: 0;
    }
    .error-card {
      background: rgba(255,255,255,.95); border: 1px solid rgba(226,232,240,.95);
      border-radius: 24px; padding: 52px 44px; max-width: 520px; width: 90%;
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
      background: linear-gradient(135deg, #6366f1, #8b5cf6);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    h2 { font-weight: 900; color: #0b1220; font-size: 1.4rem; margin-bottom: 12px; }
    p  { color: #64748b; line-height: 1.8; font-size: 15px; margin-bottom: 32px; }
    .btn-secondary-custom {
      background: transparent; border: 1px solid #e2e8f0; border-radius: 14px;
      padding: 12px 32px; color: #64748b; font-weight: 700; font-size: 15px;
      text-decoration: none; display: inline-block; transition: .2s;
    }
    .btn-secondary-custom:hover { border-color: #6366f1; color: #6366f1; }
    .divider { border: none; border-top: 1px solid rgba(226,232,240,.9); margin: 28px 0; }
    .help-text { font-size: 13px; color: #94a3b8; }

    /* نبض الأيقونة */
    .pulse { animation: pulse 2s infinite; }
    @keyframes pulse {
      0%, 100% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.08); opacity: .8; }
    }
  </style>
</head>
<body>
  <div class="error-card">
    <div class="error-icon-wrap pulse"><i class="bi bi-tools"></i></div>
    <div class="error-code">503</div>
    <h2>النظام تحت الصيانة</h2>
    <p>
      نقوم حالياً بتحديث النظام وتحسينه.<br>
      سنعود قريباً — شكراً لصبرك.
    </p>
    <div class="d-flex flex-column align-items-center gap-2">
      <a href="javascript:location.reload()" class="btn-secondary-custom">
        <i class="bi bi-arrow-clockwise"></i> إعادة المحاولة
      </a>
    </div>
    <hr class="divider">
    <div class="help-text">
      <i class="bi bi-clock"></i>
      إذا استمرت المشكلة أكثر من ساعة، تواصل مع مدير النظام.
    </div>
  </div>
</body>
</html>