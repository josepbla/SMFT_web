<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($record ? 'Editar' : 'Nuevo'); ?> <?php echo e($definition['title']); ?> | Administración</title>
    <style>
        :root { --ink:#173b67; --blue:#126bc1; --paper:#f4f7fa; --white:#fff; --muted:#6b7c8d; --line:#dfe8ef; --danger:#a23b3b; }
        * { box-sizing:border-box; } body { margin:0; color:#30465e; background:var(--paper); font-family:Arial,sans-serif; } a { color:inherit; text-decoration:none; }
        .shell { width:min(980px,92vw); margin:0 auto; padding:35px 0 60px; } .top { display:flex; justify-content:space-between; align-items:flex-start; gap:20px; margin-bottom:25px; }
        .eyebrow { color:var(--blue); font-size:11px; font-weight:700; letter-spacing:.18em; text-transform:uppercase; } h1 { margin:9px 0 7px; color:var(--ink); font:700 34px Georgia,serif; } .sub { margin:0; color:var(--muted); font-size:14px; }
        .button { display:inline-block; padding:12px 16px; color:var(--blue); background:#fff; border:1px solid #cbdbe8; font-size:13px; font-weight:700; }
        form.panel { padding:25px; background:#fff; border:1px solid var(--line); } .fields { display:grid; grid-template-columns:repeat(2,1fr); gap:18px; } .field.full { grid-column:span 2; } label { display:block; margin-bottom:7px; color:var(--ink); font-size:13px; font-weight:700; } input,select,textarea { width:100%; padding:12px 13px; color:#30465e; border:1px solid #ccdbe5; background:#fff; font:inherit; font-size:14px; } textarea { min-height:130px; resize:vertical; } input:focus,select:focus,textarea:focus { outline:2px solid #bdd9eb; border-color:var(--blue); } .check { display:flex; align-items:center; gap:9px; min-height:43px; } .check input { width:auto; } .error { margin-bottom:18px; padding:13px 16px; color:#9b3030; background:#fdeaea; font-size:13px; } .actions { display:flex; justify-content:flex-end; gap:10px; margin-top:25px; padding-top:20px; border-top:1px solid var(--line); } button { padding:13px 20px; border:0; color:#fff; background:var(--blue); font-weight:700; cursor:pointer; }
        @media(max-width:650px) { .top { display:block; } .fields { grid-template-columns:1fr; } .field.full { grid-column:span 1; } .actions { display:block; } .actions button,.actions a { display:block; width:100%; margin-top:9px; text-align:center; } }
    </style>
</head>
<body>
    <main class="shell">
        <header class="top"><div><div class="eyebrow">Centro de gestión · <?php echo e($definition['title']); ?></div><h1><?php echo e($record ? 'Editar registro' : 'Nuevo registro'); ?></h1><p class="sub">Los cambios se guardan directamente en la base de datos.</p></div><a class="button" href="<?php echo e(route('admin.module.index', $definition['key'])); ?>">← Volver al listado</a></header>
        <?php if($errors->any()): ?> <div class="error"><?php echo e($errors->first()); ?></div> <?php endif; ?>
        <form class="panel" method="POST" action="<?php echo e($record ? route('admin.module.update', [$definition['key'], $record->{$definition['primary']}]) : route('admin.module.store', $definition['key'])); ?>">
            <?php echo csrf_field(); ?>
            <?php if($record): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
            <div class="fields">
                <?php $__currentLoopData = $definition['fields']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php ($value = $field['type'] === 'password' ? old($name, '') : old($name, $record->{$name} ?? ($field['type'] === 'checkbox' ? false : ''))); ?>
                    <div class="field <?php echo e(in_array($field['type'], ['textarea']) ? 'full' : ''); ?>">
                        <?php if($field['type'] === 'checkbox'): ?>
                            <label class="check"><input type="checkbox" name="<?php echo e($name); ?>" value="1" <?php if($value): echo 'checked'; endif; ?>> <?php echo e(ucfirst(str_replace('_', ' ', $name))); ?></label>
                        <?php else: ?>
                            <label for="<?php echo e($name); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $name))); ?></label>
                            <?php if($field['type'] === 'textarea'): ?>
                                <textarea id="<?php echo e($name); ?>" name="<?php echo e($name); ?>" <?php if(($field['readonly'] ?? false) && $record): echo 'disabled'; endif; ?>><?php echo e($value); ?></textarea>
                            <?php elseif($field['type'] === 'select'): ?>
                                <select id="<?php echo e($name); ?>" name="<?php echo e($name); ?>" <?php if(($field['readonly'] ?? false) && $record): echo 'disabled'; endif; ?>>
                                    <option value="">Seleccionar...</option>
                                    <?php $__currentLoopData = ($field['options'] ?? $options[$name] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionValue => $optionLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($optionValue); ?>" <?php if((string) $value === (string) $optionValue): echo 'selected'; endif; ?>><?php echo e($optionLabel); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            <?php else: ?>
                                <input id="<?php echo e($name); ?>" name="<?php echo e($name); ?>" type="<?php echo e($field['type']); ?>" value="<?php echo e($value); ?>" <?php if(in_array('required', $field['rules'] ?? [])): echo 'required'; endif; ?> <?php if(($field['readonly'] ?? false) && $record): echo 'disabled'; endif; ?>>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="actions"><a class="button" href="<?php echo e(route('admin.module.index', $definition['key'])); ?>">Cancelar</a><button type="submit"><?php echo e($record ? 'Guardar cambios' : 'Crear registro'); ?></button></div>
        </form>
    </main>
</body>
</html>
<?php /**PATH C:\Users\Dnilson\OneDrive\Desktop\Proyecto - copia\resources\views\admin\module-form.blade.php ENDPATH**/ ?>