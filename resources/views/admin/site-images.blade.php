<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imágenes del proyecto | Colegio Santa Maria</title>
    <style>
        :root { --blue:#126bc1; --navy:#173b67; --pale:#f5f8fb; --line:#e2e9f0; }
        * { box-sizing:border-box; } body { margin:0; color:#30465e; background:var(--pale); font-family:Arial,sans-serif; } .wrap { width:min(1000px,92vw); margin:40px auto; }
        header { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; } h1 { margin:0; color:var(--navy); font-size:28px; } h1 small { display:block; margin-top:7px; color:#728497; font-size:14px; font-weight:400; } a { color:var(--blue); text-decoration:none; font-size:14px; }
        form { display:grid; grid-template-columns:repeat(2,1fr); gap:16px; } .field { padding:16px; background:#fff; border:1px solid var(--line); border-radius:10px; box-shadow:0 3px 10px rgba(36,65,95,.06); } label { display:block; margin-bottom:11px; color:var(--navy); font-size:14px; font-weight:700; } img { width:100%; height:150px; display:block; margin-bottom:13px; object-fit:cover; border-radius:6px; } input { width:100%; font-size:13px; } .actions { grid-column:span 2; display:flex; justify-content:space-between; align-items:center; gap:20px; padding-top:5px; } button { padding:13px 20px; border:0; color:#fff; background:var(--blue); border-radius:5px; font-weight:700; cursor:pointer; } .success { margin-bottom:18px; padding:13px 16px; color:#22613d; background:#e7f7ed; border-radius:6px; } .error { margin-bottom:18px; padding:13px 16px; color:#9b3030; background:#fdeaea; border-radius:6px; }
        @media(max-width:650px) { form { grid-template-columns:1fr; } .actions { grid-column:span 1; display:block; } button { margin-top:15px; } header { display:block; } header a { display:block; margin-top:12px; } }
    </style>
</head>
<body>
    <div class="wrap">
        <header><h1>Imágenes del proyecto<small>Colegio Santa Maria · se guardan de forma permanente</small></h1><a href="{{ route('home') }}">← Ver inicio</a></header>
        @if (session('success')) <div class="success">{{ session('success') }}</div> @endif
        @if ($errors->any()) <div class="error">{{ $errors->first() }}</div> @endif
        <form action="{{ route('site-images.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="field"><label for="home_hero">Inicio · imagen principal</label><img src="{{ $images['home_hero'] }}" alt="Imagen principal de Inicio"><input id="home_hero" name="home_hero" type="file" accept="image/*"></div>
            <div class="field"><label for="services_hero">Servicios · imagen principal</label><img src="{{ $images['services_hero'] }}" alt="Imagen principal de Servicios"><input id="services_hero" name="services_hero" type="file" accept="image/*"></div>
            <div class="field"><label for="contact_1">Contacto · carrusel 1</label><img src="{{ $images['contact_1'] }}" alt="Carrusel de Contacto 1"><input id="contact_1" name="contact_1" type="file" accept="image/*"></div>
            <div class="field"><label for="contact_2">Contacto · carrusel 2</label><img src="{{ $images['contact_2'] }}" alt="Carrusel de Contacto 2"><input id="contact_2" name="contact_2" type="file" accept="image/*"></div>
            <div class="field"><label for="contact_3">Contacto · carrusel 3</label><img src="{{ $images['contact_3'] }}" alt="Carrusel de Contacto 3"><input id="contact_3" name="contact_3" type="file" accept="image/*"></div>
            <div class="field"><label for="services_gallery_1">Servicios · galería 1</label><img src="{{ $images['services_gallery_1'] }}" alt="Imagen 1 de la galería de Servicios"><input id="services_gallery_1" name="services_gallery_1" type="file" accept="image/*"></div>
            <div class="field"><label for="services_gallery_2">Servicios · galería 2</label><img src="{{ $images['services_gallery_2'] }}" alt="Imagen 2 de la galería de Servicios"><input id="services_gallery_2" name="services_gallery_2" type="file" accept="image/*"></div>
            <div class="field"><label for="services_gallery_3">Servicios · galería 3</label><img src="{{ $images['services_gallery_3'] }}" alt="Imagen 3 de la galería de Servicios"><input id="services_gallery_3" name="services_gallery_3" type="file" accept="image/*"></div>
            <div class="field"><label for="services_gallery_4">Servicios · galería 4</label><img src="{{ $images['services_gallery_4'] }}" alt="Imagen 4 de la galería de Servicios"><input id="services_gallery_4" name="services_gallery_4" type="file" accept="image/*"></div>
            <div class="field"><label for="services_gallery_5">Servicios · galería 5</label><img src="{{ $images['services_gallery_5'] }}" alt="Imagen 5 de la galería de Servicios"><input id="services_gallery_5" name="services_gallery_5" type="file" accept="image/*"></div>
            <div class="field"><label for="services_gallery_6">Servicios · galería 6</label><img src="{{ $images['services_gallery_6'] }}" alt="Imagen 6 de la galería de Servicios"><input id="services_gallery_6" name="services_gallery_6" type="file" accept="image/*"></div>
            <div class="actions"><span>Deja un campo vacío para conservar su imagen actual.</span><button type="submit">Guardar imágenes</button></div>
        </form>
    </div>
</body>
</html>