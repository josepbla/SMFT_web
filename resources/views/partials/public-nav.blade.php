<nav aria-label="Navegación principal">
    @foreach ($menus as $menu)
        @php($menuUrl = match ($menu->slug) {
            'inicio' => route('home'),
            'servicios' => route('services'),
            'noticias' => route('public.news'),
            'contacto' => route('contact'),
            'institucional' => route('public.pages'),
            default => route('public.pages'),
        })
        <a class="{{ $menu->slug === ($active ?? '') ? 'active' : '' }} {{ $menu->slug === 'contacto' ? 'nav-cta' : '' }}" href="{{ $menuUrl }}">{{ $menu->titulo }}</a>
    @endforeach
    <a class="{{ ($active ?? '') === 'pastoral' ? 'active' : '' }}" href="{{ route('pastoral') }}">Pastoral</a>
    <a href="{{ session()->has('admin_user') ? route('admin.dashboard') : route('auth.login') }}" class="nav-cta">Administración</a>
</nav>
