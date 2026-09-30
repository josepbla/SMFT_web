<?php

namespace App\Http\Controllers;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminDataController extends Controller
{
    public function index(string $module): View
    {
        $definition = $this->definition($module);
        $records = DB::table($definition['table'])
            ->orderByDesc($definition['primary'])
            ->paginate(12)
            ->withQueryString();

        return view('admin.module-index', compact('definition', 'records'));
    }

    public function create(string $module): View
    {
        $definition = $this->definition($module);

        return view('admin.module-form', [
            'definition' => $definition,
            'record' => null,
            'options' => $this->options($definition),
        ]);
    }

    public function store(Request $request, string $module): RedirectResponse
    {
        $definition = $this->definition($module);
        $data = $this->validatedData($request, $definition);
        DB::table($definition['table'])->insert($data);

        return redirect()->route('admin.module.index', $module)->with('success', 'Registro creado correctamente.');
    }

    public function edit(string $module, string $id): View
    {
        $definition = $this->definition($module);
        $record = $this->query($definition)->where($definition['primary'], $id)->firstOrFail();

        return view('admin.module-form', [
            'definition' => $definition,
            'record' => $record,
            'options' => $this->options($definition),
        ]);
    }

    public function update(Request $request, string $module, string $id): RedirectResponse
    {
        $definition = $this->definition($module);
        $record = $this->query($definition)->where($definition['primary'], $id)->firstOrFail();
        $data = $this->validatedData($request, $definition, $id);
        DB::table($definition['table'])->where($definition['primary'], $record->{$definition['primary']})->update($data);

        return redirect()->route('admin.module.index', $module)->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy(string $module, string $id): RedirectResponse
    {
        $definition = $this->definition($module);
        if ($module === 'usuarios') {
            $user = DB::table('usuarios')->where('id_usuario', $id)->first();
            if ($user?->rol === 'administrador' && $user->estado === 'activo' && DB::table('usuarios')->where('rol', 'administrador')->where('estado', 'activo')->count() <= 1) {
                return back()->withErrors(['delete' => 'No puedes eliminar al último administrador activo.']);
            }
        }
        DB::table($definition['table'])->where($definition['primary'], $id)->delete();

        return redirect()->route('admin.module.index', $module)->with('success', 'Registro eliminado correctamente.');
    }

    private function query(array $definition): Builder
    {
        return DB::table($definition['table']);
    }

    private function validatedData(Request $request, array $definition, ?string $id = null): array
    {
        $rules = [];
        foreach ($definition['fields'] as $name => $field) {
            if (($field['readonly'] ?? false) && $id !== null) {
                continue;
            }
            $rules[$name] = $field['rules'] ?? ['nullable', 'string'];
        }
        if ($id === null && isset($definition['fields']['password'])) {
            $rules['password'] = ['required', 'string', 'min:8'];
        }
        $request->validate($rules);

        $data = [];
        foreach ($definition['fields'] as $name => $field) {
            if (($field['readonly'] ?? false) && $id !== null) {
                continue;
            }
            if (($field['hash'] ?? false) && ! $request->filled($name)) {
                continue;
            }
            if (($field['type'] ?? null) === 'image') {
                if ($request->hasFile($name)) {
                    $file = $request->file($name);
                    $directory = public_path('uploads/content');

                    if (! is_dir($directory)) {
                        mkdir($directory, 0755, true);
                    }

                    $filename = $name.'-'.Str::uuid().'.'.$file->extension();
                    $contents = @file_get_contents($file->getRealPath());
                    $destination = $directory.DIRECTORY_SEPARATOR.$filename;
                    $written = $contents === false ? false : @file_put_contents($destination, $contents, LOCK_EX);

                    if ($written !== strlen((string) $contents)) {
                        @unlink($destination);
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            $name => 'No se pudo guardar la imagen. Verifica los permisos de public/uploads/content.',
                        ]);
                    }

                    $data[$name] = '/uploads/content/'.$filename;
                } elseif ($id === null) {
                    $data[$name] = null;
                }

                continue;
            }
            $data[$name] = ($field['type'] ?? 'text') === 'checkbox'
                ? $request->boolean($name)
                : (($field['hash'] ?? false) ? Hash::make($request->input($name)) : $request->input($name));
        }

        return $data;
    }

    private function options(array $definition): array
    {
        $options = [];
        foreach ($definition['fields'] as $name => $field) {
            if (isset($field['options'])) {
                $options[$name] = $field['options'];
            } elseif (isset($field['relation'])) {
                $relation = $field['relation'];
                $options[$name] = DB::table($relation['table'])
                    ->orderBy($relation['label'])
                    ->pluck($relation['label'], $relation['value'])
                    ->all();
            }
        }

        return $options;
    }

    private function definition(string $module): array
    {
        $definitions = $this->definitions();
        abort_unless(isset($definitions[$module]), 404);

        return $definitions[$module] + ['key' => $module];
    }

    private function definitions(): array
    {
        $text = fn (bool $required = false): array => ['type' => 'text', 'rules' => [$required ? 'required' : 'nullable', 'string', 'max:255']];
        $image = fn (bool $required = false): array => ['type' => 'image', 'rules' => [$required ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:102400']];
        $textarea = fn (bool $required = false): array => ['type' => 'textarea', 'rules' => [$required ? 'required' : 'nullable', 'string']];
        $number = fn (): array => ['type' => 'number', 'rules' => ['nullable', 'integer', 'min:0']];
        $checkbox = fn (): array => ['type' => 'checkbox', 'rules' => ['nullable', 'boolean']];
        $select = fn (array $options): array => ['type' => 'select', 'options' => $options, 'rules' => ['required', Rule::in(array_keys($options))]];
        $relation = fn (string $table, string $value, string $label, bool $required = false): array => ['type' => 'select', 'relation' => compact('table', 'value', 'label'), 'rules' => [$required ? 'required' : 'nullable', 'integer']];

        return [
            'paginas' => ['title' => 'Páginas', 'table' => 'paginas', 'primary' => 'id_pagina', 'fields' => ['titulo' => $text(true), 'slug' => $text(true), 'contenido' => $textarea(), 'imagen' => $image(), 'publicado' => $checkbox()]],
            'menus' => ['title' => 'Menús', 'table' => 'menus', 'primary' => 'id_menu', 'fields' => ['id_padre' => $relation('menus', 'id_menu', 'titulo'), 'titulo' => $text(true), 'slug' => $text(true), 'icono' => $text(), 'orden' => $number(), 'visible' => $checkbox()]],
            'banners' => ['title' => 'Banners', 'table' => 'banners', 'primary' => 'id_banner', 'fields' => ['titulo' => $text(), 'imagen' => $image(true), 'enlace' => $text(), 'orden' => $number(), 'activo' => $checkbox()]],
            'configuracion' => ['title' => 'Configuración', 'table' => 'configuracion', 'primary' => 'clave', 'fields' => ['clave' => ['type' => 'text', 'readonly' => true, 'rules' => ['required', 'string', 'max:50']], 'valor' => $textarea()]],
            'niveles' => ['title' => 'Niveles formativos', 'table' => 'niveles', 'primary' => 'id_nivel', 'fields' => ['nombre' => $text(true), 'descripcion' => $textarea(), 'imagen' => $image(), 'orden' => $number()]],
            'personal' => ['title' => 'Personal', 'table' => 'personal', 'primary' => 'id_personal', 'fields' => ['nombres' => $text(true), 'apellidos' => $text(true), 'cargo' => $text(), 'area' => $select(['directivo' => 'Directivo', 'docente' => 'Docente', 'administrativo' => 'Administrativo', 'apoyo' => 'Apoyo']), 'id_nivel' => $relation('niveles', 'id_nivel', 'nombre'), 'especialidad' => $text(), 'email' => ['type' => 'email', 'rules' => ['nullable', 'email', 'max:120']], 'foto' => $image(), 'orden' => $number(), 'activo' => $checkbox()]],
            'exalumnos' => ['title' => 'Exalumnos', 'table' => 'exalumnos', 'primary' => 'id_exalumno', 'fields' => ['nombres' => $text(true), 'apellidos' => $text(true), 'promocion' => ['type' => 'number', 'rules' => ['required', 'integer', 'between:1901,2155']], 'profesion' => $text(), 'email' => ['type' => 'email', 'rules' => ['nullable', 'email', 'max:120']], 'telefono' => $text(), 'testimonio' => $textarea(), 'foto' => $image(), 'aprobado' => $checkbox()]],
            'solicitudes_ingreso' => ['title' => 'Solicitudes de ingreso', 'table' => 'solicitudes_ingreso', 'primary' => 'id_solicitud', 'fields' => ['nombres_alumno' => $text(true), 'apellidos_alumno' => $text(true), 'fecha_nacimiento' => ['type' => 'date', 'rules' => ['nullable', 'date']], 'id_nivel' => $relation('niveles', 'id_nivel', 'nombre'), 'grado' => $text(), 'apoderado' => $text(true), 'telefono' => $text(true), 'email' => ['type' => 'email', 'rules' => ['nullable', 'email', 'max:120']], 'comentario' => $textarea(), 'estado' => $select(['pendiente' => 'Pendiente', 'contactado' => 'Contactado', 'aceptado' => 'Aceptado', 'rechazado' => 'Rechazado'])]],
            'noticias' => ['title' => 'Noticias', 'table' => 'noticias', 'primary' => 'id_noticia', 'fields' => ['id_categoria' => $relation('categorias_noticias', 'id_categoria', 'nombre'), 'id_usuario' => $relation('usuarios', 'id_usuario', 'nombres'), 'titulo' => $text(true), 'slug' => $text(true), 'resumen' => $text(), 'contenido' => $textarea(true), 'imagen' => $image(), 'destacada' => $checkbox(), 'publicado' => $checkbox(), 'fecha_publicacion' => ['type' => 'datetime-local', 'rules' => ['nullable', 'date']]]],
            'categorias_noticias' => ['title' => 'Categorías de noticias', 'table' => 'categorias_noticias', 'primary' => 'id_categoria', 'fields' => ['nombre' => $text(true), 'slug' => $text(true)]],
            'comunicados' => ['title' => 'Comunicados', 'table' => 'comunicados', 'primary' => 'id_comunicado', 'fields' => ['titulo' => $text(true), 'contenido' => $textarea(), 'archivo' => $text(), 'fecha' => ['type' => 'date', 'rules' => ['required', 'date']], 'publicado' => $checkbox()]],
            'documentos' => ['title' => 'Documentos', 'table' => 'documentos', 'primary' => 'id_documento', 'fields' => ['titulo' => $text(true), 'categoria' => $text(), 'archivo' => $text(true), 'publicado' => $checkbox()]],
            'galerias' => ['title' => 'Galerías', 'table' => 'galerias', 'primary' => 'id_galeria', 'fields' => ['titulo' => $text(true), 'descripcion' => $text(), 'portada' => $image(), 'fecha' => ['type' => 'date', 'rules' => ['nullable', 'date']], 'publicado' => $checkbox()]],
            'galeria_items' => ['title' => 'Elementos de galería', 'table' => 'galeria_items', 'primary' => 'id_item', 'fields' => ['id_galeria' => $relation('galerias', 'id_galeria', 'titulo', true), 'tipo' => $select(['imagen' => 'Imagen', 'video' => 'Video']), 'ruta' => $text(true), 'descripcion' => $text()]],
            'mensajes_contacto' => ['title' => 'Mensajes de contacto', 'table' => 'mensajes_contacto', 'primary' => 'id_mensaje', 'fields' => ['nombre' => $text(true), 'email' => ['type' => 'email', 'rules' => ['required', 'email', 'max:120']], 'telefono' => $text(), 'asunto' => $text(), 'mensaje' => $textarea(true), 'leido' => $checkbox()]],
            'reclamaciones' => ['title' => 'Libro de reclamaciones', 'table' => 'reclamaciones', 'primary' => 'id_reclamo', 'fields' => ['codigo' => $text(true), 'nombres' => $text(true), 'documento_tipo' => $select(['DNI' => 'DNI', 'CE' => 'CE', 'Pasaporte' => 'Pasaporte']), 'documento_num' => $text(true), 'direccion' => $text(), 'telefono' => $text(), 'email' => ['type' => 'email', 'rules' => ['required', 'email', 'max:120']], 'es_menor' => $checkbox(), 'apoderado' => $text(), 'tipo' => $select(['reclamo' => 'Reclamo', 'queja' => 'Queja']), 'detalle' => $textarea(true), 'pedido' => $textarea(), 'estado' => $select(['recibido' => 'Recibido', 'en_proceso' => 'En proceso', 'atendido' => 'Atendido']), 'respuesta' => $textarea(), 'fecha_respuesta' => ['type' => 'datetime-local', 'rules' => ['nullable', 'date']]]],
            'enlaces' => ['title' => 'Enlaces', 'table' => 'enlaces', 'primary' => 'id_enlace', 'fields' => ['nombre' => $text(true), 'url' => ['type' => 'url', 'rules' => ['required', 'url', 'max:255']], 'logo' => $text(), 'tipo' => $select(['institucional' => 'Institucional', 'interes' => 'Interés', 'red_social' => 'Red social']), 'orden' => $number(), 'activo' => $checkbox()]],
            'usuarios' => ['title' => 'Administradores', 'table' => 'usuarios', 'primary' => 'id_usuario', 'fields' => ['nombres' => $text(true), 'email' => ['type' => 'email', 'rules' => ['required', 'email', 'max:120']], 'password' => ['type' => 'password', 'hash' => true, 'rules' => ['nullable', 'string', 'min:8']], 'rol' => $select(['administrador' => 'Administrador', 'editor' => 'Editor']), 'estado' => $select(['activo' => 'Activo', 'inactivo' => 'Inactivo'])]],
        ];
    }
}
