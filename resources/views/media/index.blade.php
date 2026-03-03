@extends('layouts.app')
@section('title','طلبات الميديا')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold">طلبات الميديا</h4>
</div>

{{-- تنبيه لرئيس الميديا --}}


<div class="card shadow-sm mb-3 border-primary">
    <div class="card-body">

        <h6 class="fw-bold text-primary mb-2">
            رابط فورم طلب الميديا (عام)
        </h6>

        <div class="input-group mb-2">
            <input type="text" 
                   class="form-control" 
                   id="publicMediaLink"
                   value="{{ route('media.public.form') }}"
                   readonly>

            <button class="btn btn-outline-secondary" 
                    onclick="copyMediaLink()">
                نسخ الرابط
            </button>

            <a href="{{ route('media.public.form') }}" 
               target="_blank"
               class="btn btn-namaa">
               فتح الفورم
            </a>
        </div>

        <small class="text-muted">
            أرسل هذا الرابط للمدربين أو الزبائن لتعبئة الطلب بدون تسجيل دخول.
        </small>

    </div>
</div>


<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم مقدم الطلب</th>
                    <th>الدبلومة</th>
                    <th>المدرب</th>
                    <th>التاريخ</th>
                    <th>إجراء</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $r)
                <tr>
                    <td>{{ $r->id }}</td>
                    <td>{{ $r->requester_name }}</td>
                    <td>{{ $r->diploma_name }}</td>
                    <td>{{ $r->trainer_name }}</td>
                    <td>{{ $r->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('media.show',$r) }}" 
                           class="btn btn-sm btn-outline-primary">
                           عرض التفاصيل
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

















<script>
function copyMediaLink() {
    var copyText = document.getElementById("publicMediaLink");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    alert("تم نسخ الرابط بنجاح");
}
</script>
@endsection