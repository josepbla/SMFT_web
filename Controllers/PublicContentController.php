<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PublicContentController extends Controller
{
    public function home(): View
    {
        return view('welcome', $this->sharedContent());
    }

    public function services(): View
    {
        return view('services', $this->sharedContent());
    }

    public function contact(): View
    {
        return view('contact', $this->sharedContent());
    }

    public function page(string $slug): View
    {
        $page = DB::table('paginas')->where('slug', $slug)->where('publicado', true)->firstOrFail();

        return view('public.page', compact('page'));
    }

    public function news(): View
    {
        $news = DB::table('noticias')
            ->leftJoin('categorias_noticias', 'noticias.id_categoria', '=', 'categorias_noticias.id_categoria')
            ->select('noticias.*', 'categorias_noticias.nombre as categoria_nombre')
            ->where('noticias.publicado', true)
            ->where(function ($query): void {
                $query->whereNull('noticias.fecha_publicacion')->orWhere('noticias.fecha_publicacion', '<=', now());
            })
            ->orderByDesc('noticias.fecha_publicacion')
            ->orderByDesc('noticias.creado_en')
            ->paginate(9);

        return view('public.news-index', compact('news'));
    }

    public function newsShow(string $slug): View
    {
        $article = DB::table('noticias')
            ->leftJoin('categorias_noticias', 'noticias.id_categoria', '=', 'categorias_noticias.id_categoria')
            ->select('noticias.*', 'categorias_noticias.nombre as categoria_nombre')
            ->where('noticias.slug', $slug)
            ->where('noticias.publicado', true)
            ->firstOrFail();

        return view('public.news-show', compact('article'));
    }

    public function pages(): View
    {
        $records = DB::table('paginas')->where('publicado', true)->orderBy('titulo')->get();

        return view('public.directory', ['title' => 'Páginas institucionales', 'type' => 'pages', 'records' => $records]);
    }

    public function announcements(): View
    {
        $records = DB::table('comunicados')->where('publicado', true)->orderByDesc('fecha')->get();

        return view('public.directory', ['title' => 'Comunicados', 'type' => 'announcements', 'records' => $records]);
    }

    public function documents(): View
    {
        $records = DB::table('documentos')->where('publicado', true)->orderBy('titulo')->get();

        return view('public.directory', ['title' => 'Documentos', 'type' => 'documents', 'records' => $records]);
    }

    public function staff(): View
    {
        $records = DB::table('personal')
            ->leftJoin('niveles', 'personal.id_nivel', '=', 'niveles.id_nivel')
            ->select('personal.*', 'niveles.nombre as nivel_nombre')
            ->where('personal.activo', true)
            ->orderBy('personal.orden')
            ->orderBy('personal.apellidos')
            ->get();

        return view('public.directory', ['title' => 'Equipo del colegio', 'type' => 'staff', 'records' => $records]);
    }

    public function links(): View
    {
        $records = DB::table('enlaces')->where('activo', true)->orderBy('tipo')->orderBy('orden')->get();

        return view('public.directory', ['title' => 'Enlaces de interés', 'type' => 'links', 'records' => $records]);
    }

    public function galleries(): View
    {
        $records = DB::table('galerias')->where('publicado', true)->orderByDesc('fecha')->orderBy('titulo')->get();
        $records->each(function (object $gallery): void {
            $gallery->items = DB::table('galeria_items')->where('id_galeria', $gallery->id_galeria)->get();
        });

        return view('public.directory', ['title' => 'Galerías', 'type' => 'galleries', 'records' => $records]);
    }

    private function sharedContent(): array
    {
        $config = DB::table('configuracion')->pluck('valor', 'clave')->all();
        $levels = DB::table('niveles')->orderBy('orden')->orderBy('id_nivel')->get();
        $menus = DB::table('menus')->whereNull('id_padre')->where('visible', true)->orderBy('orden')->get();
        $banners = DB::table('banners')->where('activo', true)->orderBy('orden')->get();
        $news = DB::table('noticias')
            ->where('publicado', true)
            ->where(function ($query): void {
                $query->whereNull('fecha_publicacion')->orWhere('fecha_publicacion', '<=', now());
            })
            ->orderByDesc('destacada')
            ->orderByDesc('fecha_publicacion')
            ->orderByDesc('creado_en')
            ->get();
        $staff = DB::table('personal')->where('activo', true)->orderBy('orden')->get();
        $links = DB::table('enlaces')->where('activo', true)->orderBy('tipo')->orderBy('orden')->get();

        return compact('config', 'levels', 'menus', 'banners', 'news', 'staff', 'links');
    }
}
