<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | Colegio Santa Maria</title>
    <style>
        :root { --ink:#173b67; --blue:#126bc1; --paper:#f4f7fa; --white:#fff; --muted:#6b7c8d; --line:#dce6ee; }
        * { box-sizing:border-box; } body { min-height:100vh; display:grid; place-items:center; margin:0; color:#30465e; background:linear-gradient(135deg,#eef7fc 0%,#f8f5ed 100%); font-family:Arial,sans-serif; }
        .panel { width:min(430px,90vw); padding:38px; background:var(--white); border-top:4px solid var(--blue); box-shadow:0 18px 45px rgba(29,65,94,.12); }
        .eyebrow { color:var(--blue); font-size:11px; font-weight:700; letter-spacing:.18em; text-transform:uppercase; } h1 { margin:12px 0 9px; color:var(--ink); font:700 34px Georgia,serif; } .intro { margin:0 0 27px; color:var(--muted); font-size:14px; line-height:1.6; }
        label { display:block; margin:18px 0 7px; color:var(--ink); font-size:13px; font-weight:700; } input { width:100%; padding:13px 14px; color:var(--ink); border:1px solid var(--line); font-size:14px; } input:focus { outline:2px solid #b8d7ec; border-color:var(--blue); } button { width:100%; margin-top:25px; padding:14px; border:0; color:var(--white); background:var(--blue); font-weight:700; cursor:pointer; } .error,.success { margin-bottom:17px; padding:12px 14px; font-size:13px; line-height:1.5; } .error { color:#9b3030; background:#fdeaea; } .success { color:#22613d; background:#e7f7ed; } .back { display:block; margin-top:22px; color:var(--blue); font-size:13px; text-align:center; text-decoration:none; }
    </style>
</head>
<body>
    <main class="panel">
        <div class="eyebrow">Colegio Santa Maria</div>
        <h1>Iniciar sesión</h1>
        <p class="intro">Accede al centro de gestión para administrar el contenido del colegio.</p>
        <?php if(session('success')): ?> <div class="success"><?php echo e(session('success')); ?></div> <?php endif; ?>
        <?php if($errors->any()): ?> <div class="error"><?php echo e($errors->first()); ?></div> <?php endif; ?>
        <form action="<?php echo e(route('auth.login.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <label for="email">Correo electrónico</label>
            <input id="email" name="email" type="email" value="<?php echo e(old('email')); ?>" autocomplete="email" required autofocus>
            <label for="password">Contraseña</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
            <button type="submit">Entrar al panel</button>
        </form>
        <a class="back" href="<?php echo e(route('home')); ?>">← Volver al sitio público</a>
    </main>
</body>
</html><?php /**PATH C:\Users\Dnilson\OneDrive\Desktop\Proyecto - copia\resources\views/auth/login.blade.php ENDPATH**/ ?>