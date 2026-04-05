@extends('layouts.app')
@section('title','طلبات الميديا')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h4 class="fw-bold mb-0">طلبات الميديا</h4>

    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('media.publish.index') }}" class="btn btn-soft-purple">
            <i class="bi bi-calendar2-week"></i> قائمة النشر
        </a>
        <a href="{{ route('media.create') }}" class="btn btn-namaa">
            <i class="bi bi-plus-lg"></i> طلب جديد
        </a>
    </div>
</div>

{{-- رابط الفورم العام --}}
<div class="card shadow-sm mb-3 border-primary" hidden>
    <div class="card-body">
        <h6 class="fw-bold text-primary mb-2">
            <i class="bi bi-link-45deg"></i>
            رابط فورم طلب الميديا (عام)
        </h6>
        <div class="input-group mb-2">
            <input type="text" class="form-control" id="publicMediaLink"
                   value="{{ route('media.public.form') }}" readonly>
            <button class="btn btn-outline-secondary" onclick="copyMediaLink()">
                <i class="bi bi-clipboard"></i> نسخ
            </button>
            <a href="{{ route('media.public.form') }}" target="_blank" class="btn btn-namaa">
               <i class="bi bi-box-arrow-up-left"></i> فتح الفورم
            </a>
        </div>
        <small class="text-muted">
            أرسل هذا الرابط للمدربين أو الزبائن لتعبئة الطلب بدون تسجيل دخول.
        </small>
    </div>
</div>

{{-- زر تنظيف المسودات --}}
<div class="d-flex justify-content-end mb-3">
    <form method="POST" action="{{ route('media.cleanup') }}"
          onsubmit="return confirm('هل أنت متأكد؟ سيتم حذف جميع المسودات والإدخالات التجريبية.')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-soft-warning btn-sm">
            <i class="bi bi-trash3"></i> حذف المسودات التجريبية
        </button>
    </form>
</div>

{{-- جدول الطلبات --}}
<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>اسم مقدم الطلب</th>
                    <th>الدبلومة</th>
                    <th>المدرب</th>
                    <th class="hide-mobile">رابط المحتوى</th>
                    <th class="hide-mobile">الموعد النهائي</th>
                    <th>التاريخ</th>
                    <th>إجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $r)
                <tr>
                    <td>{{ $r->id }}</td>
                    <td>{{ $r->requester_name }}</td>
                    <td>{{ $r->diploma_name }}</td>
                    <td>{{ $r->trainer_name }}</td>

                    {{-- رابط المحتوى --}}
                    <td class="hide-mobile">
                        <form method="POST" action="{{ route('media.update', $r) }}"
                              class="d-flex gap-1 align-items-center">
                            @csrf
                            <input type="text" name="content_link"
                                   value="{{ $r->content_link }}"
                                   class="form-control form-control-sm"
                                   placeholder="أدخل الرابط"
                                   style="min-width:140px;">
                            <button type="submit" class="btn btn-sm btn-outline-success" title="حفظ">
                                <i class="bi bi-check-lg"></i>
                            </button>
                        </form>
                    </td>

                    <td class="hide-mobile">
                        @if($r->editing_deadline)
                            <span class="badge {{ $r->editing_deadline->isPast() ? 'bg-danger' : 'bg-info' }}">
                                {{ $r->editing_deadline->format('Y-m-d') }}
                            </span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>

                    <td>{{ $r->created_at->format('Y-m-d') }}</td>
                    <td>
                        <button type="button"
                                class="btn btn-sm btn-outline-primary btn-show-media"
                                data-bs-toggle="modal"
                                data-bs-target="#mediaModal"
                                title="عرض التفاصيل"
                                data-id="{{ $r->id }}"
                                data-requester="{{ $r->requester_name }}"
                                data-phone="{{ $r->requester_phone ?? '—' }}"
                                data-diploma="{{ $r->diploma_name ?? '—' }}"
                                data-code="{{ $r->diploma_code ?? '—' }}"
                                data-trainer="{{ $r->trainer_name ?? '—' }}"
                                data-trainer-loc="{{ $r->trainer_location ?? '—' }}"
                                data-photo="{{ $r->trainer_photography_available ? '1' : '0' }}"
                                data-accred="{{ $r->certificate_accreditation ?? '—' }}"
                                data-cs="{{ $r->customer_service_responsible ?? '—' }}"
                                data-diploma-loc="{{ $r->diploma_location ?? '—' }}"
                                data-file="{{ $r->details_file ? asset('storage/'.$r->details_file) : '' }}"
                                data-image="{{ $r->trainer_image ? asset('storage/'.$r->trainer_image) : '' }}"
                                data-content-link="{{ $r->content_link ?? '' }}"
                                data-deadline="{{ $r->editing_deadline?->format('Y-m-d') ?? '—' }}"
                                data-need-ad="{{ $r->need_ad ? '1' : '0' }}"
                                data-need-invitation="{{ $r->need_invitation ? '1' : '0' }}"
                                data-need-review="{{ $r->need_review_video ? '1' : '0' }}"
                                data-need-content="{{ $r->need_content ? '1' : '0' }}"
                                data-need-podcast="{{ $r->need_podcast ? '1' : '0' }}"
                                data-need-carousel="{{ $r->need_carousel ? '1' : '0' }}"
                                data-need-other="{{ $r->need_other ?? '' }}"
                                data-design-done="{{ $r->design_done ? '1' : '0' }}"
                                data-ad-done="{{ $r->ad_done ? '1' : '0' }}"
                                data-invitation-done="{{ $r->invitation_done ? '1' : '0' }}"
                                data-content-done="{{ $r->content_done ? '1' : '0' }}"
                                data-podcast-done="{{ $r->podcast_done ? '1' : '0' }}"
                                data-reviews-done="{{ $r->reviews_done ? '1' : '0' }}"
                                data-notes="{{ $r->notes ?? '—' }}"
                                data-created="{{ $r->created_at->format('Y-m-d H:i') }}">
                            <i class="bi bi-eye"></i> عرض
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        لا توجد طلبات حالياً
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($requests->hasPages())
    <div class="card-footer">
        {{ $requests->links() }}
    </div>
    @endif
</div>


{{-- ============================================= --}}
{{--     Modal تفاصيل طلب الميديا                   --}}
{{-- ============================================= --}}
<div class="modal fade" id="mediaModal" tabindex="-1" aria-labelledby="mediaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0" style="border-radius:22px; overflow:hidden; box-shadow: 0 30px 80px rgba(2,6,23,.18);">

            {{-- Header --}}
            <div class="modal-header border-0 text-white px-4 py-3"
                 style="background: linear-gradient(90deg, #0ea5e9 0%, #10b981 100%);">
                <h5 class="modal-title fw-bold" id="mediaModalLabel">
                    <i class="bi bi-file-earmark-richtext me-2"></i>
                    تفاصيل طلب الميديا
                    <span class="badge bg-white text-dark rounded-pill ms-2 fw-bold" id="mModalId"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body p-4">

                {{-- ===== القسم 1: بيانات مقدم الطلب ===== --}}
                <div class="media-section-title">
                    <i class="bi bi-person-badge-fill"></i> بيانات مقدم الطلب
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="m-detail-box">
                            <div class="m-detail-label"><i class="bi bi-person-fill text-primary"></i> الاسم</div>
                            <div class="m-detail-value" id="mRequester"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="m-detail-box">
                            <div class="m-detail-label"><i class="bi bi-telephone-fill text-success"></i> الهاتف</div>
                            <div class="m-detail-value" id="mPhone"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="m-detail-box">
                            <div class="m-detail-label"><i class="bi bi-calendar3 text-info"></i> تاريخ الطلب</div>
                            <div class="m-detail-value" id="mCreated"></div>
                        </div>
                    </div>
                </div>

                {{-- ===== القسم 2: بيانات الدبلومة ===== --}}
                <div class="media-section-title">
                    <i class="bi bi-mortarboard-fill"></i> بيانات الدبلومة
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="m-detail-box">
                            <div class="m-detail-label"><i class="bi bi-book-fill text-primary"></i> اسم الدبلومة</div>
                            <div class="m-detail-value" id="mDiploma"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="m-detail-box">
                            <div class="m-detail-label"><i class="bi bi-upc-scan text-secondary"></i> الترميز</div>
                            <div class="m-detail-value" id="mCode"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="m-detail-box">
                            <div class="m-detail-label"><i class="bi bi-geo-alt-fill text-danger"></i> مكان التنفيذ</div>
                            <div class="m-detail-value" id="mDiplomaLoc"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="m-detail-box">
                            <div class="m-detail-label"><i class="bi bi-award-fill text-warning"></i> اعتمادية الشهادة</div>
                            <div class="m-detail-value" id="mAccred"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="m-detail-box">
                            <div class="m-detail-label"><i class="bi bi-headset text-info"></i> مسؤول خدمة العملاء</div>
                            <div class="m-detail-value" id="mCS"></div>
                        </div>
                    </div>
                </div>

                {{-- ===== القسم 3: بيانات المدرب ===== --}}
                <div class="media-section-title">
                    <i class="bi bi-person-workspace"></i> بيانات المدرب
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="m-detail-box">
                            <div class="m-detail-label"><i class="bi bi-person-fill text-success"></i> اسم المدرب</div>
                            <div class="m-detail-value" id="mTrainer"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="m-detail-box">
                            <div class="m-detail-label"><i class="bi bi-pin-map-fill text-danger"></i> مكان المدرب</div>
                            <div class="m-detail-value" id="mTrainerLoc"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="m-detail-box">
                            <div class="m-detail-label"><i class="bi bi-camera-fill text-secondary"></i> تصوير المدرب</div>
                            <div class="m-detail-value" id="mPhoto"></div>
                        </div>
                    </div>
                </div>

                {{-- صورة المدرب + ملف التفاصيل --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6" id="mImageWrap" style="display:none;">
                        <div class="m-detail-box text-center">
                            <div class="m-detail-label"><i class="bi bi-image text-primary"></i> صورة المدرب</div>
                            <img id="mImage" src="" class="rounded shadow-sm mt-2" style="max-width:180px; max-height:180px; object-fit:cover;">
                        </div>
                    </div>
                    <div class="col-md-6" id="mFileWrap" style="display:none;">
                        <div class="m-detail-box">
                            <div class="m-detail-label"><i class="bi bi-file-earmark-arrow-down text-success"></i> ملف التفاصيل</div>
                            <a id="mFile" href="#" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="bi bi-download"></i> تحميل / عرض الملف
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ===== القسم 4: المواد المطلوبة ===== --}}
                <div class="media-section-title">
                    <i class="bi bi-list-check"></i> المواد المطلوبة
                </div>
                <div class="d-flex flex-wrap gap-2 mb-4" id="mNeeds"></div>

                {{-- ===== القسم 5: حالة التنفيذ ===== --}}
                <div class="media-section-title">
                    <i class="bi bi-clipboard-check-fill"></i> حالة التنفيذ
                </div>
                <div class="d-flex flex-wrap gap-3 mb-4" id="mStatus"></div>

                {{-- ===== القسم 6: رابط المحتوى + الموعد + الملاحظات ===== --}}
                <div class="media-section-title">
                    <i class="bi bi-info-circle-fill"></i> معلومات إضافية
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="m-detail-box">
                            <div class="m-detail-label"><i class="bi bi-link-45deg text-primary"></i> رابط المحتوى</div>
                            <div class="m-detail-value" id="mContentLink"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="m-detail-box">
                            <div class="m-detail-label"><i class="bi bi-alarm text-danger"></i> الموعد النهائي</div>
                            <div class="m-detail-value" id="mDeadline"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="m-detail-box">
                            <div class="m-detail-label"><i class="bi bi-chat-dots text-muted"></i> ملاحظات</div>
                            <div class="m-detail-value m-notes-text" id="mNotes"></div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="modal-footer border-0 px-4 pb-4 pt-2">
                <a href="#" class="btn btn-namaa btn-sm" id="mEditBtn">
                    <i class="bi bi-pencil-square"></i> تعديل الطلب
                </a>
                <button type="button" class="btn btn-soft btn-sm" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i> إغلاق
                </button>
            </div>

        </div>
    </div>
</div>

@endsection


@push('styles')
<style>
    /* ===== Section Title ===== */
    .media-section-title {
        font-weight: 900;
        font-size: .95rem;
        color: #0b1220;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 2px solid rgba(14, 165, 233, .15);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .media-section-title i {
        color: #0ea5e9;
        font-size: 1.1rem;
    }

    /* ===== Detail Boxes ===== */
    .m-detail-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px 16px;
        height: 100%;
        transition: .15s ease;
    }
    .m-detail-box:hover {
        border-color: rgba(14, 165, 233, .3);
        background: #f0f9ff;
    }
    .m-detail-label {
        font-size: .78rem;
        font-weight: 800;
        color: #94a3b8;
        margin-bottom: 6px;
    }
    .m-detail-value {
        font-weight: 800;
        font-size: .95rem;
        color: #0b1220;
    }
    .m-notes-text {
        font-weight: 700;
        line-height: 1.9;
        white-space: pre-wrap;
        max-height: 80px;
        overflow-y: auto;
    }

    /* ===== Need Tags ===== */
    .m-need-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 12px;
        font-weight: 800;
        font-size: .82rem;
        background: rgba(14, 165, 233, .08);
        color: #0ea5e9;
        border: 1px solid rgba(14, 165, 233, .25);
    }

    /* ===== Status Chips ===== */
    .m-status-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 14px;
        font-weight: 800;
        font-size: .85rem;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }
    .m-status-chip.done {
        background: rgba(16, 185, 129, .08);
        border-color: rgba(16, 185, 129, .3);
        color: #047857;
    }
    .m-status-chip.pending {
        background: rgba(239, 68, 68, .05);
        border-color: rgba(239, 68, 68, .2);
        color: #dc2626;
    }
</style>
@endpush


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('.btn-show-media').forEach(function(btn) {
        btn.addEventListener('click', function() {

            // بيانات أساسية
            document.getElementById('mModalId').textContent   = '#' + this.dataset.id;
            document.getElementById('mRequester').textContent  = this.dataset.requester;
            document.getElementById('mPhone').textContent      = this.dataset.phone;
            document.getElementById('mCreated').textContent    = this.dataset.created;
            document.getElementById('mDiploma').textContent    = this.dataset.diploma;
            document.getElementById('mCode').textContent       = this.dataset.code;
            document.getElementById('mDiplomaLoc').textContent = this.dataset.diplomaLoc;
            document.getElementById('mAccred').textContent     = this.dataset.accred;
            document.getElementById('mCS').textContent         = this.dataset.cs;
            document.getElementById('mTrainer').textContent    = this.dataset.trainer;
            document.getElementById('mTrainerLoc').textContent = this.dataset.trainerLoc;
            document.getElementById('mNotes').textContent      = this.dataset.notes;

            // تصوير المدرب
            document.getElementById('mPhoto').innerHTML = this.dataset.photo === '1'
                ? '<span class="badge bg-success">متاح</span>'
                : '<span class="badge bg-secondary">غير متاح</span>';

            // صورة المدرب
            var imgWrap = document.getElementById('mImageWrap');
            if (this.dataset.image) {
                imgWrap.style.display = 'block';
                document.getElementById('mImage').src = this.dataset.image;
            } else {
                imgWrap.style.display = 'none';
            }

            // ملف التفاصيل
            var fileWrap = document.getElementById('mFileWrap');
            if (this.dataset.file) {
                fileWrap.style.display = 'block';
                document.getElementById('mFile').href = this.dataset.file;
            } else {
                fileWrap.style.display = 'none';
            }

            // رابط المحتوى
            var linkEl = document.getElementById('mContentLink');
            if (this.dataset.contentLink) {
                linkEl.innerHTML = '<a href="' + this.dataset.contentLink + '" target="_blank" class="text-decoration-none">' +
                    '<i class="bi bi-box-arrow-up-left"></i> فتح الرابط</a>';
            } else {
                linkEl.textContent = '—';
            }

            // الموعد النهائي
            var deadlineEl = document.getElementById('mDeadline');
            if (this.dataset.deadline && this.dataset.deadline !== '—') {
                var deadlineDate = new Date(this.dataset.deadline);
                var now = new Date();
                var isPast = deadlineDate < now;
                deadlineEl.innerHTML = '<span class="badge ' + (isPast ? 'bg-danger' : 'bg-info') + '">' +
                    this.dataset.deadline + '</span>';
            } else {
                deadlineEl.textContent = '—';
            }

            // المواد المطلوبة
            var needsHtml = '';
            var needs = [
                { key: 'needAd',        label: 'إعلان دبلومة',      icon: 'bi-megaphone' },
                { key: 'needInvitation', label: 'دعوة افتتاحية',     icon: 'bi-envelope-open' },
                { key: 'needReview',     label: 'فيديو تقييم',       icon: 'bi-star' },
                { key: 'needContent',    label: 'محتوى',             icon: 'bi-file-text' },
                { key: 'needPodcast',    label: 'بودكاست',           icon: 'bi-mic' },
                { key: 'needCarousel',   label: 'كاروسيل',           icon: 'bi-images' }
            ];

            needs.forEach(function(n) {
                if (btn.dataset[n.key] === '1') {
                    needsHtml += '<span class="m-need-tag"><i class="bi ' + n.icon + '"></i> ' + n.label + '</span>';
                }
            });

            if (this.dataset.needOther) {
                needsHtml += '<span class="m-need-tag"><i class="bi bi-three-dots"></i> ' + this.dataset.needOther + '</span>';
            }

            if (!needsHtml) needsHtml = '<span class="text-muted">لا يوجد</span>';
            document.getElementById('mNeeds').innerHTML = needsHtml;

            // حالة التنفيذ
            var statusHtml = '';
            var statuses = [
                { key: 'designDone',     label: 'التصميم',     icon: 'bi-brush' },
                { key: 'adDone',         label: 'الإعلان',     icon: 'bi-megaphone' },
                { key: 'invitationDone', label: 'الدعوة',      icon: 'bi-envelope-open' },
                { key: 'contentDone',    label: 'المحتوى',     icon: 'bi-file-text' },
                { key: 'podcastDone',    label: 'البودكاست',   icon: 'bi-mic' },
                { key: 'reviewsDone',    label: 'التقييمات',   icon: 'bi-star' }
            ];

            statuses.forEach(function(s) {
                var isDone = btn.dataset[s.key] === '1';
                statusHtml += '<div class="m-status-chip ' + (isDone ? 'done' : 'pending') + '">' +
                    '<i class="bi ' + s.icon + '"></i> ' + s.label + ' ' +
                    (isDone
                        ? '<i class="bi bi-check-circle-fill text-success"></i>'
                        : '<i class="bi bi-x-circle text-danger"></i>') +
                    '</div>';
            });

            document.getElementById('mStatus').innerHTML = statusHtml;

            // رابط التعديل
            document.getElementById('mEditBtn').href = '/media-requests/' + this.dataset.id;
        });
    });

});

function copyMediaLink() {
    var copyText = document.getElementById("publicMediaLink");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    alert("تم نسخ الرابط بنجاح");
}
</script>
@endpush