<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('usuarios')->insert([
            'nombres' => 'Administrador',
            'email' => 'admin@santamariatumbes.edu.pe',
            'password' => Hash::make(env('ADMIN_PASSWORD', 'Cambiar123!')),
            'rol' => 'administrador',
            'estado' => 'activo',
        ]);

        DB::table('configuracion')->insert([
            ['clave' => 'nombre_colegio', 'valor' => 'Institucion Educativa Particular Santa Maria de la Frontera'],
            ['clave' => 'lema', 'valor' => 'Todo por Jesus y en espiritu de reparacion'],
            ['clave' => 'direccion', 'valor' => 'Tumbes, Peru'],
            ['clave' => 'telefono', 'valor' => ''],
            ['clave' => 'email_contacto', 'valor' => ''],
            ['clave' => 'mision', 'valor' => ''],
            ['clave' => 'vision', 'valor' => ''],
        ]);

        DB::table('menus')->insert([
            ['titulo' => 'Inicio', 'slug' => 'inicio', 'orden' => 1],
            ['titulo' => 'Institucional', 'slug' => 'institucional', 'orden' => 2],
            ['titulo' => 'Academico', 'slug' => 'academico', 'orden' => 3],
            ['titulo' => 'Niveles formativos', 'slug' => 'niveles-formativos', 'orden' => 4],
            ['titulo' => 'Noticias y actualidad', 'slug' => 'noticias', 'orden' => 5],
            ['titulo' => 'Servicios', 'slug' => 'servicios', 'orden' => 6],
            ['titulo' => 'Ex alumnos', 'slug' => 'ex-alumnos', 'orden' => 7],
            ['titulo' => 'Contacto', 'slug' => 'contacto', 'orden' => 8],
        ]);

        DB::table('niveles')->insert([
            ['nombre' => 'Inicial', 'orden' => 1],
            ['nombre' => 'Primaria', 'orden' => 2],
            ['nombre' => 'Secundaria', 'orden' => 3],
        ]);

        DB::table('categorias_noticias')->insert([
            ['nombre' => 'Institucional', 'slug' => 'institucional'],
            ['nombre' => 'Academico', 'slug' => 'academico'],
            ['nombre' => 'Pastoral', 'slug' => 'pastoral'],
            ['nombre' => 'Deportes', 'slug' => 'deportes'],
            ['nombre' => 'Fiestas congregacion', 'slug' => 'fiestas-congregacion'],
        ]);

        DB::table('enlaces')->insert([
            ['nombre' => 'Ministerio de Educacion', 'url' => 'https://www.gob.pe/minedu', 'tipo' => 'institucional', 'orden' => 1],
            ['nombre' => 'PerúEduca', 'url' => 'https://www.perueduca.pe', 'tipo' => 'institucional', 'orden' => 2],
            ['nombre' => 'Pronabec', 'url' => 'https://www.gob.pe/pronabec', 'tipo' => 'institucional', 'orden' => 3],
            ['nombre' => 'SiseVe', 'url' => 'https://www.siseve.pe', 'tipo' => 'institucional', 'orden' => 4],
            ['nombre' => 'Siagie', 'url' => 'https://siagie.minedu.gob.pe', 'tipo' => 'institucional', 'orden' => 5],
            ['nombre' => 'Senaju', 'url' => 'https://www.gob.pe/senaju', 'tipo' => 'institucional', 'orden' => 6],
            ['nombre' => 'Facebook', 'url' => 'https://www.facebook.com/', 'tipo' => 'red_social', 'orden' => 1],
            ['nombre' => 'YouTube', 'url' => 'https://www.youtube.com/', 'tipo' => 'red_social', 'orden' => 2],
        ]);

        DB::table('galerias')->insert([
            ['titulo' => 'Ingreso'],
            ['titulo' => 'Pastoral'],
            ['titulo' => 'Fiestas congregacion'],
        ]);
    }
}
