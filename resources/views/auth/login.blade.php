<x-guest-layout>
    <div dir="rtl" class="w-full">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Card -->
        <div class="bg-white rounded-2xl border border-[#eef2f7]
                    shadow-[0px_0px_1px_rgba(0,0,0,0.05),0px_30px_90px_rgba(0,0,0,0.10)]
                    px-6 py-8 sm:px-10 sm:py-10">

            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/namaa-logo.png') }}"
                     alt="شعار نماء أكاديمي"
                     class="h-16 sm:h-18 w-auto">
            </div>

            <h1 class="text-center font-extrabold text-[22px] sm:text-[26px] text-[#0b1220] mb-6">
                تسجيل الدخول
            </h1>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('البريد الإلكتروني')" class="text-right" />
                    <x-text-input id="email"
                                  class="block mt-2 w-full rounded-xl border-gray-200 focus:border-emerald-400 focus:ring-emerald-200"
                                  type="email"
                                  name="email"
                                  :value="old('email')"
                                  required
                                  autofocus
                                  autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('كلمة المرور')" class="text-right" />
                    <x-text-input id="password"
                                  class="block mt-2 w-full rounded-xl border-gray-200 focus:border-emerald-400 focus:ring-emerald-200"
                                  type="password"
                                  name="password"
                                  required
                                  autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember -->
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center gap-2">
                        <input id="remember_me"
                               type="checkbox"
                               class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500"
                               name="remember">
                        <span class="text-sm text-gray-700">تذكرني</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm font-semibold text-slate-600 hover:text-slate-900 underline underline-offset-4"
                           href="{{ route('password.request') }}">
                            نسيت كلمة المرور؟
                        </a>
                    @endif
                </div>

                <!-- Submit -->
                <div class="pt-2">
                    <button type="submit"
                            class="w-full sm:w-auto min-w-[220px] mx-auto flex items-center justify-center
                                   px-10 py-3 rounded-xl text-white font-bold transition-all"
                            style="
                                background: linear-gradient(90deg, #0ea5e9 0%, #10b981 100%);
                                box-shadow: 0 14px 35px rgba(16,185,129,.22), 0 12px 25px rgba(14,165,233,.18);
                            "
                            onmouseover="this.style.filter='brightness(0.95)'"
                            onmouseout="this.style.filter='none'">
                        تسجيل الدخول
                    </button>

                    <div class="text-center mt-4">
                        <a href="{{ route('register') }}"
                           class="text-sm font-semibold text-slate-600 hover:text-slate-900 underline underline-offset-4">
                            ليس لديك حساب؟ إنشاء حساب جديد
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <p class="text-center mt-6 text-xs text-slate-500 font-semibold">
            © {{ date('Y') }} نماء أكاديمي — جميع الحقوق محفوظة
        </p>
    </div>
</x-guest-layout>
