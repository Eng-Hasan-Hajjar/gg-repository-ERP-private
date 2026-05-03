
<?php $__env->startSection('title', 'CRM - تفاصيل العميل المحتمل'); ?>

<?php $__env->startPush('styles'); ?>
  <style>
    /* ── Lead Profile Page ── */
    .lead-hero {
      background: var(--bs-body-bg, #fff);
      border: 1px solid rgba(226, 232, 240, .9);
      border-radius: 18px;
      padding: 24px 28px;
      margin-bottom: 20px;
      display: flex;
      align-items: flex-start;
      gap: 20px;
      flex-wrap: wrap;
    }

    .lead-avatar {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      background: rgba(14, 165, 233, .12);
      color: #0369a1;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      font-weight: 900;
      flex-shrink: 0;
      border: 2px solid rgba(14, 165, 233, .2);
    }

    .lead-hero-info {
      flex: 1;
      min-width: 200px;
    }

    .lead-hero-info h4 {
      font-size: 1.3rem;
      font-weight: 900;
      margin: 0 0 6px;
    }

    .lead-badges {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      margin-top: 8px;
    }

    .badge-stage,
    .badge-status,
    .badge-source {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 12px;
      font-weight: 800;
      padding: 4px 12px;
      border-radius: 20px;
      border: 1px solid;
    }

    .badge-stage {
      background: rgba(99, 102, 241, .1);
      color: #4338ca;
      border-color: rgba(99, 102, 241, .25);
    }

    .badge-status-pending {
      background: rgba(245, 158, 11, .1);
      color: #b45309;
      border-color: rgba(245, 158, 11, .3);
    }

    .badge-status-converted {
      background: rgba(16, 185, 129, .1);
      color: #047857;
      border-color: rgba(16, 185, 129, .25);
    }

    .badge-status-canceled {
      background: rgba(239, 68, 68, .1);
      color: #b91c1c;
      border-color: rgba(239, 68, 68, .25);
    }

    .badge-source {
      background: rgba(14, 165, 233, .08);
      color: #0369a1;
      border-color: rgba(14, 165, 233, .2);
    }

    .stats-row {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
      margin-bottom: 20px;
    }

    @media(max-width:768px) {
      .stats-row {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    .stat-tile {
      background: rgba(248, 250, 252, .9);
      border: 1px solid rgba(226, 232, 240, .9);
      border-radius: 14px;
      padding: 14px 16px;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .stat-tile-icon {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      flex-shrink: 0;
    }

    .stat-tile-icon.blue {
      background: rgba(14, 165, 233, .12);
      color: #0369a1;
    }

    .stat-tile-icon.green {
      background: rgba(16, 185, 129, .12);
      color: #047857;
    }

    .stat-tile-icon.amber {
      background: rgba(245, 158, 11, .12);
      color: #b45309;
    }

    .stat-tile-icon.purple {
      background: rgba(99, 102, 241, .12);
      color: #4338ca;
    }

    .stat-tile-label {
      font-size: 11px;
      color: #64748b;
      font-weight: 700;
    }

    .stat-tile-val {
      font-size: 15px;
      font-weight: 900;
      color: #0b1220;
      margin-top: 1px;
    }

    .info-card {
      background: #fff;
      border: 1px solid rgba(226, 232, 240, .9);
      border-radius: 16px;
      padding: 20px 22px;
      margin-bottom: 16px;
    }

    .info-card-title {
      font-size: 13px;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: .6px;
      color: #64748b;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .info-card-title i {
      font-size: 15px;
    }

    .info-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 14px 20px;
    }

    @media(max-width:768px) {
      .info-grid {
        grid-template-columns: 1fr 1fr;
      }
    }

    .info-item .lbl {
      font-size: 11px;
      font-weight: 700;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: .4px;
      margin-bottom: 3px;
    }

    .info-item .val {
      font-size: 14px;
      font-weight: 700;
      color: #1e293b;
    }

    .info-item .val.muted {
      color: #94a3b8;
      font-weight: 400;
    }

    .diploma-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      border-radius: 20px;
      background: rgba(14, 165, 233, .08);
      border: 1px solid rgba(14, 165, 233, .2);
      color: #0369a1;
      font-size: 13px;
      font-weight: 800;
      margin: 3px;
    }

    .diploma-pill.primary-pill {
      background: rgba(14, 165, 233, .15);
      border-color: rgba(14, 165, 233, .35);
    }

    .pending-banner {
      background: rgba(245, 158, 11, .08);
      border: 1px solid rgba(245, 158, 11, .25);
      border-right: 4px solid #f59e0b;
      border-radius: 12px;
      padding: 12px 18px;
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 16px;
      color: #92400e;
      font-weight: 700;
      font-size: 14px;
      border-top-right-radius: 0;
      border-bottom-right-radius: 0;
    }

    .followup-timeline {
      position: relative;
      padding-right: 24px;
    }

    .followup-timeline::before {
      content: '';
      position: absolute;
      right: 8px;
      top: 0;
      bottom: 0;
      width: 2px;
      background: rgba(226, 232, 240, .9);
    }

    .followup-item {
      position: relative;
      margin-bottom: 18px;
      background: rgba(248, 250, 252, .8);
      border: 1px solid rgba(226, 232, 240, .9);
      border-radius: 12px;
      padding: 12px 16px;
    }

    .followup-item::before {
      content: '';
      position: absolute;
      right: -20px;
      top: 16px;
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: #0ea5e9;
      border: 2px solid #fff;
      box-shadow: 0 0 0 2px rgba(14, 165, 233, .3);
    }

    .followup-date {
      font-size: 11px;
      font-weight: 700;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: .4px;
      margin-bottom: 4px;
    }

    .followup-result {
      font-size: 13px;
      font-weight: 800;
      color: #1e293b;
      margin-bottom: 2px;
    }

    .followup-notes {
      font-size: 13px;
      color: #64748b;
    }

    /* ── خطة الدفع ── */
    .payment-plan-section {
      background: rgba(16, 185, 129, .04);
      border: 2px solid rgba(16, 185, 129, .2);
      border-radius: 16px;
      padding: 20px 22px;
      margin-bottom: 20px;
    }

    .payment-plan-section .section-head {
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 900;
      font-size: 16px;
      color: #047857;
      margin-bottom: 16px;
    }

    /* ── ستايلات التحقق ── */
    .installment-input.invalid {
      border-color: #dc3545 !important;
      box-shadow: 0 0 0 0.15rem rgba(220, 53, 69, 0.25) !important;
    }

    .installment-input.valid {
      border-color: #198754 !important;
    }

    .inst-error-msg {
      color: #dc3545;
      font-size: 12px;
      font-weight: 700;
      margin-top: 4px;
      display: none;
    }

    .inst-error-msg.show {
      display: block;
    }

    .summary-box {
      border-radius: 12px;
      padding: 14px;
      margin-top: 16px;
      border: 2px solid;
    }

    .summary-box.ok {
      background: rgba(16, 185, 129, 0.08);
      border-color: #10b981;
    }

    .summary-box.bad {
      background: rgba(220, 53, 69, 0.08);
      border-color: #dc3545;
    }

    .summary-row {
      display: flex;
      justify-content: space-around;
      text-align: center;
    }

    .summary-row .item small {
      display: block;
      color: #64748b;
      font-size: 11px;
      font-weight: 700;
    }

    .summary-row .item strong {
      font-size: 16px;
      font-weight: 800;
    }

    .summary-box .warning-msg {
      text-align: center;
      color: #dc3545;
      font-weight: 800;
      font-size: 13px;
      margin-top: 8px;
      padding-top: 8px;
      border-top: 1px dashed rgba(220, 53, 69, 0.3);
    }

    .action-bar {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      margin-bottom: 20px;
    }

    .btn-convert {
      background: linear-gradient(135deg, #10b981, #059669);
      color: #fff;
      border: 0;
      border-radius: 12px;
      padding: 10px 22px;
      font-weight: 900;
      font-size: 14px;
      cursor: pointer;
      transition: filter .15s;
    }

    .btn-convert:hover {
      filter: brightness(.92);
      color: #fff;
    }

    .btn-edit-lead {
      background: rgba(255, 255, 255, .9);
      border: 1px solid rgba(226, 232, 240, .9);
      border-radius: 12px;
      padding: 10px 22px;
      font-weight: 900;
      font-size: 14px;
      color: #1e293b;
      text-decoration: none;
      transition: border-color .15s;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .btn-edit-lead:hover {
      border-color: rgba(14, 165, 233, .4);
      color: #1e293b;
    }

    .btn-back {
      background: transparent;
      border: 0;
      color: #64748b;
      font-size: 13px;
      font-weight: 700;
      padding: 0;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      margin-bottom: 16px;
    }

    .btn-back:hover {
      color: #0ea5e9;
    }

    /* ── كرت خطة الدفع الموجودة ── */
    .plan-card {
      background: #fff;
      border: 1px solid rgba(16, 185, 129, .25);
      border-radius: 14px;
      padding: 18px;
      margin-bottom: 12px;
    }

    .plan-card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 12px;
      flex-wrap: wrap;
      gap: 8px;
    }

    .plan-card-title {
      font-weight: 900;
      font-size: 15px;
      color: #1e293b;
    }

    .plan-kv {
      display: grid;
      grid-template-columns: 150px 1fr;
      gap: 8px;
      padding: 7px 0;
      border-bottom: 1px dashed rgba(226, 232, 240, .8);
    }

    .plan-kv:last-child {
      border-bottom: 0;
    }

    .plan-kv .k {
      font-size: 12px;
      font-weight: 700;
      color: #94a3b8;
    }

    .plan-kv .v {
      font-size: 13px;
      font-weight: 700;
      color: #1e293b;
    }
  </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

  
  <a class="btn-back" href="<?php echo e(route('leads.index')); ?>">
    <i class="bi bi-arrow-right"></i> العودة إلى قائمة العملاء
  </a>

  
  <div class="lead-hero">
    <div class="lead-avatar">
      <?php echo e(mb_substr($lead->full_name, 0, 1, 'UTF-8')); ?>

    </div>

    <div class="lead-hero-info">
      <h4><?php echo e($lead->full_name); ?></h4>
      <div style="font-size:13px; color:#64748b; margin-bottom:6px;">
        <i class="bi bi-building" style="font-size:12px"></i> <?php echo e($lead->branch->name ?? '—'); ?>

        <?php if($lead->phone): ?>
          &nbsp;·&nbsp;
          <i class="bi bi-telephone" style="font-size:12px"></i>
          <a href="tel:<?php echo e($lead->phone); ?>" style="color:#0369a1; font-weight:700;"><?php echo e($lead->phone); ?></a>
        <?php endif; ?>
        <?php if($lead->email): ?>
          &nbsp;·&nbsp;
          <i class="bi bi-envelope" style="font-size:12px"></i>
          <a href="mailto:<?php echo e($lead->email); ?>" style="color:#0369a1; font-weight:700;"><?php echo e($lead->email); ?></a>
        <?php endif; ?>
      </div>
      <div class="lead-badges">
        <span class="badge-stage"><i class="bi bi-flag" style="font-size:11px"></i><?php echo e($stage_ar); ?></span>
        <?php
          $statusClass = match ($lead->registration_status) {
            'pending' => 'badge-status-pending',
            'converted' => 'badge-status-converted',
            'canceled' => 'badge-status-canceled',
            default => 'badge-status-pending',
          };
        ?>
        <span class="badge-stage <?php echo e($statusClass); ?>">
          <i class="bi bi-circle-fill" style="font-size:8px"></i><?php echo e($registration_ar); ?>

        </span>
        <?php if($lead->source): ?>
          <span class="badge-source"><i class="bi bi-signpost" style="font-size:11px"></i><?php echo e($source_ar); ?></span>
        <?php endif; ?>
        <?php $__currentLoopData = $lead->diplomas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <span class="diploma-pill <?php echo e($d->pivot->is_primary ? 'primary-pill' : ''); ?>">
            <i class="bi bi-mortarboard-fill" style="font-size:11px"></i><?php echo e($d->name); ?>

          </span>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>

    
    <div class="action-bar" style="margin-bottom:0; align-items:flex-start;">
      <?php if(auth()->user()?->hasPermission('edit_leads')): ?>
        <a class="btn-edit-lead" href="<?php echo e(route('leads.edit', $lead)); ?>">
          <i class="bi bi-pencil" style="font-size:13px"></i> تعديل
        </a>
      <?php endif; ?>


    </div>
  </div>

  
  <div class="stats-row">
    <div class="stat-tile">
      <div class="stat-tile-icon blue"><i class="bi bi-calendar-event"></i></div>
      <div>
        <div class="stat-tile-label">أول تواصل</div>
        <div class="stat-tile-val"><?php echo e($lead->first_contact_date ? $lead->first_contact_date->format('Y/m/d') : '—'); ?>

        </div>
      </div>
    </div>
    <div class="stat-tile">
      <div class="stat-tile-icon amber"><i class="bi bi-clock-history"></i></div>
      <div>
        <div class="stat-tile-label">منذ التواصل</div>
        <div class="stat-tile-val"><?php echo e($lead->first_contact_date ? $lead->first_contact_date->diffForHumans() : '—'); ?>

        </div>
      </div>
    </div>
    <div class="stat-tile">
      <div class="stat-tile-icon green"><i class="bi bi-chat-dots"></i></div>
      <div>
        <div class="stat-tile-label">عدد المتابعات</div>
        <div class="stat-tile-val"><?php echo e($lead->followups->count()); ?> متابعة</div>
      </div>
    </div>
    <div class="stat-tile">
      <div class="stat-tile-icon purple"><i class="bi bi-person-circle"></i></div>
      <div>
        <div class="stat-tile-label">مسؤول التواصل</div>
        <div class="stat-tile-val" style="font-size:13px;"><?php echo e($lead->creator->name ?? $lead->creator->email ?? '—'); ?>

        </div>
      </div>
    </div>
  </div>

  
  <?php if($lead->registration_status === 'pending'): ?>
    <div class="pending-banner">
      <i class="bi bi-info-circle-fill" style="font-size:16px"></i>
      العميل قيد الانتظار — سيُحوَّل إلى طالب تلقائياً بعد ترحيل أول دفعة
    </div>
  <?php endif; ?>

  
  <div class="row g-3">
    <div class="col-lg-8">

      
      <div class="info-card">
        <div class="info-card-title"><i class="bi bi-person-vcard"></i> المعلومات الشخصية</div>
        <div class="info-grid">
          <div class="info-item">
            <div class="lbl">الاسم الكامل</div>
            <div class="val"><?php echo e($lead->full_name); ?></div>
          </div>
          <div class="info-item">
            <div class="lbl">الهاتف</div>
            <div class="val">
              <?php if($lead->phone): ?>
                <a href="tel:<?php echo e($lead->phone); ?>" style="color:#0369a1;"><?php echo e($lead->phone); ?></a>
              <?php else: ?> <span class="muted">—</span> <?php endif; ?>
            </div>
          </div>
          <div class="info-item">
            <div class="lbl">واتساب</div>
            <div class="val">
              <?php if($lead->whatsapp): ?>
                <a href="https://wa.me/<?php echo e($lead->whatsapp); ?>" target="_blank" style="color:#059669;">
                  <i class="bi bi-whatsapp" style="font-size:13px"></i> <?php echo e($lead->whatsapp); ?>

                </a>
              <?php else: ?> <span class="muted">—</span> <?php endif; ?>
            </div>
          </div>
          <div class="info-item">
            <div class="lbl">البريد الإلكتروني</div>
            <div class="val">
              <?php if($lead->email): ?>
                <a href="mailto:<?php echo e($lead->email); ?>" style="color:#0369a1;"><?php echo e($lead->email); ?></a>
              <?php else: ?> <span class="muted">—</span> <?php endif; ?>
            </div>
          </div>
          <div class="info-item">
            <div class="lbl">العمر</div>
            <div class="val"><?php echo e($lead->age ? $lead->age . ' سنة' : '—'); ?></div>
          </div>
          <div class="info-item">
            <div class="lbl">العمل / المهنة</div>
            <div class="val"><?php echo e($lead->job ?? '—'); ?></div>
          </div>
          <div class="info-item">
            <div class="lbl">المؤسسة / الشركة</div>
            <div class="val"><?php echo e($lead->organization ?? '—'); ?></div>
          </div>
          <div class="info-item">
            <div class="lbl">مكان السكن</div>
            <div class="val"><?php echo e($lead->residence ?? '—'); ?></div>
          </div>
          <div class="info-item">
            <div class="lbl">الدراسة</div>
            <div class="val"><?php echo e($lead->study ?? '—'); ?></div>
          </div>
        </div>
      </div>

      
      <div class="info-card">
        <div class="info-card-title"><i class="bi bi-geo-alt"></i> الموقع الجغرافي</div>
        <div class="info-grid">
          <div class="info-item">
            <div class="lbl">البلد</div>
            <div class="val"><?php echo e($lead->country ?? '—'); ?></div>
          </div>
          <div class="info-item">
            <div class="lbl">المحافظة / المدينة</div>
            <div class="val"><?php echo e($lead->province ?? '—'); ?></div>
          </div>
          <div class="info-item">
            <div class="lbl">الفرع المسجَّل</div>
            <div class="val"><?php echo e($lead->branch->name ?? '—'); ?></div>
          </div>
        </div>
      </div>

      
      <div class="info-card">
        <div class="info-card-title"><i class="bi bi-headset"></i> معلومات CRM</div>
        <div class="info-grid">
          <div class="info-item">
            <div class="lbl">تاريخ أول تواصل</div>
            <div class="val"><?php echo e($lead->first_contact_date ? $lead->first_contact_date->format('Y/m/d') : '—'); ?></div>
          </div>
          <div class="info-item">
            <div class="lbl">مرحلة العميل</div>
            <div class="val"><?php echo e($stage_ar); ?></div>
          </div>
          <div class="info-item">
            <div class="lbl">حالة التسجيل</div>
            <div class="val"><?php echo e($registration_ar); ?></div>
          </div>
          <div class="info-item">
            <div class="lbl">المصدر</div>
            <div class="val"><?php echo e($source_ar); ?></div>
          </div>
          <div class="info-item">
            <div class="lbl">مسؤول التواصل</div>
            <div class="val"><?php echo e($lead->creator->name ?? $lead->creator->email ?? '—'); ?></div>
          </div>
          <?php if($lead->registered_at): ?>
            <div class="info-item">
              <div class="lbl">تاريخ التسجيل</div>
              <div class="val"><?php echo e($lead->registered_at->format('Y/m/d')); ?></div>
            </div>
          <?php endif; ?>
          <?php if($lead->student_id): ?>
            <div class="info-item">
              <div class="lbl">رقم الطالب</div>
              <div class="val">
                <a href="<?php echo e(route('students.show', $lead->student_id)); ?>" style="color:#0369a1;">
                  <i class="bi bi-mortarboard-fill" style="font-size:12px"></i> عرض ملف الطالب
                </a>
              </div>
            </div>
          <?php endif; ?>
        </div>
        <?php if($lead->need): ?>
          <div style="margin-top:14px; padding-top:14px; border-top:1px solid rgba(226,232,240,.9);">
            <div class="lbl"
              style="font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.4px; margin-bottom:5px;">
              الاحتياج / الهدف</div>
            <div style="font-size:14px; color:#1e293b; line-height:1.7;"><?php echo e($lead->need); ?></div>
          </div>
        <?php endif; ?>
        <?php if($lead->notes): ?>
          <div style="margin-top:14px; padding-top:14px; border-top:1px solid rgba(226,232,240,.9);">
            <div class="lbl"
              style="font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.4px; margin-bottom:5px;">
              ملاحظات عامة</div>
            <div style="font-size:14px; color:#1e293b; line-height:1.7; white-space:pre-line;"><?php echo e($lead->notes); ?></div>
          </div>
        <?php endif; ?>
      </div>





      


      <?php if(auth()->user()?->hasPermission('manage_lead_payment_plan') || auth()->user()?->hasRole('super_admin')): ?>

        <?php if($lead->registration_status === 'pending'): ?>

          
          <?php if($paymentPlans->count()): ?>
            <div class="info-card" style="border-color:rgba(16,185,129,.3);">
              <div class="info-card-title" style="color:#047857;">
                <i class="bi bi-calendar-check-fill"></i> خطط الدفع المسجّلة
              </div>

              <?php $__currentLoopData = $paymentPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="plan-card">
                  <div class="plan-card-header">
                    <div class="plan-card-title">
                      <i class="bi bi-mortarboard-fill" style="color:#0ea5e9; font-size:14px"></i>
                      <?php echo e($plan->diploma->name); ?>

                    </div>
                    <span class="badge bg-info text-dark"><?php echo e($plan->currency); ?></span>
                  </div>

                  <div class="plan-kv">
                    <div class="k">المبلغ الإجمالي</div>
                    <div class="v fw-bold"><?php echo e(number_format($plan->total_amount, 2)); ?></div>
                  </div>
                  <div class="plan-kv">
                    <div class="k">نوع الدفع</div>
                    <div class="v"><?php echo e($plan->payment_type === 'full' ? 'كامل' : 'دفعات'); ?></div>
                  </div>
                  <?php if($plan->payment_type === 'installments'): ?>
                    <div class="plan-kv">
                      <div class="k">عدد الدفعات</div>
                      <div class="v"><?php echo e($plan->installments_count); ?></div>
                    </div>
                  <?php endif; ?>
                  <div class="plan-kv">
                    <div class="k">المدفوع</div>
                    <div class="v text-success fw-bold"><?php echo e(number_format($plan->paid ?? 0, 2)); ?></div>
                  </div>
                  <div class="plan-kv">
                    <div class="k">المتبقي</div>
                    <div class="v text-warning fw-bold"><?php echo e(number_format(max($plan->remaining ?? 0, 0), 2)); ?></div>
                  </div>

                  <?php if($plan->installments->count()): ?>
                    <div class="mt-3 pt-2 border-top">
                      <div class="fw-bold small text-muted mb-2">جدول الأقساط:</div>
                      <?php $__currentLoopData = $plan->installments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="plan-kv">
                          <div class="k">الدفعة <?php echo e($loop->iteration); ?></div>
                          <div class="v">
                            <?php echo e(number_format($i->amount, 2)); ?>

                            <span class="text-muted">(<?php echo e($i->due_date->format('Y-m-d')); ?>)</span>
                          </div>
                        </div>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                  <?php endif; ?>

                  
                  <?php if(($plan->payments_count ?? 0) <= 1): ?>
                    <div class="mt-3">
                      <a href="<?php echo e(route('payment.plan.edit', $plan->id)); ?>" class="btn btn-sm btn-outline-warning fw-bold">
                        <i class="bi bi-pencil"></i> تعديل الخطة
                      </a>
                    </div>
                  <?php else: ?>
                    <div class="mt-3">
                      <span class="badge bg-secondary">
                        <i class="bi bi-lock"></i> لا يمكن تعديل الخطة بعد وجود دفعات
                      </span>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          <?php endif; ?>

          
          <?php
            $diplomasWithoutPlan = $lead->diplomas->filter(fn($d) => !isset($plansByDiploma[$d->id]));
          ?>

          <?php if($diplomasWithoutPlan->count()): ?>
            <div class="payment-plan-section">
              <div class="section-head">
                <i class="bi bi-wallet2" style="font-size:20px"></i>
                إنشاء خطة دفع
              </div>

              <form method="POST" action="<?php echo e(route('payment.plan.store')); ?>" id="lead-plan-form">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="lead_id" value="<?php echo e($lead->id); ?>">
                <input type="hidden" name="diploma_id" id="lead_selected_diploma">

                <?php if($errors->any()): ?>
                  <div class="alert alert-danger fw-semibold mb-3">
                    <i class="bi bi-exclamation-triangle"></i>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div><?php echo e($e); ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </div>
                <?php endif; ?>

                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="fw-bold">الدبلومة</label>
                    <select class="form-select" id="lead_diploma_preview">
                      <?php $__currentLoopData = $diplomasWithoutPlan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($d->id); ?>"><?php echo e($d->name); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                  </div>

                  <div class="col-md-4">
                    <label class="fw-bold">المبلغ الإجمالي</label>
                    <input type="number" step="0.01" min="0.01" name="total_amount" id="lead_total_amount" class="form-control"
                      required>
                  </div>

                  <div class="col-md-4">
                    <label class="fw-bold">العملة</label>
                    <select name="currency" class="form-select">
                      <option value="USD">USD</option>
                      <option value="EUR">EUR</option>
                      <option value="TRY">TRY</option>
                    </select>
                  </div>

                  <div class="col-md-4">
                    <label class="fw-bold">نوع الدفع</label>
                    <select name="payment_type" id="lead_payment_type" class="form-select">
                      <option value="full">كامل</option>
                      <option value="installments">دفعات</option>
                    </select>
                  </div>

                  <div class="col-md-4 lead-installments-box d-none">
                    <label class="fw-bold">عدد الدفعات</label>
                    <select name="installments_count" id="lead_installments_count" class="form-select">
                      <option value="">اختر</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                    </select>
                  </div>
                </div>

                <hr>

                <div id="lead_installments_container"></div>
                <div id="lead_installments_summary"></div>

                <div class="row mt-3">
                  <?php $__currentLoopData = $diplomasWithoutPlan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 mt-2">
                      <button type="submit" class="btn btn-success fw-bold w-100 lead-plan-save-btn"
                        onclick="selectLeadDiploma(<?php echo e($d->id); ?>)">
                        <i class="bi bi-check2-circle"></i> حفظ خطة <?php echo e($d->name); ?>

                      </button>
                    </div>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
              </form>
            </div>
          <?php endif; ?>

          
          <div class="info-card" style="background:rgba(16,185,129,.04); border-color:rgba(16,185,129,.2);">
            <div class="info-card-title" style="color:#047857;">
              <i class="bi bi-cash-coin"></i> تسجيل دفعة مالية
            </div>

            <?php if(session('success')): ?>
              <div class="alert alert-success fw-bold">
                <i class="bi bi-check-circle"></i> <?php echo e(session('success')); ?>

              </div>
            <?php endif; ?>

            <?php if($errors->has('amount')): ?>
              <div class="alert alert-danger fw-bold">
                <i class="bi bi-exclamation-triangle"></i> <?php echo e($errors->first('amount')); ?>

              </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('financial.pay')); ?>">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="financial_account_id" value="<?php echo e($lead->financialAccount?->id); ?>">
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label fw-bold">الدبلومة</label>
                  <select name="diploma_id" class="form-select" required>
                    <?php $__currentLoopData = $lead->diplomas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $diploma): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e($diploma->id); ?>"><?php echo e($diploma->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </select>
                </div>
                <div class="col-md-4">
                  <label class="form-label fw-bold">الصندوق</label>
                  <select name="cashbox_id" class="form-select" required>
                    <?php $__currentLoopData = \App\Models\Cashbox::where('status', 'active')->where('branch_id', $lead->branch_id)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $box): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e($box->id); ?>"><?php echo e($box->name); ?> — <?php echo e($box->currency); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </select>
                </div>
                <div class="col-md-2">
                  <label class="form-label fw-bold">المبلغ</label>
                  <input type="number" step="0.01" name="amount" class="form-control" required placeholder="0.00">
                </div>
                <div class="col-md-2">
                  <label class="form-label fw-bold">ملاحظات</label>
                  <input type="text" name="notes" class="form-control" placeholder="اختياري">
                </div>
                <div class="col-12">
                  <button class="btn btn-success fw-bold px-4">
                    <i class="bi bi-check-circle-fill"></i> تسجيل الدفعة
                  </button>
                </div>
              </div>
            </form>
          </div>

        <?php else: ?>
          
          <?php if($lead->student_id): ?>
            <div class="info-card" style="background:rgba(16,185,129,.04); border-color:rgba(16,185,129,.3);">
              <div class="info-card-title" style="color:#047857;">
                <i class="bi bi-person-check-fill"></i> تم التحويل إلى طالب
              </div>
              <div class="d-flex align-items-center gap-3">
                <div class="fs-5 fw-bold text-success">
                  <i class="bi bi-check-circle-fill"></i>
                  تم تحويل هذا العميل إلى طالب بنجاح
                </div>
                <a href="<?php echo e(route('students.show', $lead->student_id)); ?>" class="btn btn-success fw-bold">
                  <i class="bi bi-mortarboard-fill"></i> عرض ملف الطالب
                </a>
              </div>
            </div>
          <?php endif; ?>
        <?php endif; ?>
      <?php endif; ?>



    </div>

    
    <div class="col-lg-4">

      
      <div class="info-card">
        <div class="info-card-title"><i class="bi bi-mortarboard-fill"></i> الدبلومات</div>
        <?php $__empty_1 = true; $__currentLoopData = $lead->diplomas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <div
            style="display:flex; align-items:center; gap:10px; padding:10px 0; border-bottom:1px solid rgba(226,232,240,.7);">
            <div
              style="width:34px;height:34px;border-radius:10px;background:rgba(14,165,233,.1);color:#0369a1;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;">
              <i class="bi bi-book-fill"></i>
            </div>
            <div>
              <div style="font-size:13px;font-weight:800;color:#1e293b;"><?php echo e($d->name); ?></div>
              <div style="font-size:11px;color:#94a3b8;">
                <?php echo e($d->code ?? ''); ?>

                <?php if($d->pivot->is_primary): ?>
                  · <span style="color:#059669; font-weight:700;">رئيسية</span>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <div style="text-align:center;padding:20px;color:#94a3b8;font-size:13px;">
            <i class="bi bi-mortarboard" style="font-size:22px;display:block;margin-bottom:6px;"></i>
            لا توجد دبلومات مرتبطة
          </div>
        <?php endif; ?>
      </div>

      
      <?php if($lead->financialAccount): ?>
        <div class="info-card">
          <div class="info-card-title"><i class="bi bi-wallet2"></i> الحساب المالي</div>
          <?php
            $account = $lead->financialAccount;
            $totalIn = $account->transactions()->where('type', 'in')->sum('amount');
            $totalOut = $account->transactions()->where('type', 'out')->sum('amount');
            $lastTrx = $account->transactions()->latest()->first();
          ?>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px;">
            <div
              style="background:rgba(16,185,129,.07);border:1px solid rgba(16,185,129,.2);border-radius:10px;padding:10px 12px;">
              <div style="font-size:11px;color:#047857;font-weight:700;">إجمالي المدفوع</div>
              <div style="font-size:16px;font-weight:900;color:#047857;"><?php echo e(number_format($totalIn, 0)); ?></div>
            </div>
            <div
              style="background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.15);border-radius:10px;padding:10px 12px;">
              <div style="font-size:11px;color:#b91c1c;font-weight:700;">إجمالي المسحوب</div>
              <div style="font-size:16px;font-weight:900;color:#b91c1c;"><?php echo e(number_format($totalOut, 0)); ?></div>
            </div>
          </div>
          <?php if($lastTrx): ?>
            <div style="font-size:12px;color:#94a3b8;">
              آخر حركة: <?php echo e(\Carbon\Carbon::parse($lastTrx->created_at)->format('Y/m/d')); ?>

            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      
      <div class="info-card" style="padding:14px 18px;">
        <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:#94a3b8;">
          <i class="bi bi-calendar-plus" style="font-size:14px"></i>
          <span>أُنشئ بتاريخ: <b style="color:#64748b;"><?php echo e($lead->created_at->format('Y/m/d — H:i')); ?></b></span>
        </div>
        <?php if($lead->updated_at != $lead->created_at): ?>
          <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:#94a3b8;margin-top:6px;">
            <i class="bi bi-pencil-square" style="font-size:14px"></i>
            <span>آخر تعديل: <b style="color:#64748b;"><?php echo e($lead->updated_at->format('Y/m/d — H:i')); ?></b></span>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>

  
  <div class="info-card">
    <div class="info-card-title"><i class="bi bi-chat-square-dots-fill"></i> سجل المتابعات</div>

    <form method="POST" action="<?php echo e(route('leads.followups.store', $lead)); ?>"
      style="background:rgba(248,250,252,.8);border:1px solid rgba(226,232,240,.9);border-radius:12px;padding:16px;margin-bottom:20px;">
      <?php echo csrf_field(); ?>
      <div style="font-size:13px;font-weight:800;color:#64748b;margin-bottom:12px;">
        <i class="bi bi-plus-circle"></i> إضافة متابعة جديدة
      </div>
      <div class="row g-2">
        <div class="col-md-3">
          <label class="form-label" style="font-size:12px;color:#94a3b8;font-weight:700;">تاريخ المتابعة</label>
          <input type="date" name="followup_date" class="form-control form-control-sm"
            value="<?php echo e(old('followup_date', now()->format('Y-m-d'))); ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label" style="font-size:12px;color:#94a3b8;font-weight:700;">نتيجة المتابعة</label>
          <input name="result" class="form-control form-control-sm" placeholder="مثال: مهتم، يحتاج وقت..."
            value="<?php echo e(old('result')); ?>">
        </div>
        <div class="col-md-5">
          <label class="form-label" style="font-size:12px;color:#94a3b8;font-weight:700;">ملاحظات</label>
          <input name="notes" class="form-control form-control-sm" placeholder="تفاصيل إضافية..."
            value="<?php echo e(old('notes')); ?>">
        </div>
        <div class="col-12">
          <button class="btn btn-primary btn-sm fw-bold px-4">
            <i class="bi bi-plus-lg"></i> حفظ المتابعة
          </button>
        </div>
      </div>
    </form>

    <?php if($lead->followups->count()): ?>
      <div class="followup-timeline">
        <?php $__currentLoopData = $lead->followups->sortByDesc('followup_date'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="followup-item">
            <div class="followup-date">
              <i class="bi bi-calendar3" style="font-size:11px"></i>
              <?php echo e($f->followup_date?->format('Y/m/d') ?? 'غير محدد'); ?>

            </div>
            <?php if($f->result): ?>
              <div class="followup-result"><?php echo e($f->result); ?></div>
            <?php endif; ?>
            <?php if($f->notes): ?>
              <div class="followup-notes"><?php echo e($f->notes); ?></div>
            <?php endif; ?>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    <?php else: ?>
      <div style="text-align:center;padding:30px 20px;color:#94a3b8;">
        <i class="bi bi-chat-square-dots" style="font-size:28px;display:block;margin-bottom:8px;"></i>
        <div style="font-size:14px;font-weight:700;">لا توجد متابعات مسجّلة بعد</div>
        <div style="font-size:12px;margin-top:4px;">أضف أول متابعة للعميل من النموذج أعلاه</div>
      </div>
    <?php endif; ?>
  </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
  <script>
    (function () {
      'use strict';

      const $totalInput = document.getElementById('lead_total_amount');
      const $paymentType = document.getElementById('lead_payment_type');
      const $countSelect = document.getElementById('lead_installments_count');
      const $container = document.getElementById('lead_installments_container');
      const $summary = document.getElementById('lead_installments_summary');
      const $installBox = document.querySelector('.lead-installments-box');

      if (!$totalInput) return; // إذا لم يكن النموذج موجوداً (العميل تحوّل)

      function buildInstallmentRow(index) {
        return `
        <div class="row g-3 mt-1">
          <div class="col-md-6">
            <label class="fw-bold">قيمة الدفعة ${index}</label>
            <input type="number" step="0.01" min="0.01"
                   name="installments[${index}][amount]"
                   class="form-control installment-input"
                   data-index="${index}"
                   placeholder="0.00" required>
            <div class="inst-error-msg" data-error="${index}"></div>
          </div>
          <div class="col-md-6">
            <label class="fw-bold">تاريخ الدفعة ${index}</label>
            <input type="date" name="installments[${index}][due_date]"
                   class="form-control" required>
          </div>
        </div>`;
      }

      function runValidation() {
        const total = parseFloat($totalInput.value) || 0;
        const inputs = document.querySelectorAll('.installment-input');
        const btns = document.querySelectorAll('.lead-plan-save-btn');

        if (inputs.length === 0) {
          $summary.innerHTML = '';
          btns.forEach(b => { b.disabled = false; b.classList.remove('opacity-50'); });
          return true;
        }

        let sum = 0, hasError = false;
        inputs.forEach(inp => { sum += parseFloat(inp.value) || 0; });

        inputs.forEach(inp => {
          const idx = inp.dataset.index;
          const val = parseFloat(inp.value) || 0;
          const errEl = document.querySelector(`[data-error="${idx}"]`);
          if (inp.value === '') { inp.classList.remove('invalid', 'valid'); errEl.classList.remove('show'); return; }
          if (val <= 0) {
            inp.classList.add('invalid'); inp.classList.remove('valid');
            errEl.textContent = '⚠️ القيمة يجب أن تكون أكبر من صفر';
            errEl.classList.add('show'); hasError = true; return;
          }
          if (total > 0 && val > total) {
            inp.classList.add('invalid'); inp.classList.remove('valid');
            errEl.textContent = `⚠️ قيمة الدفعة (${val.toFixed(2)}) أكبر من الإجمالي (${total.toFixed(2)})`;
            errEl.classList.add('show'); hasError = true; return;
          }
          inp.classList.add('valid'); inp.classList.remove('invalid');
          errEl.classList.remove('show');
        });

        const overLimit = total > 0 && sum > total;
        const remaining = total - sum;
        const boxClass = (overLimit || hasError) ? 'bad' : 'ok';

        $summary.innerHTML = `
        <div class="summary-box ${boxClass}">
          <div class="summary-row">
            <div class="item"><small>الإجمالي</small><strong>${total.toFixed(2)}</strong></div>
            <div class="item"><small>مجموع الدفعات</small><strong class="${overLimit ? 'text-danger' : ''}">${sum.toFixed(2)}</strong></div>
            <div class="item"><small>المتبقي</small><strong class="${remaining < 0 ? 'text-danger' : 'text-success'}">${remaining.toFixed(2)}</strong></div>
          </div>
          ${overLimit ? '<div class="warning-msg">⚠️ مجموع الدفعات يتجاوز المبلغ الإجمالي!</div>' : ''}
          ${hasError && !overLimit ? '<div class="warning-msg">⚠️ يوجد أخطاء في القيم المدخلة</div>' : ''}
        </div>`;

        const isValid = !hasError && !overLimit;
        btns.forEach(btn => {
          btn.disabled = !isValid;
          btn.classList.toggle('opacity-50', !isValid);
          btn.style.cursor = isValid ? 'pointer' : 'not-allowed';
        });
        return isValid;
      }

      function attachInputListeners() {
        document.querySelectorAll('.installment-input').forEach(inp => {
          inp.addEventListener('input', runValidation);
        });
      }

      $paymentType.addEventListener('change', function () {
        const isInst = this.value === 'installments';
        $installBox.classList.toggle('d-none', !isInst);
        if (!isInst) { $container.innerHTML = ''; $summary.innerHTML = ''; $countSelect.value = ''; runValidation(); }
      });

      $countSelect.addEventListener('change', function () {
        const count = parseInt(this.value);
        $container.innerHTML = ''; $summary.innerHTML = '';
        if (!count) { runValidation(); return; }
        let html = '';
        for (let i = 1; i <= count; i++) html += buildInstallmentRow(i);
        $container.innerHTML = html;
        attachInputListeners();
        runValidation();
      });

      $totalInput.addEventListener('input', runValidation);

      document.getElementById('lead-plan-form').addEventListener('submit', function (e) {
        if (!runValidation()) {
          e.preventDefault();
          alert('⚠️ يرجى تصحيح الأخطاء قبل الحفظ');
          return false;
        }
      });

      window.selectLeadDiploma = function (id) {
        document.getElementById('lead_selected_diploma').value = id;
      };

      runValidation();
    })();
  </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/crm/leads/show.blade.php ENDPATH**/ ?>