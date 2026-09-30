<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title); ?> | Colegio Santa Maria</title>
    <style>
        :root{--ink:#173b67;--blue:#126bc1;--paper:#f5f8fb;--line:#dfe8ef;--muted:#68798a}*{box-sizing:border-box}body{margin:0;color:#30465e;background:var(--paper);font-family:Arial,sans-serif}header,main{width:min(1120px,92vw);margin:auto}header{padding:30px 0}a{color:var(--blue);text-decoration:none}.eyebrow{color:var(--blue);font-size:11px;font-weight:700;letter-spacing:.16em;text-transform:uppercase}h1{margin:10px 0 0;color:var(--ink);font:700 46px Georgia,serif}.grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}.card{padding:23px;background:#fff;border:1px solid var(--line)}.card h2{margin:9px 0;color:var(--ink);font:700 23px Georgia,serif}.card p{color:var(--muted);line-height:1.65}.date{color:var(--blue);font-size:12px;font-weight:700}.button{display:inline-block;margin-top:12px;padding:11px 14px;color:#fff;background:var(--blue);font-size:12px;font-weight:700}.photo{width:100%;height:190px;object-fit:cover;margin-bottom:12px}.empty{padding:35px;background:#fff;color:var(--muted)}@media(max-width:760px){.grid{grid-template-columns:1fr}h1{font-size:36px}}
    </style>
</head>
<body>
<header><a href="<?php echo e(route('home')); ?>">← Volver al inicio</a><div class="eyebrow">Colegio Santa Maria</div><h1><?php echo e($title); ?></h1></header>
<main><div class="grid">
    <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php if($type === 'pages'): ?>
            <article class="card"><div class="eyebrow">Información</div><h2><?php echo e($record->titulo); ?></h2><p><?php echo e(\Illuminate\Support\Str::limit(strip_tags($record->contenido ?? ''), 150)); ?></p><a class="button" href="<?php echo e(route('public.page', $record->slug)); ?>">Leer página ↗</a></article>
        <?php elseif($type === 'announcements'): ?>
            <article class="card"><span class="date"><?php echo e(\Illuminate\Support\Carbon::parse($record->fecha)->format('d/m/Y')); ?></span><h2><?php echo e($record->titulo); ?></h2><p><?php echo e($record->contenido); ?></p><?php if($record->archivo): ?><a class="button" href="<?php echo e($record->archivo); ?>" target="_blank" rel="noopener">Ver archivo ↗</a><?php endif; ?></article>
        <?php elseif($type === 'documents'): ?>
            <article class="card"><div class="eyebrow">Documento</div><h2><?php echo e($record->titulo); ?></h2><p><?php echo e($record->categoria ?: 'Documento institucional'); ?></p><a class="button" href="<?php echo e($record->archivo); ?>" target="_blank" rel="noopener">Abrir documento ↗</a></article>
        <?php elseif($type === 'staff'): ?>
            <article class="card"><?php if($record->foto): ?><img class="photo" src="<?php echo e($record->foto); ?>" alt="<?php echo e($record->nombres); ?> <?php echo e($record->apellidos); ?>"><?php endif; ?><div class="eyebrow"><?php echo e(ucfirst($record->area)); ?></div><h2><?php echo e($record->nombres); ?> <?php echo e($record->apellidos); ?></h2><p><?php echo e($record->cargo ?: $record->especialidad ?: ($record->nivel_nombre ?? 'Equipo educativo')); ?></p></article>
        <?php elseif($type === 'links'): ?>
            <article class="card"><div class="eyebrow"><?php echo e(str_replace('_', ' ', $record->tipo)); ?></div><h2><?php echo e($record->nombre); ?></h2><a class="button" href="<?php echo e($record->url); ?>" target="_blank" rel="noopener">Visitar enlace ↗</a></article>
        <?php else: ?>
            <article class="card"><?php if($record->portada): ?><img class="photo" src="<?php echo e($record->portada); ?>" alt="<?php echo e($record->titulo); ?>"><?php elseif($record->items->firstWhere('tipo', 'imagen')): ?><img class="photo" src="<?php echo e($record->items->firstWhere('tipo', 'imagen')->ruta); ?>" alt="<?php echo e($record->titulo); ?>"><?php endif; ?><div class="eyebrow">Galería</div><h2><?php echo e($record->titulo); ?></h2><p><?php echo e($record->descripcion ?: 'Momentos de nuestra comunidad educativa.'); ?></p><?php if($record->items->isNotEmpty()): ?><p><?php echo e($record->items->count()); ?> elementos publicados.</p><?php endif; ?></article>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="empty">No hay contenido publicado en este momento.</p>
    <?php endif; ?>
</div></main>
</body>
</html>
<?php /**PATH C:\Users\Dnilson\OneDrive\Desktop\Proyecto - copia\resources\views\public\directory.blade.php ENDPATH**/ ?>