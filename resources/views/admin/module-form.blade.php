<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $record ? 'Editar' : 'Nuevo' }} {{ $definition['title'] }} | Administración</title>
    <style>
        :root { --ink:#173b67; --blue:#126bc1; --paper:#f4f7fa; --white:#fff; --muted:#6b7c8d; --line:#dfe8ef; --danger:#a23b3b; }
        * { box-sizing:border-box; } body { margin:0; color:#30465e; background:var(--paper); font-family:Arial,sans-serif; } a { color:inherit; text-decoration:none; }
        .shell { width:min(980px,92vw); margin:0 auto; padding:35px 0 60px; } .top { display:flex; justify-content:space-between; align-items:flex-start; gap:20px; margin-bottom:25px; }
        .eyebrow { color:var(--blue); font-size:11px; font-weight:700; letter-spacing:.18em; text-transform:uppercase; } h1 { margin:9px 0 7px; color:var(--ink); font:700 34px Georgia,serif; } .sub { margin:0; color:var(--muted); font-size:14px; }
        .button { display:inline-block; padding:12px 16px; color:var(--blue); background:#fff; border:1px solid #cbdbe8; font-size:13px; font-weight:700; }
        form.panel { padding:25px; background:#fff; border:1px solid var(--line); } .fields { display:grid; grid-template-columns:repeat(2,1fr); gap:18px; } .field.full { grid-column:span 2; } label { display:block; margin-bottom:7px; color:var(--ink); font-size:13px; font-weight:700; } input,select,textarea { width:100%; padding:12px 13px; color:#30465e; border:1px solid #ccdbe5; background:#fff; font:inherit; font-size:14px; } input[type=file] { padding:9px; } textarea { min-height:130px; resize:vertical; } input:focus,select:focus,textarea:focus { outline:2px solid #bdd9eb; border-color:var(--blue); } .check { display:flex; align-items:center; gap:9px; min-height:43px; } .check input { width:auto; } .current-image { display:block; width:min(320px,100%); max-height:200px; margin-top:12px; object-fit:contain; object-position:left center; border:1px solid var(--line); } .hint { margin:7px 0 0; color:var(--muted); font-size:12px; line-height:1.5; } .error { margin-bottom:18px; padding:13px 16px; color:#9b3030; background:#fdeaea; font-size:13px; } .actions { display:flex; justify-content:flex-end; gap:10px; margin-top:25px; padding-top:20px; border-top:1px solid var(--line); } button { padding:13px 20px; border:0; color:#fff; background:var(--blue); font-weight:700; cursor:pointer; }
        @media(max-width:650px) { .top { display:block; } .fields { grid-template-columns:1fr; } .field.full { grid-column:span 1; } .actions { display:block; } .actions button,.actions a { display:block; width:100%; margin-top:9px; text-align:center; } }
    </style>
</head>
<body>
    <main class="shell">
        <header class="top"><div><div class="eyebrow">Centro de gestión · {{ $definition['title'] }}</div><h1>{{ $record ? 'Editar registro' : 'Nuevo registro' }}</h1><p class="sub">@if ($definition['key'] === 'enlaces')Para mostrar una red en Servicios, escribe su URL completa, elige “Red social” y marca “Activo”.@else Los cambios se guardan directamente en la base de datos.@endif</p></div><a class="button" href="{{ route('admin.module.index', $definition['key']) }}">← Volver al listado</a></header>
        @if ($errors->any()) <div class="error">{{ $errors->first() }}</div> @endif
        <form class="panel" method="POST" enctype="multipart/form-data" action="{{ $record ? route('admin.module.update', [$definition['key'], $record->{$definition['primary']}]) : route('admin.module.store', $definition['key']) }}">
            @csrf
            @if ($record) @method('PUT') @endif
            <div class="fields">
                @foreach ($definition['fields'] as $name => $field)
                    @php($value = $field['type'] === 'password' ? old($name, '') : old($name, $record->{$name} ?? ($field['type'] === 'checkbox' ? false : '')))
                    <div class="field {{ in_array($field['type'], ['textarea']) ? 'full' : '' }}">
                        @if ($field['type'] === 'checkbox')
                            <label class="check"><input type="checkbox" name="{{ $name }}" value="1" @checked($value)> {{ ucfirst(str_replace('_', ' ', $name)) }}</label>
                        @else
                            <label for="{{ $name }}">{{ ucfirst(str_replace('_', ' ', $name)) }}</label>
                            @if ($field['type'] === 'textarea')
                                <textarea id="{{ $name }}" name="{{ $name }}" @disabled(($field['readonly'] ?? false) && $record)>{{ $value }}</textarea>
                            @elseif ($field['type'] === 'select')
                                <select id="{{ $name }}" name="{{ $name }}" @disabled(($field['readonly'] ?? false) && $record)>
                                    <option value="">Seleccionar...</option>
                                    @foreach (($field['options'] ?? $options[$name] ?? []) as $optionValue => $optionLabel)<option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>@endforeach
                                </select>
                            @elseif ($field['type'] === 'image')
                                <input id="{{ $name }}" name="{{ $name }}" type="file" accept="image/jpeg,image/png,image/webp,image/gif" @required(in_array('required', $field['rules'] ?? []) && ! $record)>
                                @if ($record && $value)
                                    <img class="current-image" src="{{ \Illuminate\Support\Str::startsWith($value, ['http://', 'https://']) ? $value : asset(ltrim($value, '/')) }}" alt="Imagen actual">
                                @endif
                                <p class="hint">JPG, PNG, WEBP o GIF, hasta 100 MB. Si no eliges otra imagen, se conservará la actual.</p>
                            @else
                                <input id="{{ $name }}" name="{{ $name }}" type="{{ $field['type'] }}" value="{{ $value }}" @required(in_array('required', $field['rules'] ?? [])) @disabled(($field['readonly'] ?? false) && $record)>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="actions"><a class="button" href="{{ route('admin.module.index', $definition['key']) }}">Cancelar</a><button type="submit">{{ $record ? 'Guardar cambios' : 'Crear registro' }}</button></div>
        </form>
    </main>
</body>
</html>
