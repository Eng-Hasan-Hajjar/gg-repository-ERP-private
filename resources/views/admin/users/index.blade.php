@extends('layouts.app')
@php($activeModule = 'users')

@section('title', 'إدارة المستخدمين')

@section('content')
  <style>
    /* ───── Dashboard Cards ───── */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
      margin-bottom: 20px;
    }

    .stat-card {
      background: #fff;
      border-radius: 14px;
      padding: 20px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
      display: flex;
      align-items: center;
      gap: 16px;
      transition: transform .2s, box-shadow .2s;
    }

    .stat-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, .1);
    }

    .stat-icon {
      width: 52px;
      height: 52px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      flex-shrink: 0;
    }

    .stat-icon.total {
      background: #e0e7ff;
      color: #4f46e5;
    }

    .stat-icon.online {
      background: #d1fae5;
      color: #059669;
    }

    .stat-icon.offline {
      background: #fee2e2;
      color: #dc2626;
    }

    .stat-icon.hours {
      background: #fef3c7;
      color: #d97706;
    }

    .stat-number {
      font-size: 28px;
      font-weight: 800;
      line-height: 1;
      color: #1e293b;
    }

    .stat-label {
      font-size: 13px;
      color: #64748b;
      margin-top: 2px;
    }

    /* ───── Table ───── */
    .roles-cell {
      min-width: 120px;
    }

    .role-line {
      display: block;
      margin-bottom: 4px;
    }

    .worked-cell {
      line-height: 1.3;
      white-space: pre-line;
    }

    .worked-hours {
      font-weight: 700;
    }

    .worked-minutes {
      font-size: 12px;
      color: #64748b;
    }

    /* ───── User Detail Modal ───── */
    .user-detail-modal .modal-content {
      border: none;
      border-radius: 16px;
      overflow: hidden;
    }

    .user-detail-header {
      background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
      color: #fff;
      padding: 32px 28px 28px;
      text-align: center;
      position: relative;
    }

    .user-detail-avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: rgba(255, 255, 255, .2);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 32px;
      font-weight: 700;
      color: #fff;
      margin: 0 auto 12px;
      border: 3px solid rgba(255, 255, 255, .4);
    }

    .user-detail-name {
      font-size: 22px;
      font-weight: 700;
      margin-bottom: 4px;
    }

    .user-detail-email {
      font-size: 14px;
      opacity: .85;
    }

    .user-detail-status {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 4px 14px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 600;
      margin-top: 10px;
    }

    .user-detail-status.is-online {
      background: rgba(16, 185, 129, .2);
      color: #d1fae5;
    }

    .user-detail-status.is-offline {
      background: rgba(239, 68, 68, .2);
      color: #fecaca;
    }

    .detail-section {
      padding: 20px 28px;
    }

    .detail-section+.detail-section {
      border-top: 1px solid #f1f5f9;
    }

    .detail-section-title {
      font-size: 13px;
      font-weight: 700;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: .5px;
      margin-bottom: 12px;
    }

    .detail-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 8px 0;
    }

    .detail-row+.detail-row {
      border-top: 1px solid #f8fafc;
    }

    .detail-key {
      font-size: 14px;
      color: #64748b;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .detail-key i {
      font-size: 16px;
      width: 20px;
      text-align: center;
    }

    .detail-value {
      font-size: 14px;
      font-weight: 600;
      color: #1e293b;
      text-align: left;
      direction: ltr;
    }

    .detail-roles-list {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
    }

    .detail-role-badge {
      padding: 4px 12px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 600;
      background: #e0e7ff;
      color: #4338ca;
    }

    .detail-location-box {
      background: #f8fafc;
      border-radius: 10px;
      padding: 12px 16px;
      margin-top: 6px;
    }

    .detail-activity-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      background: #d1fae5;
      color: #065f46;
    }

    /* ───── Filter Card ───── */
    .filter-card {
      border-radius: 14px;
      border: none;
      box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
    }

    /* ───── Better action buttons ───── */
    .action-btn-group {
      display: flex;
      gap: 4px;
      justify-content: flex-end;
      flex-wrap: wrap;
    }

    .btn-detail {
      background: #f0f9ff;
      color: #0284c7;
      border: 1px solid #bae6fd;
      font-weight: 600;
    }

    .btn-detail:hover {
      background: #e0f2fe;
      color: #0369a1;
    }
  </style>

  {{-- ═══════════════ Header ═══════════════ --}}
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
    <div>
      <h4 class="mb-0 fw-bold">إدارة المستخدمين</h4>
      <div class="text-muted small">لوحة تحكم شاملة + بحث وتصفية</div>
    </div>
    <div class="d-flex gap-2 flex-wrap">
      <?php if (auth()->user()?->hasPermission('create_users')): ?>
      <a class="btn btn-primary rounded-pill px-4 fw-bold" href="{{ route('admin.users.create') }}">
        <i class="bi bi-person-plus"></i> مستخدم جديد
      </a>
      <?php endif; ?>
      <a href="{{ route('reports.monthly') }}" class="btn btn-success rounded-pill px-4 fw-bold">
        <i class="bi bi-calendar3"></i> تقرير الحضور الشهري
      </a>



      <form method="POST" action="{{ route('admin.users.logoutAll') }}">
        @csrf
        <button class="btn btn-danger rounded-pill px-4 fw-bold"
          onclick="return confirm('هل تريد إخراج جميع المستخدمين؟')">

          <i class="bi bi-box-arrow-right"></i>
          إخراج جميع المستخدمين

        </button>
      </form>



    </div>
  </div>

  {{-- ═══════════════ Dashboard Stats ═══════════════ --}}


  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon total"><i class="bi bi-people-fill"></i></div>
      <div>
        <div class="stat-number">{{ $totalUsers }}</div>
        <div class="stat-label">إجمالي المستخدمين</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon online"><i class="bi bi-wifi"></i></div>
      <div>
        <div class="stat-number">{{ $onlineCount }}</div>
        <div class="stat-label">متصلون الآن</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon offline"><i class="bi bi-wifi-off"></i></div>
      <div>
        <div class="stat-number">{{ $offlineCount }}</div>
        <div class="stat-label">غير متصلين</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon hours"><i class="bi bi-clock-history"></i></div>
      <div>
        <div class="stat-number">{{ $roles->count() }}</div>
        <div class="stat-label">عدد الأدوار</div>
      </div>
    </div>
  </div>

  {{-- ═══════════════ Filter Form ═══════════════ --}}
  <div class="card filter-card card-body mb-3">
    <form method="GET">
      <div class="row g-2 align-items-end">
        <div class="col-12 col-md-3">
          <label class="form-label small fw-bold text-muted mb-1">
            <i class="bi bi-search"></i> بحث
          </label>
          <input name="search" value="{{ request('search') }}" class="form-control" placeholder="بحث بالاسم أو البريد">
        </div>
        <div class="col-6 col-md-2">
          <label class="form-label small fw-bold text-muted mb-1">
            <i class="bi bi-shield"></i> الدور
          </label>
          <select name="role_id" class="form-select">
            <option value="">كل الأدوار</option>
            <?php foreach ($roles as $r): ?>
            <option value="<?= $r->id ?>" <?= request('role_id') == $r->id ? 'selected' : '' ?>>
              <?= e($r->label) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-6 col-md-2">
          <label class="form-label small fw-bold text-muted mb-1">
            <i class="bi bi-circle-fill text-success" style="font-size:8px"></i> الحالة
          </label>
          <select name="online" class="form-select">
            <option value="">كل المستخدمين</option>
            <option value="1" <?= request('online') == '1' ? 'selected' : '' ?>>
              🟢 متصلون الآن ({{ $onlineCount }})
            </option>
          </select>
        </div>
        <div class="col-6 col-md-2">
          <label class="form-label small fw-bold text-muted mb-1">
            <i class="bi bi-sort-down"></i> ترتيب
          </label>
          <select name="sort" class="form-select">
            <option value="">الأحدث</option>
            <option value="name" <?= request('sort') == 'name' ? 'selected' : '' ?>>الاسم</option>
            <option value="oldest" <?= request('sort') == 'oldest' ? 'selected' : '' ?>>الأقدم</option>
          </select>
        </div>
        <div class="col-6 col-md-1 d-grid">
          <label class="form-label small mb-1">&nbsp;</label>
          <button class="btn btn-namaa fw-bold">
            <i class="bi bi-funnel"></i> تطبيق
          </button>
        </div>
        <div class="col-12 col-md-2 d-grid">
          <label class="form-label small mb-1">&nbsp;</label>
          <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary fw-bold">
            <i class="bi bi-x-circle"></i> تنظيف
          </a>
        </div>
      </div>
    </form>
  </div>

  {{-- ═══════════════ Users Table ═══════════════ --}}
  <div class="card border-0 shadow-sm" style="border-radius:14px;overflow:hidden">
    <div class="table-responsive">
      <table class="table table-hover mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th class="hide-mobile">#</th>
            <th>الاسم</th>
            <th class="hide-mobile">ماذا يعمل الآن</th>
            <th class="hide-mobile">ساعات اليوم</th>
           
            <th class="hide-mobile">الأدوار</th>
            <th class="text-end">إجراءات</th>
          </tr>
        </thead>
        <tbody>

          <?php if ($users->count()): ?>
          <?php  foreach ($users as $u): ?>
          <?php
      $activeSession = $u->sessions
        ->whereNull('logout_at')
        ->sortByDesc('last_activity')
        ->first();

      $locationText = '';
      $fullAddress = '';
      $sessionCity = '';
      $sessionCountry = '';
      $sessionIp = '';
      $sessionAgent = '';
      $sessionLat = '';
      $sessionLng = '';
      $loginTime = '';

      if ($activeSession) {
        $fullAddress = $activeSession->address_detail ?? '';
        $locationText = $fullAddress ?: collect([
          $activeSession->city ?? null,
          $activeSession->country ?? null,
        ])->filter()->implode('، ');
        $sessionCity = $activeSession->city ?? '';
        $sessionCountry = $activeSession->country ?? '';
        $sessionIp = $activeSession->ip ?? '';
        $sessionAgent = $activeSession->user_agent ?? '';
        $sessionLat = $activeSession->latitude ?? '';
        $sessionLng = $activeSession->longitude ?? '';
        $loginTime = $activeSession->login_at ? $activeSession->login_at->format('H:i') : '';
      }

      $isOnline = $u->isOnline();
              ?>
          <tr>
            <td class="hide-mobile"><?= $u->id ?></td>

            <td class="fw-semibold">
              <div class="user-cell">
                <span class="status-dot <?= $isOnline ? 'online' : 'offline' ?>"></span>
                <div>
                  <span class="user-name"><?= e($u->name) ?></span>
                  <?php    if ($isOnline): ?>
                  <div class="text-success small">
                    <i class="bi bi-circle-fill" style="font-size:7px"></i> متصل الآن
                  </div>
                  <?php      if ($locationText): ?>
                  <div class="text-muted small">
                    <i class="bi bi-geo-alt-fill text-danger"></i>
                    <?= e($locationText) ?>
                  </div>
                  <?php      endif; ?>
                  <?php    else: ?>
                  <div class="text-danger small">غير متصل</div>
                  <div class="text-muted small">
                    آخر ظهور: <?= e($u->last_seen ?? '—') ?>
                  </div>
                  <?php    endif; ?>
                </div>
              </div>
            </td>

            <td class="hide-mobile">
              <?php    if ($isOnline): ?>
              <span class="badge bg-success">
                <i class="bi bi-activity"></i>
                <?= e($u->current_activity ?? '—') ?>
              </span>
              <?php    else: ?>
              <span class="text-muted">—</span>
              <?php    endif; ?>
            </td>

            <td class="worked-cell roles-cell hide-mobile">
              <div class="worked-hours"><?= e($u->today_worked_hours) ?></div>
              <div class="worked-minutes"><?= e($u->today_worked_minutes) ?></div>
            </td>

          

            <td class="roles-cell hide-mobile">
              <?php    foreach ($u->roles as $role): ?>
              <div class="role-line">
                <span class="badge bg-secondary"><?= e($role->label) ?></span>
              </div>
              <?php    endforeach; ?>
            </td>

            <td class="text-end">
              <div class="action-btn-group">
                {{-- زر التفاصيل --}}
                <button class="btn btn-sm btn-detail" data-bs-toggle="modal" data-bs-target="#userModal"
                  data-user-id="<?= $u->id ?>" data-user-name="<?= e($u->name) ?>" data-user-email="<?= e($u->email) ?>"
                  data-user-online="<?= $isOnline ? '1' : '0' ?>" data-user-lastseen="<?= e($u->last_seen ?? '—') ?>"
                  data-user-activity="<?= e($u->current_activity ?? '—') ?>"
                  data-user-hours="<?= e($u->today_worked_hours) ?>"
                  data-user-minutes="<?= e($u->today_worked_minutes) ?>"
                  data-user-roles="<?= e($u->roles->pluck('label')->implode('،')) ?>"
                  data-user-location="<?= e($locationText) ?>" data-user-address="<?= e($fullAddress) ?>"
                  data-user-city="<?= e($sessionCity) ?>" data-user-country="<?= e($sessionCountry) ?>"
                  data-user-ip="<?= e($sessionIp) ?>" data-user-agent="<?= e($sessionAgent) ?>"
                  data-user-lat="<?= e($sessionLat) ?>" data-user-lng="<?= e($sessionLng) ?>"
                  data-user-login="<?= e($loginTime) ?>" data-user-created="<?= e($u->created_at?->format('Y-m-d')) ?>">
                  <i class="bi bi-eye"></i> تفاصيل
                </button>

                <?php    if (auth()->user()?->hasPermission('edit_users')): ?>
                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.users.edit', $u) }}">
                  <i class="bi bi-pencil"></i>
                </a>
                <?php    endif; ?>

                <?php    if (auth()->user()?->hasPermission('manage_roles')): ?>
                <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.roles.index', ['user' => $u->id]) }}">
                  <i class="bi bi-shield-lock"></i>
                </a>
                <?php    endif; ?>

                <?php    if (!$u->hasRole('super_admin')): ?>
                <button class="btn btn-sm btn-outline-danger" onclick="if(confirm('هل أنت متأكد؟')){
                          document.getElementById('del-<?= $u->id ?>').submit();
                        }">
                  <i class="bi bi-trash"></i>
                </button>




                <form method="POST" action="{{ route('admin.users.forceLogout', $u) }}" style="display:inline">

                  @csrf

                  <button class="btn btn-sm btn-outline-warning"
                    onclick="return confirm('إخراج هذا المستخدم من النظام؟')">

                    <i class="bi bi-box-arrow-right"></i>

                  </button>

                </form>


                
                <?php    endif; ?>
              </div>
            </td>
          </tr>
          <?php  endforeach; ?>

          <?php else: ?>
          <tr>
            <td colspan="7" class="text-center text-muted py-4">لا يوجد مستخدمون</td>
          </tr>
          <?php endif; ?>

        </tbody>
      </table>
    </div>
  </div>

  {{-- ═══════════════ Delete Forms ═══════════════ --}}
  <?php foreach ($users as $u): ?>
  <?php  if (!$u->hasRole('super_admin')): ?>
  <form id="del-<?= $u->id ?>" method="POST" action="{{ route('admin.users.destroy', $u) }}" style="display:none">
    @csrf
    @method('DELETE')
  </form>
  <?php  endif; ?>
  <?php endforeach; ?>

  <div class="mt-3">
    {{ $users->links() }}
  </div>

  {{-- ═══════════════ User Details Modal ═══════════════ --}}
  <div class="modal fade user-detail-modal" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">

        {{-- Header --}}
        <div class="user-detail-header">
          <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
            data-bs-dismiss="modal"></button>
          <div class="user-detail-avatar" id="modalAvatar"></div>
          <div class="user-detail-name" id="modalName"></div>
          <div class="user-detail-email" id="modalEmail"></div>
          <div id="modalStatusBadge"></div>
        </div>

        {{-- Body --}}
        <div class="modal-body p-0">

          {{-- قسم النشاط --}}
          <div class="detail-section" id="modalActivitySection">
            <div class="detail-section-title">
              <i class="bi bi-activity"></i> النشاط الحالي
            </div>
            <div class="detail-row">
              <span class="detail-key"><i class="bi bi-lightning-charge text-warning"></i> يعمل على</span>
              <span class="detail-activity-badge" id="modalActivity"></span>
            </div>
            <div class="detail-row">
              <span class="detail-key"><i class="bi bi-box-arrow-in-right text-success"></i> وقت الدخول</span>
              <span class="detail-value" id="modalLoginTime"></span>
            </div>
          </div>

          {{-- قسم ساعات العمل --}}
          <div class="detail-section">
            <div class="detail-section-title">
              <i class="bi bi-clock"></i> ساعات العمل اليوم
            </div>
            <div class="detail-row">
              <span class="detail-key"><i class="bi bi-hourglass-split text-primary"></i> المدة</span>
              <span class="detail-value" id="modalWorked"></span>
            </div>
          </div>

          {{-- قسم الموقع --}}
          <div class="detail-section" id="modalLocationSection">
            <div class="detail-section-title">
              <i class="bi bi-geo-alt"></i> الموقع الجغرافي
            </div>
            <div class="detail-location-box">
              <div class="detail-row" id="modalAddressRow">
                <span class="detail-key"><i class="bi bi-pin-map-fill text-danger"></i> العنوان الكامل</span>
                <span class="detail-value" id="modalAddress"></span>
              </div>
              <div class="detail-row" id="modalCityRow">
                <span class="detail-key"><i class="bi bi-building text-info"></i> المدينة</span>
                <span class="detail-value" id="modalCity"></span>
              </div>
              <div class="detail-row" id="modalCountryRow">
                <span class="detail-key"><i class="bi bi-flag text-success"></i> الدولة</span>
                <span class="detail-value" id="modalCountry"></span>
              </div>
              <div class="detail-row" id="modalCoordsRow">
                <span class="detail-key"><i class="bi bi-crosshair text-secondary"></i> الإحداثيات</span>
                <span class="detail-value" id="modalCoords"></span>
              </div>
            </div>
          </div>

          {{-- قسم الأدوار --}}
          <div class="detail-section">
            <div class="detail-section-title">
              <i class="bi bi-shield-check"></i> الأدوار والصلاحيات
            </div>
            <div class="detail-roles-list" id="modalRoles"></div>
          </div>

          {{-- قسم معلومات الاتصال --}}
          <div class="detail-section">
            <div class="detail-section-title">
              <i class="bi bi-info-circle"></i> معلومات تقنية
            </div>
            <div class="detail-row">
              <span class="detail-key"><i class="bi bi-hash"></i> رقم المستخدم</span>
              <span class="detail-value" id="modalUserId"></span>
            </div>
            <div class="detail-row">
              <span class="detail-key"><i class="bi bi-calendar-plus"></i> تاريخ الإنشاء</span>
              <span class="detail-value" id="modalCreated"></span>
            </div>
            <div class="detail-row" id="modalIpRow">
              <span class="detail-key"><i class="bi bi-hdd-network text-secondary"></i> عنوان IP</span>
              <span class="detail-value" id="modalIp"></span>
            </div>
            <div class="detail-row" id="modalAgentRow">
              <span class="detail-key"><i class="bi bi-laptop text-secondary"></i> المتصفح</span>
              <span class="detail-value small" id="modalAgent" style="max-width:350px;word-break:break-all;"></span>
            </div>
          </div>

        </div>

        {{-- Footer --}}
        <div class="modal-footer border-0 justify-content-between">
          <div class="d-flex gap-2" id="modalActions"></div>
          <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">إغلاق</button>
        </div>

      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const modal = document.getElementById('userModal');

      modal.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        const d = btn.dataset;

        const isOnline = d.userOnline === '1';

        // Avatar (أول حرفين)
        const initials = (d.userName || '').split(' ').map(w => w[0]).join('').substring(0, 2);
        document.getElementById('modalAvatar').textContent = initials;

        // الاسم والبريد
        document.getElementById('modalName').textContent = d.userName;
        document.getElementById('modalEmail').textContent = d.userEmail;

        // حالة الاتصال
        const statusEl = document.getElementById('modalStatusBadge');
        if (isOnline) {
          statusEl.innerHTML = '<span class="user-detail-status is-online"><i class="bi bi-circle-fill" style="font-size:8px"></i> متصل الآن</span>';
        } else {
          statusEl.innerHTML = '<span class="user-detail-status is-offline"><i class="bi bi-circle-fill" style="font-size:8px"></i> غير متصل — آخر ظهور: ' + d.userLastseen + '</span>';
        }

        // النشاط
        const actSection = document.getElementById('modalActivitySection');
        if (isOnline) {
          actSection.style.display = '';
          document.getElementById('modalActivity').textContent = d.userActivity;
          document.getElementById('modalLoginTime').textContent = d.userLogin || '—';
        } else {
          actSection.style.display = 'none';
        }

        // ساعات العمل
        document.getElementById('modalWorked').textContent = d.userHours + ' — ' + d.userMinutes;

        // الموقع
        const locSection = document.getElementById('modalLocationSection');
        if (d.userLocation) {
          locSection.style.display = '';

          // العنوان الكامل
          const addrRow = document.getElementById('modalAddressRow');
          if (d.userAddress) {
            addrRow.style.display = '';
            document.getElementById('modalAddress').textContent = d.userAddress;
          } else {
            addrRow.style.display = 'none';
          }

          // المدينة
          const cityRow = document.getElementById('modalCityRow');
          if (d.userCity) {
            cityRow.style.display = '';
            document.getElementById('modalCity').textContent = d.userCity;
          } else {
            cityRow.style.display = 'none';
          }

          // الدولة
          const countryRow = document.getElementById('modalCountryRow');
          if (d.userCountry) {
            countryRow.style.display = '';
            document.getElementById('modalCountry').textContent = d.userCountry;
          } else {
            countryRow.style.display = 'none';
          }

          // الإحداثيات
          const coordsRow = document.getElementById('modalCoordsRow');
          if (d.userLat && d.userLng) {
            coordsRow.style.display = '';
            document.getElementById('modalCoords').textContent = d.userLat + ', ' + d.userLng;
          } else {
            coordsRow.style.display = 'none';
          }
        } else {
          locSection.style.display = 'none';
        }

        // الأدوار
        const rolesEl = document.getElementById('modalRoles');
        rolesEl.innerHTML = '';
        if (d.userRoles) {
          d.userRoles.split('،').forEach(function (role) {
            role = role.trim();
            if (role) {
              rolesEl.innerHTML += '<span class="detail-role-badge">' + role + '</span>';
            }
          });
        }

        // معلومات تقنية
        document.getElementById('modalUserId').textContent = d.userId;
        document.getElementById('modalCreated').textContent = d.userCreated || '—';

        // IP
        const ipRow = document.getElementById('modalIpRow');
        if (d.userIp) {
          ipRow.style.display = '';
          document.getElementById('modalIp').textContent = d.userIp;
        } else {
          ipRow.style.display = 'none';
        }

        // المتصفح
        const agentRow = document.getElementById('modalAgentRow');
        if (d.userAgent) {
          agentRow.style.display = '';
          document.getElementById('modalAgent').textContent = d.userAgent;
        } else {
          agentRow.style.display = 'none';
        }

        // أزرار الإجراءات
        const actionsEl = document.getElementById('modalActions');
        actionsEl.innerHTML = '';

        @if(auth()->user()?->hasPermission('edit_users'))
          actionsEl.innerHTML += '<a class="btn btn-sm btn-outline-primary rounded-pill px-3" href="{{ url("admin/users") }}/' + d.userId + '/edit"><i class="bi bi-pencil"></i> تعديل</a>';
        @endif

        @if(auth()->user()?->hasPermission('manage_roles'))
          actionsEl.innerHTML += '<a class="btn btn-sm btn-outline-dark rounded-pill px-3" href="{{ url("admin/roles") }}?user=' + d.userId + '"><i class="bi bi-shield-lock"></i> الصلاحيات</a>';
        @endif
      });
    });
  </script>

@endsection