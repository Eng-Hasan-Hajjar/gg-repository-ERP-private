<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <style>
    body{ font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    table{ width:100%; border-collapse:collapse; }
    th,td{ border:1px solid #ddd; padding:6px; text-align:center; }
    th{ background:#f5f5f5; }
    .t{ text-align:right; font-weight:bold; margin-bottom:10px; }
  </style>
</head>
<body>
  <div class="t">تقرير الدوام من {{ $from }} إلى {{ $to }}</div>
  <table>
    <thead>
      <tr>
        <th>الموظف</th><th>أيام حضور</th><th>غياب</th><th>إجازة</th><th>تأخير</th><th>ساعات</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rows as $r)
        <tr>
          <td>{{ $r->employee->full_name ?? '-' }}</td>
          <td>{{ (int)$r->present_days }}</td>
          <td>{{ (int)$r->absent_days }}</td>
          <td>{{ (int)$r->leave_days }}</td>
          <td>{{ (int)$r->late_minutes }}</td>
          <td>{{ round(((int)$r->worked_minutes)/60,2) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
