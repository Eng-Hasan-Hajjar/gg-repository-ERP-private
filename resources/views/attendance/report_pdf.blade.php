<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">

    <style>
        @page {
            margin: 90px 40px 70px 40px;
        }

        /* تحميل الخط العربي — أهم سطر */
        @font-face {
            font-family: 'Hacen';
            src: url("file:///{{ str_replace('\\', '/', public_path('fonts/hacen-tunisia/Hacen-Tunisia-Bd.ttf')) }}") format("truetype");
        }

        .logo-img {
            height: 55px;
        }

        .header-table {
            width: 50%;
        }

   
.page-break{
    page-break-after:always;
}

header {
    position: fixed;
    top: -85px;
    left: 0;
    right: 0;
    height: 70px;
}


        body {
            font-family: 'Hacen';
            direction: rtl;
            text-align: right;
            font-size: 13px;
            color: #1f2937;
        }

      

        footer {
            position: fixed;
            bottom: -50px;
            left: 0;
            right: 0;
            height: 40px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
        }

        .page-number:before {
            content: "صفحة " counter(page);
        }

        h2 {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #e5e7eb;
            padding: 7px;
        }

        th {
            background: #f1f5f9;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background: #fafafa;
        }

        .badge {
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .warning {
            background: #fef3c7;
            color: #92400e;
        }

        .danger {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>

<body>


<header>
    <table style="width:100%">
        <tr>
            <td style="text-align:right">
                <img
                    src="file:///{{ str_replace('\\','/', public_path('images/company-logo.png')) }}"
                    style="height:60px">
            </td>

            <td style="text-align:left;font-size:11px;color:#6b7280">
                <strong>نظام نماء الأكاديمي</strong><br>
                تم إنشاء التقرير<br>
                {{ now()->format('Y-m-d H:i') }}
            </td>
        </tr>
    </table>
</header>

    <footer>
        <div class="page-number"></div>
    </footer>

    <main>

        <h2>تقرير الدوام</h2>

        <div style="font-size:11px;color:#6b7280;">
            الفترة: {{ $from }} → {{ $to }}
        </div>

        <table>
            <thead>
                <tr>
                    <th>الموظف</th>
                    <th>أيام حضور</th>
                    <th>غياب</th>
                    <th>إجازة</th>
                    <th>تأخير</th>
                    <th>ساعات العمل</th>
                </tr>
            </thead>

            <tbody>
                @foreach($rows as $r)
                    <tr>
                        <td>{{ $r->employee->full_name ?? '-' }}</td>

                        <td><span class="badge success">{{ (int) $r->present_days }}</span></td>
                        <td>{{ (int) $r->absent_days }}</td>
                        <td>{{ (int) $r->leave_days }}</td>

                        <td><span class="badge danger">{{ (int) $r->late_minutes }}</span></td>

                        <td>{{ round(((int) $r->worked_minutes) / 60, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </main>
</body>

</html>