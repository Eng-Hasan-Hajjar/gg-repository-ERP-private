<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير الدوام</title>

    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            direction: rtl;
        }

        h2{
            margin: 0;
            font-size: 18px;
        }

        .muted{
            color: #6b7280;
            font-size: 11px;
        }

        .header{
            margin-bottom: 15px;
            border-bottom: 2px solid #111827;
            padding-bottom: 8px;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td{
            border: 1px solid #d1d5db;
            padding: 6px;
            text-align: center;
            vertical-align: middle;
        }

        th{
            background: #f3f4f6;
            font-weight: bold;
        }

        .emp{
            text-align: right;
            font-weight: bold;
        }

        .badge{
            padding: 2px 6px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 11px;
            display: inline-block;
        }

        .bg-success{ background:#dcfce7; color:#166534; }
        .bg-dark{ background:#e5e7eb; color:#111827; }
        .bg-warning{ background:#fef3c7; color:#92400e; }
        .bg-danger{ background:#fee2e2; color:#991b1b; }

        .footer{
            margin-top: 20px;
            font-size: 10px;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- ================= Header ================= --}}
    <div class="header">
        <h2>تقرير الدوام</h2>
        <div class="muted">
            الفترة:
            {{ $from }} →
            {{ $to }}
            @if($branch)
                | الفرع: {{ $branch->name }}
            @endif
        </div>
    </div>

    {{-- ================= Table ================= --}}
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>الموظف</th>
                <th>الفرع</th>
                <th>أيام حضور</th>
                <th>أيام غياب</th>
                <th>أيام إجازة</th>
                <th>تأخير (د)</th>
                <th>ساعات عمل</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $index => $r)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="emp">{{ $r->employee->full_name ?? '-' }}</td>
                    <td>{{ $r->employee->branch->name ?? '-' }}</td>

                    <td>
                        <span class="badge bg-success">
                            {{ (int)$r->present_days }}
                        </span>
                    </td>

                    <td>
                        <span class="badge bg-dark">
                            {{ (int)$r->absent_days }}
                        </span>
                    </td>

                    <td>
                        <span class="badge bg-warning">
                            {{ (int)$r->leave_days }}
                        </span>
                    </td>

                    <td>
                        <span class="badge bg-danger">
                            {{ (int)$r->late_minutes }}
                        </span>
                    </td>

                    <td>
                        {{ round(((int)$r->worked_minutes)/60, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">لا يوجد بيانات</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ================= Footer ================= --}}
    <div class="footer">
        تم إنشاء التقرير بتاريخ {{ now()->format('Y-m-d H:i') }}
    </div>

</body>
</html>
