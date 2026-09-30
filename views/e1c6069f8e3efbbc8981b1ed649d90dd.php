<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($article->titulo); ?> | Colegio Santa Maria</title>
    <style>body{margin:0;color:#30465e;background:#f5f8fb;font-family:Arial,sans-serif}header,main{width:min(960px,90vw);margin:auto}header{padding:28px 0}a{color:#126bc1;text-decoration:none}main{padding:45px 0 80px;background:#fff}article{max-width:780px;margin:auto;padding:0 40px}small{color:#126bc1;font-weight:700;text-transform:uppercase}h1{color:#173b67;font:700 clamp(34px,5vw,58px) Georgia,serif}.summary{font-size:20px;color:#6b7c8d;line-height:1.6}.content{font-size:17px;line-height:1.85;white-space:pre-line}.image{width:100%;max-height:460px;object-fit:cover;margin:25px 0}</style>
</head>
<body><header><a href="<?php echo e(route('public.news')); ?>">← Volver a noticias</a></header><main><article><small><?php echo e($article->categoria_nombre ?? 'Colegio'); ?></small><h1><?php echo e($article->titulo); ?></h1>
<?php if($article->imagen): ?>
    <img class="image" src="<?php echo e($article->imagen); ?>" alt="<?php echo e($article->titulo); ?>">
<?php endif; ?>
<?php if($article->resumen): ?>
    <p class="summary"><?php echo e($article->resumen); ?></p>
<?php endif; ?>
<div class="content"><?php echo nl2br(e($article->contenido)); ?></div></article></main></body>
</html>
<?php /**PATH C:\Users\Dnilson\OneDrive\Desktop\Proyecto - copia\resources\views\public\news-show.blade.php ENDPATH**/ ?>