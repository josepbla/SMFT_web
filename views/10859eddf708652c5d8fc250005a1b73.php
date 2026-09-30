<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($page->titulo); ?> | Colegio Santa Maria</title>
    <style>body{margin:0;color:#30465e;background:#f5f8fb;font-family:Arial,sans-serif}header,main{width:min(960px,90vw);margin:auto}header{padding:28px 0}a{color:#126bc1;text-decoration:none}main{padding:45px 0 80px;background:#fff}article{max-width:780px;margin:auto;padding:0 40px}h1{color:#173b67;font:700 clamp(34px,5vw,58px) Georgia,serif} .content{font-size:17px;line-height:1.85;white-space:pre-line} .image{width:100%;max-height:420px;object-fit:cover;margin:10px 0 30px}</style>
</head>
<body><header><a href="<?php echo e(route('home')); ?>">← Volver al inicio</a></header><main><article><h1><?php echo e($page->titulo); ?></h1><?php if($page->imagen): ?><img class="image" src="<?php echo e($page->imagen); ?>" alt="<?php echo e($page->titulo); ?>"><?php endif; ?><div class="content"><?php echo nl2br(e($page->contenido ?? '')); ?></div></article></main></body>
</html>
<?php /**PATH C:\Users\Dnilson\OneDrive\Desktop\Proyecto - copia\resources\views\public\page.blade.php ENDPATH**/ ?>