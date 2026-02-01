<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: "DejaVu Sans", Arial, sans-serif; direction: rtl; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 6px; text-align: center; font-size: 11px; }
    th { background: #f3f4f6; }
    .emp { text-align: right; font-weight: bold; }
  </style>
</head>
<body>
  <h3 style="margin:0 0 10px 0;">تقويم الدوام الشهري — {{ $month }}</h3>

  <table>
    <thead>
      <tr>
        <th style="width:220px">الموظف</th>
        @foreach($days as $date)
          <th>{{ \Carbon\Carbon::parse($date)->day }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @foreach($employees as $emp)
        <tr>
          <td class="emp">{{ $emp->full_name }} — {{ $emp->branch->name ?? '-' }}</td>
          @foreach($days as $date)
            @php($status = $recordsMap[$emp->id][$date] ?? null)
            <td>{{ $letterMap[$status] ?? '-' }}</td>
          @endforeach
        </tr>
      @endforeach
    </tbody>
  </table>

  <div style="margin-top:10px;font-size:12px;">
    P=حضور | L=تأخير | A=غياب | O=عطلة | V=إجازة | S=مجدول
  </div>
</body>
</html>
