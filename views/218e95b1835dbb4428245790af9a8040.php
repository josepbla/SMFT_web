<nav aria-label="Navegación principal">
    <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php ($menuUrl = match ($menu->slug) {
            'inicio' => route('home'),
            'servicios' => route('services'),
            'noticias' => route('public.news'),
            'contacto' => route('contact'),
            'institucional' => route('public.pages'),
            default => route('public.pages'),
        }); ?>
        <a class="<?php echo e($menu->slug === ($active ?? '') ? 'active' : ''); ?> <?php echo e($menu->slug === 'contacto' ? 'nav-cta' : ''); ?>" href="<?php echo e($menuUrl); ?>"><?php echo e($menu->titulo); ?></a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <a class="<?php echo e(($active ?? '') === 'pastoral' ? 'active' : ''); ?>" href="<?php echo e(route('pastoral')); ?>">Pastoral</a>
    <a href="<?php echo e(route('auth.login')); ?>" class="nav-login">Iniciar sesión</a>
</nav>
<?php /**PATH C:\Users\Dnilson\OneDrive\Desktop\Proyecto - copia\resources\views\partials\public-nav.blade.php ENDPATH**/ ?>