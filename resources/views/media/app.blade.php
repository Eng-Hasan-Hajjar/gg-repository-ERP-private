<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="icon" href="{{ asset('images/namaa-logo.png') }}">
    <title>@yield('title', 'طلب ميديا — نماء')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        @font-face {
            font-family: 'Hacen Tunisia';
            src: url('{{ asset('fonts/hacen-tunisia/Hacen-Tunisia-Bd.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        :root {
            --namaa-blue: #0ea5e9;
            --namaa-green: #10b981;
            --namaa-ink: #0b1220;
            --namaa-muted: #64748b;
        }

        body {
            font-family: 'Hacen Tunisia', sans-serif !important;
            background:
                radial-gradient(1000px 600px at 15% 10%, rgba(16, 185, 129, .10), transparent 60%),
                radial-gradient(1000px 600px at 85% 15%, rgba(14, 165, 233, .10), transparent 55%),
                linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
            color: var(--namaa-ink);
        }

        input, select, textarea, button, .card, .form-control, .form-check-label {
            font-family: 'Hacen Tunisia', sans-serif !important;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Hacen Tunisia', sans-serif;
            font-weight: 900;
        }

        .navbar.namaa-nav {
            background: linear-gradient(90deg, rgba(14, 165, 233, .96) 0%, rgba(16, 185, 129, .96) 100%) !important;
            box-shadow: 0 12px 30px rgba(2, 6, 23, .10);
        }

        .navbar .navbar-brand {
            font-weight: 900;
            letter-spacing: .3px;
        }

        .btn-namaa {
            border: 0;
            font-weight: 900;
            border-radius: 14px;
            padding: 10px 14px;
            background: linear-gradient(90deg, var(--namaa-blue) 0%, var(--namaa-green) 100%);
            color: #fff !important;
            box-shadow: 0 18px 35px rgba(16, 185, 129, .18), 0 16px 26px rgba(14, 165, 233, .14);
        }

        .btn-namaa:hover {
            filter: brightness(.95);
        }

        .card {
            border: 1px solid rgba(226, 232, 240, .9);
            border-radius: 18px;
            background: rgba(255, 255, 255, .86);
            box-shadow: 0 14px 40px rgba(2, 6, 23, .08);
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark namaa-nav">
        <div class="container py-1">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <i class="bi bi-grid-fill"></i>
                نماء — طلب ميديا
            </a>
        </div>
    </nav>

    <main class="container py-4">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

        <div class="text-center mt-5 small text-secondary fw-semibold">
            © {{ date('Y') }} نماء أكاديمي — جميع الحقوق محفوظة
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>