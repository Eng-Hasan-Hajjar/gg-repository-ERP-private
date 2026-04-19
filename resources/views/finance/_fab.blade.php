{{-- ========================================================
     Finance FAB — الزر العائم الديناميكي لقسم المالية
     يُضمّن في نهاية layouts/app.blade.php قبل </body>
     ======================================================== --}}

@if(isset($activeModule) && $activeModule === 'finance')
<style>
.namaa-fab-wrapper {
  position: fixed;
  bottom: 28px;
  right: 28px;
  z-index: 9999;
  direction: rtl;
}
.namaa-fab-ripple {
  position: absolute;
  inset: 0;
  border-radius: 50%;
  border: 2px solid rgba(17,153,142,0.45);
  animation: namaaRipple 2.2s ease-out infinite;
  pointer-events: none;
}
.namaa-fab-ripple:nth-child(2) { animation-delay: 0.7s; }
.namaa-fab-ripple:nth-child(3) { animation-delay: 1.4s; }
@keyframes namaaRipple {
  0%   { transform: scale(1);   opacity: 0.9; }
  100% { transform: scale(2.4); opacity: 0;   }
}
.namaa-fab-wrapper.fab-open .namaa-fab-ripple { display: none; }

.namaa-fab-main {
  width: 58px; height: 58px;
  border-radius: 50%;
  background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
  border: none; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 4px 22px rgba(17,153,142,0.50);
  position: relative; z-index: 2;
  transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1),
              box-shadow 0.3s ease, background 0.4s ease;
  outline: none;
}
.namaa-fab-main:hover { box-shadow: 0 6px 30px rgba(17,153,142,0.60); transform: scale(1.06); }
.namaa-fab-main svg { width: 26px; height: 26px; fill: #fff; }
.namaa-fab-wrapper.fab-open .namaa-fab-main {
  transform: rotate(45deg) scale(1.05);
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  box-shadow: 0 6px 28px rgba(102,126,234,0.50);
}

/* ── اللوحة ── */
.namaa-fab-panel {
  position: fixed;
  bottom: 100px; right: 20px;
  width: 300px; max-height: 72vh;
  background: #fff;
  border-radius: 18px;
  box-shadow: 0 8px 40px rgba(0,0,0,0.18);
  overflow: hidden;
  display: none;
  flex-direction: column;
  opacity: 0;
  transform: translateY(20px) scale(0.95);
  transition: opacity 0.3s ease, transform 0.35s cubic-bezier(0.34,1.56,0.64,1);
  pointer-events: none;
  z-index: 9998;
}
.namaa-fab-panel.panel-visible {
  display: flex;
}
.namaa-fab-panel.panel-open {
  opacity: 1;
  transform: translateY(0) scale(1);
  pointer-events: all;
}

.namaa-fab-header {
  padding: 14px 18px 12px;
  background: linear-gradient(135deg, #11998e, #38ef7d);
  color: #fff;
  font-size: 13px; font-weight: 600;
  display: flex; align-items: center; gap: 8px;
  flex-shrink: 0;
}
.namaa-fab-header svg { width: 18px; height: 18px; fill: rgba(255,255,255,0.85); }

.namaa-fab-section-title {
  padding: 8px 16px 6px;
  font-size: 10.5px; font-weight: 700;
  color: #888; letter-spacing: 0.08em;
  background: #f8f9fa;
  border-bottom: 1px solid #f0f0f0;
  flex-shrink: 0;
}

.namaa-fab-cashboxes {
  overflow-y: auto; flex: 1; padding: 4px 0;
}
.namaa-fab-cashboxes::-webkit-scrollbar { width: 4px; }
.namaa-fab-cashboxes::-webkit-scrollbar-thumb { background: #ddd; border-radius: 4px; }

.namaa-fab-cashbox {
  display: flex; align-items: center; gap: 12px;
  padding: 10px 16px;
  text-decoration: none; color: #1a1a2e;
  transition: background 0.18s;
  border-bottom: 1px solid #f5f5f5;
}
.namaa-fab-cashbox:hover { background: #f0fdf8; color: #11998e; text-decoration: none; }
.namaa-fab-cashbox:last-child { border-bottom: none; }

.namaa-fab-currency {
  width: 38px; height: 38px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 9.5px; font-weight: 800; flex-shrink: 0; letter-spacing: 0.02em;
}
.cur-usd { background: #e8f5e9; color: #2e7d32; }
.cur-eur { background: #e3f2fd; color: #1565c0; }
.cur-try { background: #fce4ec; color: #c62828; }
.cur-gbp { background: #f3e5f5; color: #6a1b9a; }
.cur-sar { background: #fff8e1; color: #e65100; }
.cur-default { background: #f0f0f0; color: #555; }

.namaa-fab-cashbox-info { flex: 1; min-width: 0; }
.namaa-fab-cashbox-name {
  font-size: 13px; font-weight: 600;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 2px;
}
.namaa-fab-cashbox-sub {
  font-size: 11px; color: #999;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.namaa-fab-balance { font-size: 11px; font-weight: 700; color: #11998e; flex-shrink: 0; }
.namaa-fab-balance.neg { color: #e53935; }

.namaa-fab-state { padding: 22px 16px; text-align: center; font-size: 12px; color: #aaa; }
.namaa-fab-spinner {
  width: 22px; height: 22px;
  border: 2px solid #e0e0e0; border-top-color: #11998e;
  border-radius: 50%; animation: fabSpin 0.7s linear infinite;
  margin: 0 auto 10px;
}
@keyframes fabSpin { to { transform: rotate(360deg); } }

.namaa-fab-quick {
  display: flex; border-top: 1px solid #f0f0f0; flex-shrink: 0;
}
.namaa-fab-quick-btn {
  flex: 1; padding: 10px 4px; text-align: center;
  font-size: 10.5px; color: #555; text-decoration: none;
  transition: background 0.18s, color 0.18s;
  border-left: 1px solid #f0f0f0;
  display: flex; flex-direction: column; align-items: center; gap: 3px;
}
.namaa-fab-quick-btn:last-child { border-left: none; }
.namaa-fab-quick-btn:hover { background: #f5fffe; color: #11998e; text-decoration: none; }
.namaa-fab-quick-btn svg { width: 15px; height: 15px; fill: currentColor; opacity: 0.75; }

.namaa-fab-backdrop {
  display: none; position: fixed; inset: 0; z-index: 9997;
}
.namaa-fab-backdrop.show { display: block; }
</style>

<div class="namaa-fab-backdrop" id="namaaFabBackdrop" onclick="namaaFabClose()"></div>

{{-- ══ اللوحة ══ --}}
<div class="namaa-fab-panel" id="namaaFabPanel" role="dialog" aria-label="الصناديق المالية">

  <div class="namaa-fab-header">
    <svg viewBox="0 0 24 24"><path d="M21 7.28V5c0-1.1-.9-2-2-2H5c-1.11 0-2 .89-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-2.28A2 2 0 0 0 22 15V9a2 2 0 0 0-1-1.72zM20 9v6h-7V9h7zM5 19V5h14v2h-6c-1.1 0-2 .9-2 2v6c0 1.1.9 2 2 2h6v2H5z"/></svg>
    الصناديق المالية
  </div>

  <div class="namaa-fab-section-title">الصناديق النشطة</div>

  <div class="namaa-fab-cashboxes" id="namaaFabList">
    <div class="namaa-fab-state">
      <div class="namaa-fab-spinner"></div>
      جارٍ التحميل...
    </div>
  </div>

  <div class="namaa-fab-quick">
    <a href="{{ route('finance.dashboard') }}" class="namaa-fab-quick-btn">
      <svg viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
      اللوحة
    </a>
    <a href="{{ route('finance.reports.daily') }}" class="namaa-fab-quick-btn">
      <svg viewBox="0 0 24 24"><path d="M19 3h-1V1h-2v2H8V1H6v2H5C3.89 4 3 4.9 3 6v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/></svg>
      اليومي
    </a>
    <a href="{{ route('finance.reports.profit') }}" class="namaa-fab-quick-btn">
      <svg viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
      الأرباح
    </a>
    @if(auth()->user()?->hasPermission('create_cashboxes'))
    <a href="{{ route('cashboxes.create') }}" class="namaa-fab-quick-btn">
      <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.89-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/></svg>
      إضافة
    </a>
    @endif
  </div>

</div>

{{-- ══ الزر الرئيسي ══ --}}
<div class="namaa-fab-wrapper" id="namaaFabWrapper">
  <span class="namaa-fab-ripple"></span>
  <span class="namaa-fab-ripple"></span>
  <span class="namaa-fab-ripple"></span>

  <button class="namaa-fab-main" onclick="namaaFabToggle()"
          aria-label="قائمة الصناديق المالية" title="الصناديق المالية">
    <svg viewBox="0 0 24 24">
      <path d="M21 7.28V5c0-1.1-.9-2-2-2H5c-1.11 0-2 .89-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-2.28A2 2 0 0 0 22 15V9a2 2 0 0 0-1-1.72zM20 9v6h-7V9h7zM5 19V5h14v2h-6c-1.1 0-2 .9-2 2v6c0 1.1.9 2 2 2h6v2H5z"/>
    </svg>
  </button>
</div>

<script>
(function () {
  var _open   = false;
  var _loaded = false;

  var CUR_CLASS = { USD:'cur-usd', EUR:'cur-eur', TRY:'cur-try', GBP:'cur-gbp', SAR:'cur-sar' };

  function buildItem(c) {
    var cc  = CUR_CLASS[c.currency] || 'cur-default';
    var neg = parseFloat(String(c.balance).replace(/,/g,'')) < 0;
    var a   = document.createElement('a');
    a.href  = c.url;
    a.className = 'namaa-fab-cashbox';
    a.innerHTML =
      '<div class="namaa-fab-currency ' + cc + '">' + c.currency + '</div>' +
      '<div class="namaa-fab-cashbox-info">' +
        '<div class="namaa-fab-cashbox-name">' + c.name + '</div>' +
        '<div class="namaa-fab-cashbox-sub">' + c.branch + '</div>' +
      '</div>' +
      '<div class="namaa-fab-balance' + (neg ? ' neg' : '') + '">' + c.balance + '</div>';
    return a;
  }

  function loadCashboxes() {
    if (_loaded) return;
    fetch('{{ route("finance.fab.cashboxes") }}', {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r){ return r.json(); })
    .then(function(data) {
      _loaded = true;
      var list = document.getElementById('namaaFabList');
      if (!data || !data.length) {
        list.innerHTML = '<div class="namaa-fab-state">لا توجد صناديق نشطة</div>';
        return;
      }
      list.innerHTML = '';
      data.forEach(function(c){ list.appendChild(buildItem(c)); });
    })
    .catch(function() {
      document.getElementById('namaaFabList').innerHTML =
        '<div class="namaa-fab-state">⚠️ تعذّر التحميل</div>';
    });
  }

  window.namaaFabToggle = function () { _open ? namaaFabClose() : namaaFabOpen(); };

  window.namaaFabOpen = function () {
    _open = true;
    var panel = document.getElementById('namaaFabPanel');
    panel.style.display = 'flex';
    // إطار صغير لتفعيل الـ CSS transition
    requestAnimationFrame(function(){
      requestAnimationFrame(function(){
        panel.classList.add('panel-open');
      });
    });
    document.getElementById('namaaFabWrapper').classList.add('fab-open');
    document.getElementById('namaaFabBackdrop').classList.add('show');
    loadCashboxes();
  };

  window.namaaFabClose = function () {
    _open = false;
    var panel = document.getElementById('namaaFabPanel');
    panel.classList.remove('panel-open');
    document.getElementById('namaaFabWrapper').classList.remove('fab-open');
    document.getElementById('namaaFabBackdrop').classList.remove('show');
    setTimeout(function(){ if (!_open) panel.style.display = 'none'; }, 320);
  };

  document.addEventListener('keydown', function(e){
    if (e.key === 'Escape' && _open) namaaFabClose();
  });
})();
</script>
@endif