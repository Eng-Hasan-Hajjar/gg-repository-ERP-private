<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style>
        @page {
            margin: 80px 40px 60px 40px;
            size: A4 portrait;
        }

        @font-face {
            font-family: 'Amiri';
            src: url(data:font/truetype;charset=utf-8;base64,[BASE64_STRING_HERE]) format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Amiri';
            src: url(data:font/truetype;charset=utf-8;base64,[BASE64_BOLD_STRING_HERE]) format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        body {
            font-family: 'Amiri', 'Segoe UI', Tahoma, sans-serif !important;
            direction: rtl;
            text-align: right;
            unicode-bidi: embed;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            direction: rtl;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px 6px;
            font-size: 11pt;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        header {
            position: fixed;
            top: -60px;
            left: 0;
            right: 0;
            height: 50px;
            border-bottom: 2px solid #3490dc;
            font-size: 11pt;
        }

        footer {
            position: fixed;
            bottom: -40px;
            left: 0;
            right: 0;
            height: 30px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 9pt;
            color: #666;
        }

        .page-number:before {
            content: "صفحة " counter(page) " من " counter(pages);
        }

        h2 {
            text-align: center;
            margin: 0 0 20px;
            color: #1e3a8a;
        }

        .info p {
            margin: 4px 0;
        }

        .type-in {
            color: #15803d;
            font-weight: bold;
        }

        .type-out {
            color: #b91c1c;
            font-weight: bold;
        }

        .status-posted {
            color: #1d4ed8;
        }

        .status-draft {
            color: #6b7280;
        }
    </style>
</head>

<body>

    <header>
        <table style="width:100%; border:none;">
            <tr>
                <td style="text-align:right"><strong>نظام نماء أكاديمي</strong> — حركات الصندوق</td>
                <td style="text-align:left">تاريخ الإنشاء: {{ now()->format('Y-m-d H:i') }}</td>
            </tr>
        </table>
    </header>

    <footer>
        <div class="page-number"></div>
    </footer>

    <h2>حركات الصندوق: {{ $cashbox->name }}</h2>

    <div class="info">
        <p><strong>الكود:</strong> {{ $cashbox->code }}</p>
        <p><strong>العملة:</strong> {{ $cashbox->currency }}</p>
        <p><strong>إجمالي المقبوض (مرحّل):</strong> {{ number_format($postedIn ?? 0, 2) }}</p>
        <p><strong>إجمالي المدفوع (مرحّل):</strong> {{ number_format($postedOut ?? 0, 2) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>التاريخ</th>
                <th>الطالب</th>
                <th>الدبلومة</th>
                <th>النوع</th>
                <th>المبلغ</th>
                <th>التصنيف</th>
                <th>المرجع</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $t)
                <tr>
                    <td>{{ $t->id }}</td>
                    <td>{{ $t->trx_date?->format('Y-m-d') ?? '—' }}</td>
                    <td>{{ optional(optional($t->account)->accountable)->full_name ?? '—' }}</td>
                    <td>{{ optional($t->diploma)->name ?? '—' }}</td>
                    <td class="type-{{ $t->type }}">
                        {{ $t->type === 'in' ? 'مقبوض' : 'مدفوع' }}
                    </td>
                    <td>{{ number_format($t->amount, 2) }} {{ $t->currency }}</td>
                    <td>{{ $t->category ?? '—' }}</td>
                    <td>{{ $t->reference ?? '—' }}</td>
                    <td class="status-{{ $t->status }}">
                        {{ $t->status === 'posted' ? 'مُرحّل' : 'معلّق' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center; padding:30px;">لا توجد حركات مطابقة</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>