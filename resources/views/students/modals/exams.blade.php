@if($results->isEmpty())
    <div class="alert alert-info">لا توجد نتائج امتحانات مسجلة لهذا الطالب.</div>
@else
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>الامتحان</th>
                <th>الدبلومة</th>
                <th>التاريخ</th>
                <th>الدرجة</th>
                <th>النتيجة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $r)
            <tr>
                <td>{{ $r->exam->title ?? '-' }}</td>
                <td>{{ $r->exam->diploma->name ?? '-' }}</td>
                <td>{{ $r->exam->exam_date ?? '-' }}</td>
                <td class="fw-bold">{{ $r->score ?? '-' }}</td>
                <td>
                    @if($r->passed)
                        <span class="badge bg-success">ناجح</span>
                    @else
                        <span class="badge bg-danger">راسب</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif