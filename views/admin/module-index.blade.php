<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $definition['title'] }} | Administración</title>
    <style>
        :root { --ink:#173b67; --blue:#126bc1; --paper:#f4f7fa; --white:#fff; --muted:#6b7c8d; --line:#dfe8ef; --danger:#a23b3b; }
        * { box-sizing:border-box; } body { margin:0; color:#30465e; background:var(--paper); font-family:Arial,sans-serif; } a { color:inherit; text-decoration:none; }
        .shell { width:min(1180px,92vw); margin:0 auto; padding:35px 0 60px; } .top { display:flex; justify-content:space-between; align-items:flex-start; gap:20px; margin-bottom:27px; }
        .eyebrow { color:var(--blue); font-size:11px; font-weight:700; letter-spacing:.18em; text-transform:uppercase; } h1 { margin:9px 0 7px; color:var(--ink); font:700 34px Georgia,serif; } .sub { margin:0; color:var(--muted); font-size:14px; }
        .actions { display:flex; gap:8px; } .button { display:inline-block; padding:12px 16px; color:#fff; background:var(--blue); font-size:13px; font-weight:700; } .button.secondary { color:var(--blue); background:#fff; border:1px solid #cbdbe8; }
        .notice { margin-bottom:18px; padding:13px 16px; color:#22613d; background:#e7f7ed; font-size:13px; } .table-wrap { overflow-x:auto; background:#fff; border:1px solid var(--line); } table { width:100%; border-collapse:collapse; min-width:720px; } th,td { padding:14px 15px; border-bottom:1px solid var(--line); text-align:left; vertical-align:top; font-size:13px; } th { color:var(--ink); background:#f8fbfd; font-size:11px; letter-spacing:.08em; text-transform:uppercase; } tr:last-child td { border-bottom:0; } td { color:#53697d; } td:first-child { color:var(--ink); font-weight:700; } .cell { max-width:260px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; } .row-actions { display:flex; align-items:center; gap:10px; white-space:nowrap; } .edit { color:var(--blue); font-weight:700; } .delete { padding:0; color:var(--danger); border:0; background:none; font:inherit; cursor:pointer; }
        .empty { padding:45px; color:var(--muted); text-align:center; } .pagination { margin-top:18px; } .pagination svg { width:16px; } .pagination nav { display:flex; justify-content:center; gap:5px; } .pagination a,.pagination span { display:inline-block; padding:8px 11px; background:#fff; border:1px solid var(--line); font-size:12px; }
        @media(max-width:650px) { .top { display:block; } .actions { margin-top:18px; } .actions .button { flex:1; text-align:center; } }
    </style>
</head>
<body>
    <main class="shell">
        <header class="top"><div><div class="eyebrow">Centro de gestión</div><h1>{{ $definition['title'] }}</h1><p class="sub">Registros conectados directamente a la base de datos.</p></div><div class="actions"><a class="button secondary" href="{{ route('admin.dashboard') }}">Panel</a><a class="button" href="{{ route('admin.module.create', $definition['key']) }}">+ Nuevo registro</a></div></header>
        @if (session('success')) <div class="notice">{{ session('success') }}</div> @endif
        @if ($errors->any()) <div class="notice" style="color:#9b3030;background:#fdeaea;">{{ $errors->first() }}</div> @endif
        @if ($records->isEmpty())
            <div class="table-wrap empty">Todavía no hay registros en este módulo.<br><a class="edit" href="{{ route('admin.module.create', $definition['key']) }}">Crear el primero</a></div>
        @else
            <div class="table-wrap"><table><thead><tr><th>{{ $definition['primary'] }}</th>@foreach (array_slice($definition['fields'], 0, 4, true) as $name => $field)<th>{{ ucfirst(str_replace('_', ' ', $name)) }}</th>@endforeach<th>Acciones</th></tr></thead><tbody>
                @foreach ($records as $record)<tr><td>{{ $record->{$definition['primary']} }}</td>@foreach (array_slice($definition['fields'], 0, 4, true) as $name => $field)<td><div class="cell">{{ is_bool($record->{$name}) ? ($record->{$name} ? 'Sí' : 'No') : ($record->{$name} ?? 'Sin dato') }}</div></td>@endforeach<td><div class="row-actions"><a class="edit" href="{{ route('admin.module.edit', [$definition['key'], $record->{$definition['primary']}]) }}">Editar</a><form method="POST" action="{{ route('admin.module.destroy', [$definition['key'], $record->{$definition['primary']}]) }}" onsubmit="return confirm('¿Eliminar este registro?')">@csrf @method('DELETE')<button class="delete" type="submit">Eliminar</button></form></div></td></tr>@endforeach
            </tbody></table></div>
            <div class="pagination">{{ $records->links() }}</div>
        @endif
    </main>
</body>
</html>
