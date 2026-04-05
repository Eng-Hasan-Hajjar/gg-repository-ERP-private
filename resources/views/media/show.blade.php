@extends('layouts.app')
@section('title','تفاصيل الطلب')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">تفاصيل طلب الميديا #{{ $media->id }}</h4>
    <a href="{{ route('media.index') }}" class="btn btn-soft">
        <i class="bi bi-arrow-right"></i> رجوع
    </a>
</div>

<div class="card shadow-sm p-4 mb-4">

    <div class="row g-3">

        <div class="col-md-6">
            <strong>اسم مقدم الطلب:</strong>
            <div>{{ $media->requester_name }}</div>
        </div>

        <div class="col-md-6">
            <strong>رقم الهاتف:</strong>
            <div>{{ $media->requester_phone ?? '—' }}</div>
        </div>

        <div class="col-md-6">
            <strong>اسم الدبلومة:</strong>
            <div>{{ $media->diploma_name ?? '—' }}</div>
        </div>

        <div class="col-md-6">
            <strong>ترميز الدبلومة:</strong>
            <div>{{ $media->diploma_code ?? '—' }}</div>
        </div>

        <div class="col-md-6">
            <strong>اسم المدرب:</strong>
            <div>{{ $media->trainer_name ?? '—' }}</div>
        </div>

        <div class="col-md-6">
            <strong>مكان المدرب:</strong>
            <div>{{ $media->trainer_location ?? '—' }}</div>
        </div>

        <div class="col-md-6">
            <strong>اعتمادية الشهادة:</strong>
            <div>{{ $media->certificate_accreditation ?? '—' }}</div>
        </div>

        <div class="col-md-6">
            <strong>مسؤول خدمة العملاء:</strong>
            <div>{{ $media->customer_service_responsible ?? '—' }}</div>
        </div>

        <div class="col-md-6">
            <strong>مكان تنفيذ الدبلومة:</strong>
            <div>{{ $media->diploma_location ?? '—' }}</div>
        </div>

        <div class="col-md-6">
            <strong>تصوير المدرب متاح؟</strong>
            <div>
                @if($media->trainer_photography_available)
                    <span class="badge bg-success">نعم</span>
                @else
                    <span class="badge bg-secondary">لا</span>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <strong>ملف التفاصيل:</strong>
            <div>
                @if($media->details_file)
                    <a href="{{ asset('storage/'.$media->details_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-file-earmark-arrow-down"></i> عرض الملف
                    </a>
                @else
                    —
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <strong>صورة المدرب:</strong>
            <div>
                @if($media->trainer_image)
                    <img src="{{ asset('storage/'.$media->trainer_image) }}"
                         width="120" class="rounded shadow-sm">
                @else
                    —
                @endif
            </div>
        </div>

        <div class="col-12">
            <hr>
            <h5 class="fw-bold">المواد المطلوبة</h5>
            <div class="d-flex flex-wrap gap-2 mt-2">
                @if($media->need_ad)
                    <span class="badge badge-namaa">إعلان دبلومة</span>
                @endif
                @if($media->need_invitation)
                    <span class="badge badge-namaa">دعوة افتتاحية</span>
                @endif
                @if($media->need_review_video)
                    <span class="badge badge-namaa">فيديو تقييم</span>
                @endif
                @if($media->need_content)
                    <span class="badge badge-namaa">محتوى</span>
                @endif
                @if($media->need_podcast)
                    <span class="badge badge-namaa">بودكاست</span>
                @endif
                @if($media->need_carousel)
                    <span class="badge badge-namaa">كاروسيل</span>
                @endif
                @if($media->need_other)
                    <span class="badge badge-namaa">{{ $media->need_other }}</span>
                @endif
            </div>
        </div>

        <div class="col-12">
            <strong>ملاحظات:</strong>
            <div>{{ $media->notes ?? '—' }}</div>
        </div>

    </div>
</div>

{{-- قسم تعديل رابط المحتوى + الموعد النهائي + حالة التنفيذ --}}
<div class="card shadow-sm p-4 mb-4">
    <h5 class="fw-bold mb-3">
        <i class="bi bi-pencil-square"></i> تحديث الحالة
    </h5>

    <form method="POST" action="{{ route('media.update', $media) }}">
        @csrf

        <div class="row g-3">

            {{-- رابط المحتوى --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">رابط المحتوى</label>
                <input type="url" name="content_link"
                       class="form-control"
                       value="{{ $media->content_link }}"
                       placeholder="https://docs.google.com/...">
                <small class="text-muted">يمكن لكاتبة المحتوى تعديل هذا الحقل</small>
            </div>

            {{-- موعد نهاية التعديل --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">موعد نهاية التعديل</label>
                <input type="date" name="editing_deadline"
                       class="form-control"
                       value="{{ $media->editing_deadline?->format('Y-m-d') }}">
            </div>

            {{-- حالة التنفيذ --}}
            <div class="col-12">
                <label class="form-label fw-bold">حالة التنفيذ</label>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" name="design_done" class="form-check-input"
                                   {{ $media->design_done ? 'checked' : '' }}>
                            <label class="form-check-label">التصميم</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" name="ad_done" class="form-check-input"
                                   {{ $media->ad_done ? 'checked' : '' }}>
                            <label class="form-check-label">الإعلان</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" name="invitation_done" class="form-check-input"
                                   {{ $media->invitation_done ? 'checked' : '' }}>
                            <label class="form-check-label">الدعوة</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" name="content_done" class="form-check-input"
                                   {{ $media->content_done ? 'checked' : '' }}>
                            <label class="form-check-label">المحتوى</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" name="podcast_done" class="form-check-input"
                                   {{ $media->podcast_done ? 'checked' : '' }}>
                            <label class="form-check-label">البودكاست</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" name="reviews_done" class="form-check-input"
                                   {{ $media->reviews_done ? 'checked' : '' }}>
                            <label class="form-check-label">التقييمات</label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ملاحظات --}}
            <div class="col-12">
                <label class="form-label fw-bold">ملاحظات</label>
                <textarea name="notes" class="form-control" rows="2">{{ $media->notes }}</textarea>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-namaa">
                    <i class="bi bi-check2-circle"></i> حفظ التحديث
                </button>
            </div>
        </div>
    </form>
</div>

{{-- سجلات النشر المرتبطة --}}
@if($media->publishEntries->count())
<div class="card shadow-sm p-4">
    <h5 class="fw-bold mb-3">
        <i class="bi bi-calendar2-week"></i> سجلات النشر
    </h5>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>الدبلومة</th>
                    <th>المحتوى</th>
                    <th>النوع</th>
                    <th>الفرع</th>
                    <th>ميتا</th>
                    <th>تيك توك</th>
                    <th>يوتوب</th>
                    <th>تاريخ النشر</th>
                </tr>
            </thead>
            <tbody>
                @foreach($media->publishEntries as $entry)
                <tr>
                    <td>{{ $entry->diploma_name }}</td>
                    <td>{{ $entry->content_category_label }}</td>
                    <td>{{ $entry->content_type_label }}</td>
                    <td>{{ $entry->branch ?? '—' }}</td>
                    <td>{!! $entry->published_meta ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-x-circle text-muted"></i>' !!}</td>
                    <td>{!! $entry->published_tiktok ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-x-circle text-muted"></i>' !!}</td>
                    <td>{!! $entry->published_youtube ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-x-circle text-muted"></i>' !!}</td>
                    <td>{{ $entry->publish_date?->format('Y-m-d') ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection