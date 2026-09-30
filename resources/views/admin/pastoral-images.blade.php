<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar imágenes | Colegio Santa Maria</title>
    <style>
        :root { --blue:#126bc1; --navy:#173b67; --pale:#f5f8fb; --line:#e2e9f0; }
        * { box-sizing:border-box; } body { margin:0; color:#30465e; background:var(--pale); font-family:Arial,sans-serif; } .wrap { width:min(900px,92vw); margin:45px auto; }
        header { display:flex; justify-content:space-between; align-items:center; margin-bottom:28px; } h1 { margin:0; color:var(--navy); font-size:30px; } h1 small { display:block; margin-top:7px; color:#728497; font-size:14px; font-weight:400; } a { color:var(--blue); text-decoration:none; font-size:14px; }
        form { display:grid; grid-template-columns:1fr 1fr; gap:18px; } .field { padding:18px; background:#fff; border:1px solid var(--line); border-radius:10px; box-shadow:0 3px 10px rgba(36,65,95,.06); } .field:first-child { grid-column:span 2; } label { display:block; margin-bottom:12px; color:var(--navy); font-size:14px; font-weight:700; } input { width:100%; color:#647487; font-size:13px; } img { width:100%; height:150px; display:block; margin-bottom:14px; object-fit:cover; border-radius:6px; } .actions { grid-column:span 2; display:flex; align-items:center; justify-content:space-between; gap:20px; padding-top:4px; } button { padding:13px 20px; border:0; color:#fff; background:var(--blue); border-radius:5px; font-weight:700; cursor:pointer; } .success { margin-bottom:20px; padding:13px 16px; color:#22613d; background:#e7f7ed; border-radius:6px; } .error { margin-bottom:20px; padding:13px 16px; color:#9b3030; background:#fdeaea; border-radius:6px; }
        @media(max-width:650px) { form { grid-template-columns:1fr; } .field:first-child,.actions { grid-column:span 1; } header { display:block; } header a { display:block; margin-top:12px; } }
    </style>
</head>
<body>
    <div class="wrap">
        <header><h1>Imágenes de Pastoral<small>Colegio Santa Maria · cambia la portada y la galería</small></h1><a href="{{ route('pastoral') }}">← Ver página Pastoral</a></header>
        @if (session('success')) <div class="success">{{ session('success') }}</div> @endif
        @if ($errors->any()) <div class="error">{{ $errors->first() }}</div> @endif
        <form action="{{ route('pastoral.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="field"><label for="hero">Imagen principal</label><img src="{{ $images['hero'] }}" alt="Imagen principal actual"><input id="hero" name="hero" type="file" accept="image/*"></div>
            <div class="field"><label for="gallery_1">Galería 1</label><img src="{{ $images['gallery_1'] }}" alt="Galería 1 actual"><input id="gallery_1" name="gallery_1" type="file" accept="image/*"></div>
            <div class="field"><label for="gallery_2">Galería 2</label><img src="{{ $images['gallery_2'] }}" alt="Galería 2 actual"><input id="gallery_2" name="gallery_2" type="file" accept="image/*"></div>
            <div class="field"><label for="gallery_3">Galería 3</label><img src="{{ $images['gallery_3'] }}" alt="Galería 3 actual"><input id="gallery_3" name="gallery_3" type="file" accept="image/*"></div>
            <div class="field"><label for="gallery_4">Galería 4</label><img src="{{ $images['gallery_4'] }}" alt="Galería 4 actual"><input id="gallery_4" name="gallery_4" type="file" accept="image/*"></div>
            <div class="field"><label for="gallery_5">Galería 5</label><img src="{{ $images['gallery_5'] }}" alt="Galería 5 actual"><input id="gallery_5" name="gallery_5" type="file" accept="image/*"></div>
            <div class="field"><label for="gallery_6">Galería 6</label><img src="{{ $images['gallery_6'] }}" alt="Galería 6 actual"><input id="gallery_6" name="gallery_6" type="file" accept="image/*"></div>
            <div class="actions"><span>Los campos vacíos conservan su imagen actual.</span><button type="submit">Guardar imágenes</button></div>
        </form>
    </div>
</body>
</html>