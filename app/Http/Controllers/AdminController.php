<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $groups = [
            [
                'title' => 'Contenido institucional',
                'description' => 'Páginas, menús, banners y configuración general del colegio.',
                'items' => [
                    ['label' => 'Páginas', 'table' => 'paginas', 'icon' => '▣'],
                    ['label' => 'Menús', 'table' => 'menus', 'icon' => '☰'],
                    ['label' => 'Banners', 'table' => 'banners', 'icon' => '▤'],
                    ['label' => 'Configuración', 'table' => 'configuracion', 'icon' => '⚙'],
                ],
            ],
            [
                'title' => 'Comunidad educativa',
                'description' => 'Niveles, personal, exalumnos y solicitudes de ingreso.',
                'items' => [
                    ['label' => 'Niveles formativos', 'table' => 'niveles', 'icon' => '◇'],
                    ['label' => 'Personal', 'table' => 'personal', 'icon' => '◎'],
                    ['label' => 'Exalumnos', 'table' => 'exalumnos', 'icon' => '○'],
                    ['label' => 'Solicitudes de ingreso', 'table' => 'solicitudes_ingreso', 'icon' => '→'],
                ],
            ],
            [
                'title' => 'Comunicación',
                'description' => 'Noticias, comunicados, documentos y galerías multimedia.',
                'items' => [
                    ['label' => 'Noticias', 'table' => 'noticias', 'icon' => '▤'],
                    ['label' => 'Categorías de noticias', 'table' => 'categorias_noticias', 'icon' => '≡'],
                    ['label' => 'Comunicados', 'table' => 'comunicados', 'icon' => '▥'],
                    ['label' => 'Documentos', 'table' => 'documentos', 'icon' => '□'],
                    ['label' => 'Galerías', 'table' => 'galerias', 'icon' => '▦'],
                    ['label' => 'Elementos de galería', 'table' => 'galeria_items', 'icon' => '▧'],
                ],
            ],
            [
                'title' => 'Atención y enlaces',
                'description' => 'Mensajes, reclamos, redes sociales y enlaces de interés.',
                'items' => [
                    ['label' => 'Mensajes de contacto', 'table' => 'mensajes_contacto', 'icon' => '@'],
                    ['label' => 'Libro de reclamaciones', 'table' => 'reclamaciones', 'icon' => '!'],
                    ['label' => 'Redes sociales y enlaces', 'table' => 'enlaces', 'icon' => '↗'],
                    ['label' => 'Imágenes del sitio', 'table' => null, 'icon' => '◈', 'route' => 'site-images.edit'],
                    ['label' => 'Imágenes de Pastoral', 'table' => null, 'icon' => '✦', 'route' => 'pastoral.edit'],
                ],
            ],
            [
                'title' => 'Seguridad',
                'description' => 'Cuentas autorizadas para administrar el contenido del colegio.',
                'items' => [
                    ['label' => 'Administradores', 'table' => 'usuarios', 'icon' => '◎'],
                ],
            ],
        ];

        $tables = collect($groups)
            ->flatMap(fn (array $group) => $group['items'])
            ->pluck('table')
            ->filter()
            ->unique();

        $counts = $tables->mapWithKeys(fn (string $table) => [$table => DB::table($table)->count()]);

        return view('admin.dashboard', compact('groups', 'counts'));
    }
}