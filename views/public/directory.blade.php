<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} | Colegio Santa Maria</title>
    <style>
        :root{--ink:#173b67;--blue:#126bc1;--paper:#f5f8fb;--line:#dfe8ef;--muted:#68798a}*{box-sizing:border-box}body{margin:0;color:#30465e;background:var(--paper);font-family:Arial,sans-serif}header,main{width:min(1120px,92vw);margin:auto}header{padding:30px 0}a{color:var(--blue);text-decoration:none}.eyebrow{color:var(--blue);font-size:11px;font-weight:700;letter-spacing:.16em;text-transform:uppercase}h1{margin:10px 0 0;color:var(--ink);font:700 46px Georgia,serif}.grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}.card{padding:23px;background:#fff;border:1px solid var(--line)}.card h2{margin:9px 0;color:var(--ink);font:700 23px Georgia,serif}.card p{color:var(--muted);line-height:1.65}.date{color:var(--blue);font-size:12px;font-weight:700}.button{display:inline-block;margin-top:12px;padding:11px 14px;color:#fff;background:var(--blue);font-size:12px;font-weight:700}.photo{width:100%;height:190px;object-fit:cover;margin-bottom:12px}.empty{padding:35px;background:#fff;color:var(--muted)}@media(max-width:760px){.grid{grid-template-columns:1fr}h1{font-size:36px}}
    </style>
</head>
<body>
<header><a href="{{ route('home') }}">← Volver al inicio</a><div class="eyebrow">Colegio Santa Maria</div><h1>{{ $title }}</h1></header>
<main><div class="grid">
    @forelse ($records as $record)
        @if ($type === 'pages')
            <article class="card"><div class="eyebrow">Información</div><h2>{{ $record->titulo }}</h2><p>{{ \Illuminate\Support\Str::limit(strip_tags($record->contenido ?? ''), 150) }}</p><a class="button" href="{{ route('public.page', $record->slug) }}">Leer página ↗</a></article>
        @elseif ($type === 'announcements')
            <article class="card"><span class="date">{{ \Illuminate\Support\Carbon::parse($record->fecha)->format('d/m/Y') }}</span><h2>{{ $record->titulo }}</h2><p>{{ $record->contenido }}</p>@if($record->archivo)<a class="button" href="{{ $record->archivo }}" target="_blank" rel="noopener">Ver archivo ↗</a>@endif</article>
        @elseif ($type === 'documents')
            <article class="card"><div class="eyebrow">Documento</div><h2>{{ $record->titulo }}</h2><p>{{ $record->categoria ?: 'Documento institucional' }}</p><a class="button" href="{{ $record->archivo }}" target="_blank" rel="noopener">Abrir documento ↗</a></article>
        @elseif ($type === 'staff')
            <article class="card">@if($record->foto)<img class="photo" src="{{ $record->foto }}" alt="{{ $record->nombres }} {{ $record->apellidos }}">@endif<div class="eyebrow">{{ ucfirst($record->area) }}</div><h2>{{ $record->nombres }} {{ $record->apellidos }}</h2><p>{{ $record->cargo ?: $record->especialidad ?: ($record->nivel_nombre ?? 'Equipo educativo') }}</p></article>
        @elseif ($type === 'links')
            <article class="card"><div class="eyebrow">{{ str_replace('_', ' ', $record->tipo) }}</div><h2>{{ $record->nombre }}</h2><a class="button" href="{{ $record->url }}" target="_blank" rel="noopener">Visitar enlace ↗</a></article>
        @else
            <article class="card">@if($record->portada)<img class="photo" src="{{ $record->portada }}" alt="{{ $record->titulo }}">@elseif($record->items->firstWhere('tipo', 'imagen'))<img class="photo" src="{{ $record->items->firstWhere('tipo', 'imagen')->ruta }}" alt="{{ $record->titulo }}">@endif<div class="eyebrow">Galería</div><h2>{{ $record->titulo }}</h2><p>{{ $record->descripcion ?: 'Momentos de nuestra comunidad educativa.' }}</p>@if($record->items->isNotEmpty())<p>{{ $record->items->count() }} elementos publicados.</p>@endif</article>
        @endif
    @empty
        <p class="empty">No hay contenido publicado en este momento.</p>
    @endforelse
</div></main>
</body>
</html>
