<?php if (isset($component)) { $__componentOriginal69dc84650370d1d4dc1b42d016d7226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b = $attributes; } ?>
<?php $component = App\View\Components\GuestLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\GuestLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div dir="rtl" class="w-full">

        <div class="bg-white rounded-2xl border border-[#eef2f7]
                    shadow-[0px_0px_1px_rgba(0,0,0,0.05),0px_30px_90px_rgba(0,0,0,0.10)]
                    px-6 py-8 sm:px-10 sm:py-10">

            <div class="flex justify-center mb-6">
                <img src="<?php echo e(asset('images/namaa-logo.png')); ?>" alt="شعار نماء أكاديمي" class="h-16 sm:h-18 w-auto">
            </div>

            <h1 class="text-center font-extrabold text-[20px] sm:text-[24px] text-[#0b1220] mb-4">
                تفعيل البريد الإلكتروني
            </h1>

            <p class="text-center text-sm text-slate-600 leading-relaxed mb-6">
                تم إنشاء الحساب بنجاح. الرجاء تفعيل بريدك الإلكتروني عبر الرابط الذي أرسلناه لك.
                إذا لم يصلك البريد، يمكنك إرسال رابط جديد.
            </p>

            <?php if(session('status') == 'verification-link-sent'): ?>
                <div class="mb-4 text-center font-semibold text-sm text-emerald-700 bg-emerald-50 border border-emerald-100 rounded-xl p-3">
                    تم إرسال رابط تفعيل جديد إلى بريدك الإلكتروني.
                </div>
            <?php endif; ?>

            <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-3">
                <form method="POST" action="<?php echo e(route('verification.send')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                            class="min-w-[220px] w-full sm:w-auto flex items-center justify-center
                                   px-10 py-3 rounded-xl text-white font-bold transition-all"
                            style="
                                background: linear-gradient(90deg, #0ea5e9 0%, #10b981 100%);
                                box-shadow: 0 14px 35px rgba(16,185,129,.22), 0 12px 25px rgba(14,165,233,.18);
                            "
                            onmouseover="this.style.filter='brightness(0.95)'"
                            onmouseout="this.style.filter='none'">
                        إعادة إرسال رابط التفعيل
                    </button>
                </form>

                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                            class="text-sm font-semibold text-slate-600 hover:text-slate-900 underline underline-offset-4">
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $attributes = $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $component = $__componentOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views\auth\verify-email.blade.php ENDPATH**/ ?>