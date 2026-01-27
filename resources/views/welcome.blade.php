<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>نماء أكاديمي</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=cairo:400,600,700&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            /* (CSS الكبير الموجود عندك كما هو — لا حاجة لتعديله هنا) */
        </style>
    @endif

    <style>
        body{
            font-family: "Cairo","Instrument Sans",ui-sans-serif,system-ui,sans-serif;
            background:
                radial-gradient(900px 500px at 20% 20%, rgba(16,185,129,.08), transparent 60%),
                radial-gradient(900px 500px at 80% 35%, rgba(59,130,246,.09), transparent 55%),
                linear-gradient(180deg, #ffffff 0%, #fbfdfc 100%);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-10 text-[#0f172a]">

    <main class="w-full max-w-3xl">
        <div class="bg-white rounded-2xl border border-[#eef2f7]
                    shadow-[0px_0px_1px_rgba(0,0,0,0.05),0px_30px_90px_rgba(0,0,0,0.10)]
                    px-6 py-10 sm:px-10 sm:py-12 lg:px-14 lg:py-14">

            <!-- Logo (واضح على الموبايل) -->
            <div class="flex justify-center mb-8 sm:mb-10">
                <img
                    src="{{ asset('images/namaa-logo.png') }}"
                    alt="شعار نماء أكاديمي"
                    class="h-28 sm:h-32 lg:h-40 w-auto"
                >
            </div>

            <div class="text-center">
                <h1 class="font-extrabold text-[24px] sm:text-[30px] lg:text-[38px] leading-tight text-[#0b1220] mb-4 sm:mb-5">
                    أهلاً وسهلاً بك في نظام نماء أكاديمي
                </h1>

                <p class="mx-auto max-w-2xl text-[#0f172a] text-[14px] sm:text-[16px] lg:text-[18px]
                          leading-relaxed sm:leading-loose font-semibold mb-8 sm:mb-10"
                   style="opacity:.92">
                    النظام المتكامل (ERP) لإدارة العمليات الأكاديمية والإدارية بكفاءة واحترافية.
                    <br class="hidden sm:block">
                    سجّل دخولك للبدء والوصول إلى لوحات التحكم والأقسام والصلاحيات المخصصة لك.
                </p>

                <div class="flex items-center justify-center">
                    <a
                        href="{{ route('login') }}"
                        class="inline-flex items-center justify-center sm:w-auto min-w-[330px] sm:min-w-[360px]
                               px-8 sm:px-10 py-3.5 rounded-xl text-[15px] sm:text-[16px] lg:text-[17px]
                               font-bold text-white transition-all"
                        style="
                            padding:2%;
                            background: linear-gradient(90deg, #0ea5e9 0%, #10b981 100%);
                            box-shadow: 0 14px 35px rgba(16,185,129,.22), 0 12px 25px rgba(14,165,233,.18);
                        "
                        onmouseover="this.style.filter='brightness(0.95)'"
                        onmouseout="this.style.filter='none'"
                    >
                        تسجيل الدخول
                    </a>
                </div>

                <p class="mt-8 sm:mt-10 text-[12px] sm:text-[13px] text-[#334155] font-semibold" style="opacity:.88">
                    © {{ date('Y') }} نماء أكاديمي — جميع الحقوق محفوظة
                </p>
            </div>
        </div>
    </main>

</body>
</html>
