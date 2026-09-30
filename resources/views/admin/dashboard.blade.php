<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel administrativo | Colegio Santa Maria</title>
    <style>
        :root { --ink:#173b67; --blue:#126bc1; --gold:#d7a84e; --paper:#f4f7fa; --white:#fff; --muted:#6b7c8d; --line:#e1e9f0; }
        * { box-sizing:border-box; }
        body { margin:0; color:#30465e; background:var(--paper); font-family:Arial,sans-serif; }
        a { color:inherit; text-decoration:none; }
        .shell { width:min(1180px,92vw); margin:0 auto; padding:38px 0 60px; }
        .top { display:flex; justify-content:space-between; align-items:flex-start; gap:25px; margin-bottom:34px; }
        .eyebrow { margin-bottom:10px; color:var(--blue); font-size:11px; font-weight:700; letter-spacing:.18em; text-transform:uppercase; }
        h1 { margin:0; color:var(--ink); font:700 clamp(30px,4vw,45px)/1.05 Georgia,serif; }
        .subtitle { max-width:650px; margin:13px 0 0; color:var(--muted); line-height:1.6; }
        .home-link { padding:11px 15px; color:var(--blue); border:1px solid #cbdbe8; background:var(--white); font-size:13px; font-weight:700; }
        .summary { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:38px; }
        .summary-card { padding:20px 22px; background:var(--ink); color:var(--white); }
        .summary-card:nth-child(2) { background:var(--blue); }
        .summary-card:nth-child(3) { color:var(--ink); background:#f0d18c; }
        .summary-card strong { display:block; font-size:30px; }
        .summary-card span { display:block; margin-top:6px; font-size:12px; letter-spacing:.08em; text-transform:uppercase; opacity:.82; }
        .group { margin-top:34px; }
        .group-heading { display:flex; align-items:end; justify-content:space-between; gap:20px; padding-bottom:13px; border-bottom:1px solid var(--line); }
        h2 { margin:0; color:var(--ink); font:700 24px Georgia,serif; }
        .group-heading p { max-width:470px; margin:0; color:var(--muted); font-size:13px; line-height:1.5; text-align:right; }
        .module-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:13px; margin-top:15px; }
        .module { min-height:122px; display:flex; flex-direction:column; justify-content:space-between; padding:17px; background:var(--white); border:1px solid var(--line); box-shadow:0 4px 12px rgba(29,65,94,.05); transition:transform .18s ease,border-color .18s ease; }
        .module:hover { transform:translateY(-3px); border-color:#a9c9df; }
        .module-icon { color:var(--gold); font-size:22px; }
        .module-name { margin-top:16px; color:var(--ink); font-size:14px; font-weight:700; }
        .module-meta { margin-top:7px; color:var(--muted); font-size:12px; }
        .module-action { color:var(--blue); font-size:12px; font-weight:700; }
        footer { margin-top:44px; padding-top:18px; color:#8393a1; border-top:1px solid var(--line); font-size:12px; }
        @media(max-width:800px) { .module-grid { grid-template-columns:repeat(2,1fr); } .summary { grid-template-columns:1fr; } }
        @media(max-width:560px) { .shell { width:min(94vw,460px); padding-top:25px; } .top,.group-heading { display:block; } .home-link { display:inline-block; margin-top:18px; } .group-heading p { margin-top:10px; text-align:left; } .module-grid { grid-template-columns:1fr; } }
    </style>
</head>
<body>
    <main class="shell">
        <header class="top">
            <div>
                <div class="eyebrow">Administración · Santa Maria</div>
                <h1>Centro de gestión</h1>
                <p class="subtitle">Todo el contenido del colegio reunido en un solo lugar. Consulta el estado de cada módulo y continúa con la gestión que necesitas.</p>
            </div>
            <div>
                <a class="home-link" href="{{ route('home') }}">Ver sitio público ↗</a>
                <form action="{{ route('auth.logout') }}" method="POST" style="display:inline;margin-left:8px;">@csrf<button class="home-link" type="submit">Cerrar sesión</button></form>
            </div>
        </header>

        <section class="summary" aria-label="Resumen del sistema">
            <div class="summary-card"><strong>{{ $counts->sum() }}</strong><span>Registros cargados</span></div>
            <div class="summary-card"><strong>{{ array_sum(array_map(fn ($group) => count($group['items']), $groups)) }}</strong><span>Módulos organizados</span></div>
            <div class="summary-card"><strong>{{ $counts->get('noticias', 0) }}</strong><span>Noticias publicadas o en borrador</span></div>
        </section>

        @foreach ($groups as $group)
            <section class="group">
                <div class="group-heading"><h2>{{ $group['title'] }}</h2><p>{{ $group['description'] }}</p></div>
                <div class="module-grid">
                    @foreach ($group['items'] as $item)
                        @if (isset($item['route']))
                            <a class="module" href="{{ route($item['route']) }}">
                        @elseif ($item['table'])
                            <a class="module" href="{{ route('admin.module.index', $item['table']) }}">
                        @else
                            <div class="module">
                        @endif
                            <div><div class="module-icon" aria-hidden="true">{{ $item['icon'] }}</div><div class="module-name">{{ $item['label'] }}</div></div>
                            @if ($item['table'])
                                <div class="module-meta">{{ number_format($counts->get($item['table'], 0)) }} registros · Gestionar ↗</div>
                            @else
                                <div class="module-action">Abrir herramienta ↗</div>
                            @endif
                        @if (isset($item['route']) || $item['table'])
                            </a>
                        @else
                            </div>
                        @endif
                    @endforeach
                </div>
            </section>
        @endforeach

        <footer>Panel conectado a la base de datos de Colegio Santa Maria.</footer>
    </main>
</body>
</html>