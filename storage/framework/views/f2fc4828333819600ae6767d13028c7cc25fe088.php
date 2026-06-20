<?php if (isset($component)) { $__componentOriginalc3251b308c33b100480ddc8862d4f9c79f6df015 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\GuestLayout::class, []); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.auth.shell','data' => []]); ?>
<?php $component->withName('auth.shell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
        <div class="auth-card" role="main" aria-label="Inicio de sesion">
            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.auth.visual','data' => []]); ?>
<?php $component->withName('auth.visual'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

            <section class="auth-panel" aria-label="Formulario de inicio de sesion">
                <div class="auth-panel-card">
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.auth.panel-header','data' => ['eyebrow' => 'Bienvenido','title' => 'Iniciar sesion','copy' => 'Usa las credenciales entregadas por el equipo acad&eacute;mico.','mobileLogo' => true]]); ?>
<?php $component->withName('auth.panel-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['eyebrow' => 'Bienvenido','title' => 'Iniciar sesion','copy' => 'Usa las credenciales entregadas por el equipo acad&eacute;mico.','mobile-logo' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'jetstream::components.validation-errors','data' => ['class' => 'auth-alert-gap eus-alert eus-alert-danger','role' => 'alert']]); ?>
<?php $component->withName('jet-validation-errors'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['class' => 'auth-alert-gap eus-alert eus-alert-danger','role' => 'alert']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

                    <?php if(session('status')): ?>
                        <div class="auth-alert-gap eus-alert eus-alert-success" role="status">
                            <?php echo e(session('status')); ?>

                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('login')); ?>" class="auth-login-form" novalidate aria-describedby="login-help">
                        <?php echo csrf_field(); ?>

                        <div>
                            <label for="username" class="eus-label">Nombre de usuario</label>
                            <input
                                id="username"
                                class="eus-input"
                                type="text"
                                name="username"
                                value="<?php echo e(old('username')); ?>"
                                required
                                autofocus
                                autocomplete="username"
                                aria-required="true"
                            >
                        </div>

                        <div>
                            <label for="password" class="eus-label">Contrase&ntilde;a</label>
                            <input
                                id="password"
                                class="eus-input"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                aria-required="true"
                            >
                            <?php if(Route::has('password.request')): ?>
                                <a class="auth-forgot-link" href="<?php echo e(route('password.request')); ?>">
                                    &iquest;Olvidaste tu contrase&ntilde;a?
                                </a>
                            <?php endif; ?>
                        </div>

                        <label for="remember_me" class="eus-checkbox">
                            <input id="remember_me" type="checkbox" name="remember">
                            <span>Recordarme</span>
                        </label>

                        <button type="submit" class="eus-btn eus-btn-primary eus-btn-lg eus-btn-full">
                            Ingresar
                        </button>
                    </form>

                    <p id="login-help" class="auth-help">
                        Si tienes inconvenientes para ingresar, escribe a
                        <a class="auth-help-link" href="mailto:soporte@semilladigital.com">soporte@semilladigital.com</a>.
                    </p>
                </div>
            </section>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc3251b308c33b100480ddc8862d4f9c79f6df015)): ?>
<?php $component = $__componentOriginalc3251b308c33b100480ddc8862d4f9c79f6df015; ?>
<?php unset($__componentOriginalc3251b308c33b100480ddc8862d4f9c79f6df015); ?>
<?php endif; ?>
<?php /**PATH C:\Users\t'chala\proyectos\plataforma-laravel\resources\views/auth/login.blade.php ENDPATH**/ ?>