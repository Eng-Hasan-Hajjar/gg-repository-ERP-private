@extends('layouts.app')
@section('title', 'CRM - تفاصيل العميل المحتمل')

@section('content')

<style>
  /* ── Lead Profile Page ── */
  .lead-hero {
    background: var(--bs-body-bg, #fff);
    border: 1px solid rgba(226,232,240,.9);
    border-radius: 18px;
    padding: 24px 28px;
    margin-bottom: 20px;
    display: flex;
    align-items: flex-start;
    gap: 20px;
    flex-wrap: wrap;
  }
  .lead-avatar {
    width: 64px; height: 64px;
    border-radius: 50%;
    background: rgba(14,165,233,.12);
    color: #0369a1;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; font-weight: 900;
    flex-shrink: 0;
    border: 2px solid rgba(14,165,233,.2);
  }
  .lead-hero-info { flex: 1; min-width: 200px; }
  .lead-hero-info h4 { font-size: 1.3rem; font-weight: 900; margin: 0 0 6px; }
  .lead-badges { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 8px; }
  .badge-stage, .badge-status, .badge-source {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 12px; font-weight: 800; padding: 4px 12px;
    border-radius: 20px; border: 1px solid;
  }
  .badge-stage  { background: rgba(99,102,241,.1); color: #4338ca; border-color: rgba(99,102,241,.25); }
  .badge-status-pending   { background: rgba(245,158,11,.1); color: #b45309; border-color: rgba(245,158,11,.3); }
  .badge-status-converted { background: rgba(16,185,129,.1); color: #047857; border-color: rgba(16,185,129,.25); }
  .badge-status-canceled  { background: rgba(239,68,68,.1);  color: #b91c1c; border-color: rgba(239,68,68,.25); }
  .badge-source { background: rgba(14,165,233,.08); color: #0369a1; border-color: rgba(14,165,233,.2); }

  /* ── Timeline كرت الإحصاء السريع ── */
  .stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px; margin-bottom: 20px;
  }
  @media(max-width:768px) { .stats-row { grid-template-columns: repeat(2,1fr); } }
  .stat-tile {
    background: rgba(248,250,252,.9);
    border: 1px solid rgba(226,232,240,.9);
    border-radius: 14px; padding: 14px 16px;
    display: flex; align-items: center; gap: 12px;
  }
  .stat-tile-icon {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; flex-shrink: 0;
  }
  .stat-tile-icon.blue   { background: rgba(14,165,233,.12); color: #0369a1; }
  .stat-tile-icon.green  { background: rgba(16,185,129,.12); color: #047857; }
  .stat-tile-icon.amber  { background: rgba(245,158,11,.12); color: #b45309; }
  .stat-tile-icon.purple { background: rgba(99,102,241,.12); color: #4338ca; }
  .stat-tile-label { font-size: 11px; color: #64748b; font-weight: 700; }
  .stat-tile-val   { font-size: 15px; font-weight: 900; color: #0b1220; margin-top: 1px; }

  /* ── Info Cards ── */
  .info-card {
    background: #fff;
    border: 1px solid rgba(226,232,240,.9);
    border-radius: 16px; padding: 20px 22px;
    margin-bottom: 16px;
  }
  .info-card-title {
    font-size: 13px; font-weight: 900; text-transform: uppercase;
    letter-spacing: .6px; color: #64748b;
    margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
  }
  .info-card-title i { font-size: 15px; }
  .info-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px 20px;
  }
  @media(max-width:768px) { .info-grid { grid-template-columns: 1fr 1fr; } }
  .info-item {}
  .info-item .lbl {
    font-size: 11px; font-weight: 700;
    color: #94a3b8; text-transform: uppercase; letter-spacing: .4px;
    margin-bottom: 3px;
  }
  .info-item .val {
    font-size: 14px; font-weight: 700; color: #1e293b;
  }
  .info-item .val.muted { color: #94a3b8; font-weight: 400; }

  /* ── Diploma pills ── */
  .diploma-pill {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 14px; border-radius: 20px;
    background: rgba(14,165,233,.08);
    border: 1px solid rgba(14,165,233,.2);
    color: #0369a1; font-size: 13px; font-weight: 800;
    margin: 3px;
  }
  .diploma-pill.primary-pill {
    background: rgba(14,165,233,.15);
    border-color: rgba(14,165,233,.35);
  }

  /* ── Pending alert banner ── */
  .pending-banner {
    background: rgba(245,158,11,.08);
    border: 1px solid rgba(245,158,11,.25);
    border-right: 4px solid #f59e0b;
    border-radius: 12px; padding: 12px 18px;
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 16px; color: #92400e; font-weight: 700; font-size: 14px;
    border-top-right-radius: 0; border-bottom-right-radius: 0;
  }
  /* ── Timeline متابعات ── */
  .followup-timeline { position: relative; padding-right: 24px; }
  .followup-timeline::before {
    content: ''; position: absolute; right: 8px; top: 0; bottom: 0;
    width: 2px; background: rgba(226,232,240,.9);
  }
  .followup-item {
    position: relative; margin-bottom: 18px;
    background: rgba(248,250,252,.8);
    border: 1px solid rgba(226,232,240,.9);
    border-radius: 12px; padding: 12px 16px;
  }
  .followup-item::before {
    content: ''; position: absolute; right: -20px; top: 16px;
    width: 10px; height: 10px; border-radius: 50%;
    background: #0ea5e9; border: 2px solid #fff;
    box-shadow: 0 0 0 2px rgba(14,165,233,.3);
  }
  .followup-date {
    font-size: 11px; font-weight: 700; color: #94a3b8;
    text-transform: uppercase; letter-spacing: .4px; margin-bottom: 4px;
  }
  .followup-result {
    font-size: 13px; font-weight: 800; color: #1e293b; margin-bottom: 2px;
  }
  .followup-notes { font-size: 13px; color: #64748b; }

  /* ── Payment Form ── */
  .payment-section {
    background: rgba(16,185,129,.04);
    border: 1px solid rgba(16,185,129,.2);
    border-radius: 16px; padding: 20px 22px;
    margin-bottom: 16px;
  }
  .payment-section .section-head {
    display: flex; align-items: center; gap: 8px;
    font-weight: 900; font-size: 15px; color: #047857; margin-bottom: 16px;
  }

  /* ── Action buttons ── */
  .action-bar {
    display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px;
  }
  .btn-convert {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #fff; border: 0; border-radius: 12px;
    padding: 10px 22px; font-weight: 900; font-size: 14px;
    cursor: pointer; transition: filter .15s;
  }
  .btn-convert:hover { filter: brightness(.92); color: #fff; }
  .btn-edit-lead {
    background: rgba(255,255,255,.9);
    border: 1px solid rgba(226,232,240,.9);
    border-radius: 12px; padding: 10px 22px;
    font-weight: 900; font-size: 14px; color: #1e293b;
    text-decoration: none; transition: border-color .15s;
    display: inline-flex; align-items: center; gap: 6px;
  }
  .btn-edit-lead:hover { border-color: rgba(14,165,233,.4); color: #1e293b; }
  .btn-back {
    background: transparent; border: 0;
    color: #64748b; font-size: 13px; font-weight: 700;
    padding: 0; text-decoration: none;
    display: inline-flex; align-items: center; gap: 5px;
    margin-bottom: 16px;
  }
  .btn-back:hover { color: #0ea5e9; }
</style>

{{-- Back --}}
<a class="btn-back" href="{{ route('leads.index') }}">
  <i class="bi bi-arrow-right"></i> العودة إلى قائمة العملاء
</a>

{{-- ═══════════ Hero ═══════════ --}}
<div class="lead-hero">
  <div class="lead-avatar">
    {{ mb_substr($lead->full_name, 0, 1, 'UTF-8') }}
  </div>

  <div class="lead-hero-info">
    <h4>{{ $lead->full_name }}</h4>
    <div style="font-size:13px; color:#64748b; margin-bottom:6px;">
      <i class="bi bi-building" style="font-size:12px"></i> {{ $lead->branch->name ?? '—' }}
      @if($lead->phone)
        &nbsp;·&nbsp;
        <i class="bi bi-telephone" style="font-size:12px"></i>
        <a href="tel:{{ $lead->phone }}" style="color:#0369a1; font-weight:700;">{{ $lead->phone }}</a>
      @endif
      @if($lead->email)
        &nbsp;·&nbsp;
        <i class="bi bi-envelope" style="font-size:12px"></i>
        <a href="mailto:{{ $lead->email }}" style="color:#0369a1; font-weight:700;">{{ $lead->email }}</a>
      @endif
    </div>
    <div class="lead-badges">
      <span class="badge-stage"><i class="bi bi-flag" style="font-size:11px"></i>{{ $stage_ar }}</span>
      @php
        $statusClass = match($lead->registration_status) {
          'pending'   => 'badge-status-pending',
          'converted' => 'badge-status-converted',
          'canceled'  => 'badge-status-canceled',
          default     => 'badge-status-pending',
        };
      @endphp
      <span class="badge-stage {{ $statusClass }}">
        <i class="bi bi-circle-fill" style="font-size:8px"></i>{{ $registration_ar }}
      </span>
      @if($lead->source)
        <span class="badge-source"><i class="bi bi-signpost" style="font-size:11px"></i>{{ $source_ar }}</span>
      @endif
      @foreach($lead->diplomas as $d)
        <span class="diploma-pill {{ $d->pivot->is_primary ? 'primary-pill' : '' }}">
          <i class="bi bi-mortarboard-fill" style="font-size:11px"></i>{{ $d->name }}
        </span>
      @endforeach
    </div>
  </div>

  {{-- Action buttons --}}
  <div class="action-bar" style="margin-bottom:0; align-items:flex-start;">
    @if(auth()->user()?->hasPermission('edit_leads'))
      <a class="btn-edit-lead" href="{{ route('leads.edit', $lead) }}">
        <i class="bi bi-pencil" style="font-size:13px"></i> تعديل
      </a>
    @endif
    @if(auth()->user()?->hasPermission('convert_leads') && $lead->registration_status === 'pending')
      <form method="POST" action="{{ route('leads.convert', $lead) }}" style="margin:0">
        @csrf
        <button class="btn-convert">
          <i class="bi bi-person-check-fill" style="font-size:13px"></i> تحويل إلى طالب
        </button>
      </form>
    @endif
  </div>
</div>

{{-- ═══════════ Quick Stats ═══════════ --}}
<div class="stats-row">
  <div class="stat-tile">
    <div class="stat-tile-icon blue"><i class="bi bi-calendar-event"></i></div>
    <div>
      <div class="stat-tile-label">أول تواصل</div>
      <div class="stat-tile-val">
        {{ $lead->first_contact_date ? $lead->first_contact_date->format('Y/m/d') : '—' }}
      </div>
    </div>
  </div>
  <div class="stat-tile">
    <div class="stat-tile-icon amber"><i class="bi bi-clock-history"></i></div>
    <div>
      <div class="stat-tile-label">منذ التواصل</div>
      <div class="stat-tile-val">
        {{ $lead->first_contact_date ? $lead->first_contact_date->diffForHumans() : '—' }}
      </div>
    </div>
  </div>
  <div class="stat-tile">
    <div class="stat-tile-icon green"><i class="bi bi-chat-dots"></i></div>
    <div>
      <div class="stat-tile-label">عدد المتابعات</div>
      <div class="stat-tile-val">{{ $lead->followups->count() }} متابعة</div>
    </div>
  </div>
  <div class="stat-tile">
    <div class="stat-tile-icon purple"><i class="bi bi-person-circle"></i></div>
    <div>
      <div class="stat-tile-label">مسؤول التواصل</div>
      <div class="stat-tile-val" style="font-size:13px;">
        {{ $lead->creator->name ?? $lead->creator->email ?? '—' }}
      </div>
    </div>
  </div>
</div>

{{-- Pending banner --}}
@if($lead->registration_status === 'pending')
  <div class="pending-banner">
    <i class="bi bi-info-circle-fill" style="font-size:16px"></i>
    العميل قيد الانتظار — سيُحوَّل إلى طالب بعد تسجيل دفعة مالية أولية
  </div>
@endif

{{-- ═══════════ Info Sections ═══════════ --}}
<div class="row g-3">
  <div class="col-lg-8">

    {{-- المعلومات الشخصية --}}
    <div class="info-card">
      <div class="info-card-title">
        <i class="bi bi-person-vcard"></i> المعلومات الشخصية
      </div>
      <div class="info-grid">
        <div class="info-item">
          <div class="lbl">الاسم الكامل</div>
          <div class="val">{{ $lead->full_name }}</div>
        </div>
        <div class="info-item">
          <div class="lbl">الهاتف</div>
          <div class="val">
            @if($lead->phone)
              <a href="tel:{{ $lead->phone }}" style="color:#0369a1;">{{ $lead->phone }}</a>
            @else <span class="muted">—</span> @endif
          </div>
        </div>
        <div class="info-item">
          <div class="lbl">واتساب</div>
          <div class="val">
            @if($lead->whatsapp)
              <a href="https://wa.me/{{ $lead->whatsapp }}" target="_blank" style="color:#059669;">
                <i class="bi bi-whatsapp" style="font-size:13px"></i> {{ $lead->whatsapp }}
              </a>
            @else <span class="muted">—</span> @endif
          </div>
        </div>
        <div class="info-item">
          <div class="lbl">البريد الإلكتروني</div>
          <div class="val">
            @if($lead->email)
              <a href="mailto:{{ $lead->email }}" style="color:#0369a1;">{{ $lead->email }}</a>
            @else <span class="muted">—</span> @endif
          </div>
        </div>
        <div class="info-item">
          <div class="lbl">العمر</div>
          <div class="val">{{ $lead->age ? $lead->age . ' سنة' : '—' }}</div>
        </div>
        <div class="info-item">
          <div class="lbl">العمل / المهنة</div>
          <div class="val">{{ $lead->job ?? '—' }}</div>
        </div>
        <div class="info-item">
          <div class="lbl">المؤسسة / الشركة</div>
          <div class="val">{{ $lead->organization ?? '—' }}</div>
        </div>
        <div class="info-item">
          <div class="lbl">مكان السكن</div>
          <div class="val">{{ $lead->residence ?? '—' }}</div>
        </div>
        <div class="info-item">
          <div class="lbl">الدراسة</div>
          <div class="val">{{ $lead->study ?? '—' }}</div>
        </div>
      </div>
    </div>

    {{-- الموقع الجغرافي --}}
    <div class="info-card">
      <div class="info-card-title">
        <i class="bi bi-geo-alt"></i> الموقع الجغرافي
      </div>
      <div class="info-grid">
        <div class="info-item">
          <div class="lbl">البلد</div>
          <div class="val">{{ $lead->country ?? '—' }}</div>
        </div>
        <div class="info-item">
          <div class="lbl">المحافظة / المدينة</div>
          <div class="val">{{ $lead->province ?? '—' }}</div>
        </div>
        <div class="info-item">
          <div class="lbl">الفرع المسجَّل</div>
          <div class="val">{{ $lead->branch->name ?? '—' }}</div>
        </div>
      </div>
    </div>

    {{-- معلومات CRM --}}
    <div class="info-card">
      <div class="info-card-title">
        <i class="bi bi-headset"></i> معلومات CRM
      </div>
      <div class="info-grid">
        <div class="info-item">
          <div class="lbl">تاريخ أول تواصل</div>
          <div class="val">
            {{ $lead->first_contact_date ? $lead->first_contact_date->format('Y/m/d') : '—' }}
          </div>
        </div>
        <div class="info-item">
          <div class="lbl">مرحلة العميل</div>
          <div class="val">{{ $stage_ar }}</div>
        </div>
        <div class="info-item">
          <div class="lbl">حالة التسجيل</div>
          <div class="val">{{ $registration_ar }}</div>
        </div>
        <div class="info-item">
          <div class="lbl">المصدر</div>
          <div class="val">{{ $source_ar }}</div>
        </div>
        <div class="info-item">
          <div class="lbl">مسؤول التواصل</div>
          <div class="val">{{ $lead->creator->name ?? $lead->creator->email ?? '—' }}</div>
        </div>
        @if($lead->registered_at)
          <div class="info-item">
            <div class="lbl">تاريخ التسجيل</div>
            <div class="val">{{ $lead->registered_at->format('Y/m/d') }}</div>
          </div>
        @endif
        @if($lead->student_id)
          <div class="info-item">
            <div class="lbl">رقم الطالب</div>
            <div class="val">
              <a href="{{ route('students.show', $lead->student_id) }}" style="color:#0369a1;">
                <i class="bi bi-mortarboard-fill" style="font-size:12px"></i> عرض ملف الطالب
              </a>
            </div>
          </div>
        @endif
      </div>
      @if($lead->need)
        <div style="margin-top:14px; padding-top:14px; border-top:1px solid rgba(226,232,240,.9);">
          <div class="lbl" style="font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.4px; margin-bottom:5px;">الاحتياج / الهدف</div>
          <div style="font-size:14px; color:#1e293b; line-height:1.7;">{{ $lead->need }}</div>
        </div>
      @endif
      @if($lead->notes)
        <div style="margin-top:14px; padding-top:14px; border-top:1px solid rgba(226,232,240,.9);">
          <div class="lbl" style="font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.4px; margin-bottom:5px;">ملاحظات عامة</div>
          <div style="font-size:14px; color:#1e293b; line-height:1.7; white-space:pre-line;">{{ $lead->notes }}</div>
        </div>
      @endif
    </div>

  </div>

  {{-- ═══ Sidebar ═══ --}}
  <div class="col-lg-4">

    {{-- الدبلومات --}}
    <div class="info-card">
      <div class="info-card-title">
        <i class="bi bi-mortarboard-fill"></i> الدبلومات
      </div>
      @forelse($lead->diplomas as $d)
        <div style="display:flex; align-items:center; gap:10px; padding:10px 0; border-bottom:1px solid rgba(226,232,240,.7);">
          <div style="width:34px;height:34px;border-radius:10px;background:rgba(14,165,233,.1);color:#0369a1;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;">
            <i class="bi bi-book-fill"></i>
          </div>
          <div>
            <div style="font-size:13px;font-weight:800;color:#1e293b;">{{ $d->name }}</div>
            <div style="font-size:11px;color:#94a3b8;">
              {{ $d->code ?? '' }}
              @if($d->pivot->is_primary)
                · <span style="color:#059669; font-weight:700;">رئيسية</span>
              @endif
            </div>
          </div>
        </div>
      @empty
        <div style="text-align:center;padding:20px;color:#94a3b8;font-size:13px;">
          <i class="bi bi-mortarboard" style="font-size:22px;display:block;margin-bottom:6px;"></i>
          لا توجد دبلومات مرتبطة
        </div>
      @endforelse
    </div>

    {{-- معلومات الحساب المالي --}}
    @if($lead->financialAccount)
      <div class="info-card">
        <div class="info-card-title">
          <i class="bi bi-wallet2"></i> الحساب المالي
        </div>
        @php
          $account   = $lead->financialAccount;
          $totalIn   = $account->transactions()->where('type','in')->sum('amount');
          $totalOut  = $account->transactions()->where('type','out')->sum('amount');
          $lastTrx   = $account->transactions()->latest()->first();
        @endphp
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px;">
          <div style="background:rgba(16,185,129,.07);border:1px solid rgba(16,185,129,.2);border-radius:10px;padding:10px 12px;">
            <div style="font-size:11px;color:#047857;font-weight:700;">إجمالي المدفوع</div>
            <div style="font-size:16px;font-weight:900;color:#047857;">{{ number_format($totalIn, 0) }}</div>
          </div>
          <div style="background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.15);border-radius:10px;padding:10px 12px;">
            <div style="font-size:11px;color:#b91c1c;font-weight:700;">إجمالي المسحوب</div>
            <div style="font-size:16px;font-weight:900;color:#b91c1c;">{{ number_format($totalOut, 0) }}</div>
          </div>
        </div>
        @if($lastTrx)
          <div style="font-size:12px;color:#94a3b8;">
            آخر حركة: {{ \Carbon\Carbon::parse($lastTrx->created_at)->format('Y/m/d') }}
          </div>
        @endif
      </div>
    @endif

    {{-- Created At --}}
    <div class="info-card" style="padding:14px 18px;">
      <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:#94a3b8;">
        <i class="bi bi-calendar-plus" style="font-size:14px"></i>
        <span>أُنشئ بتاريخ: <b style="color:#64748b;">{{ $lead->created_at->format('Y/m/d — H:i') }}</b></span>
      </div>
      @if($lead->updated_at != $lead->created_at)
        <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:#94a3b8;margin-top:6px;">
          <i class="bi bi-pencil-square" style="font-size:14px"></i>
          <span>آخر تعديل: <b style="color:#64748b;">{{ $lead->updated_at->format('Y/m/d — H:i') }}</b></span>
        </div>
      @endif
    </div>

  </div>
</div>

{{-- ═══════════ دفعة مالية ═══════════ --}}
@if($lead->registration_status === 'pending')
  <div class="payment-section">
    <div class="section-head">
      <i class="bi bi-cash-coin" style="font-size:18px"></i>
      تسجيل دفعة مالية
    </div>
    <form method="POST" action="{{ route('financial.pay') }}">
      @csrf
      <input type="hidden" name="financial_account_id" value="{{ $lead->financialAccount?->id }}">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label fw-bold" style="font-size:13px;">الدبلومة</label>
          <select name="diploma_id" class="form-select" required>
            @foreach($lead->diplomas as $diploma)
              <option value="{{ $diploma->id }}">{{ $diploma->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-bold" style="font-size:13px;">الصندوق</label>
          <select name="cashbox_id" class="form-select" required>
            @foreach(\App\Models\Cashbox::where('status','active')->where('branch_id',$lead->branch_id)->get() as $box)
              <option value="{{ $box->id }}">{{ $box->name }} — {{ $box->currency }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label fw-bold" style="font-size:13px;">المبلغ</label>
          <input type="number" step="0.01" name="amount" class="form-control" required placeholder="0.00">
        </div>
        <div class="col-md-2">
          <label class="form-label fw-bold" style="font-size:13px;">ملاحظات</label>
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
@endif

{{-- ═══════════ المتابعات ═══════════ --}}
<div class="info-card">
  <div class="info-card-title">
    <i class="bi bi-chat-square-dots-fill"></i> سجل المتابعات
  </div>

  {{-- نموذج إضافة متابعة --}}
  <form method="POST" action="{{ route('leads.followups.store', $lead) }}"
        style="background:rgba(248,250,252,.8);border:1px solid rgba(226,232,240,.9);border-radius:12px;padding:16px;margin-bottom:20px;">
    @csrf
    <div style="font-size:13px;font-weight:800;color:#64748b;margin-bottom:12px;">
      <i class="bi bi-plus-circle"></i> إضافة متابعة جديدة
    </div>
    <div class="row g-2">
      <div class="col-md-3">
        <label class="form-label" style="font-size:12px;color:#94a3b8;font-weight:700;">تاريخ المتابعة</label>
        <input type="date" name="followup_date" class="form-control form-control-sm" value="{{ old('followup_date', now()->format('Y-m-d')) }}">
      </div>
      <div class="col-md-4">
        <label class="form-label" style="font-size:12px;color:#94a3b8;font-weight:700;">نتيجة المتابعة</label>
        <input name="result" class="form-control form-control-sm" placeholder="مثال: مهتم، يحتاج وقت..." value="{{ old('result') }}">
      </div>
      <div class="col-md-5">
        <label class="form-label" style="font-size:12px;color:#94a3b8;font-weight:700;">ملاحظات</label>
        <input name="notes" class="form-control form-control-sm" placeholder="تفاصيل إضافية..." value="{{ old('notes') }}">
      </div>
      <div class="col-12">
        <button class="btn btn-primary btn-sm fw-bold px-4">
          <i class="bi bi-plus-lg"></i> حفظ المتابعة
        </button>
      </div>
    </div>
  </form>

  {{-- Timeline المتابعات --}}
  @if($lead->followups->count())
    <div class="followup-timeline">
      @foreach($lead->followups->sortByDesc('followup_date') as $f)
        <div class="followup-item">
          <div class="followup-date">
            <i class="bi bi-calendar3" style="font-size:11px"></i>
            {{ $f->followup_date?->format('Y/m/d') ?? 'غير محدد' }}
          </div>
          @if($f->result)
            <div class="followup-result">{{ $f->result }}</div>
          @endif
          @if($f->notes)
            <div class="followup-notes">{{ $f->notes }}</div>
          @endif
        </div>
      @endforeach
    </div>
  @else
    <div style="text-align:center;padding:30px 20px;color:#94a3b8;">
      <i class="bi bi-chat-square-dots" style="font-size:28px;display:block;margin-bottom:8px;"></i>
      <div style="font-size:14px;font-weight:700;">لا توجد متابعات مسجّلة بعد</div>
      <div style="font-size:12px;margin-top:4px;">أضف أول متابعة للعميل من النموذج أعلاه</div>
    </div>
  @endif
</div>

@endsection