<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->unsignedInteger('id_usuario')->autoIncrement();
            $table->string('nombres', 80);
            $table->string('email', 120)->unique();
            $table->string('password');
            $table->enum('rol', ['administrador', 'editor'])->default('editor');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->dateTime('ultimo_acceso')->nullable();
            $table->timestamp('creado_en')->useCurrent();
        });

        Schema::create('configuracion', function (Blueprint $table) {
            $table->string('clave', 50)->primary();
            $table->text('valor')->nullable();
        });

        Schema::create('menus', function (Blueprint $table) {
            $table->unsignedInteger('id_menu')->autoIncrement();
            $table->unsignedInteger('id_padre')->nullable();
            $table->string('titulo', 60);
            $table->string('slug', 80)->unique();
            $table->string('icono')->nullable();
            $table->unsignedTinyInteger('orden')->default(0);
            $table->boolean('visible')->default(true);
            $table->foreign('id_padre')->references('id_menu')->on('menus')->cascadeOnDelete();
        });

        Schema::create('paginas', function (Blueprint $table) {
            $table->unsignedInteger('id_pagina')->autoIncrement();
            $table->unsignedInteger('id_menu')->nullable();
            $table->string('titulo', 150);
            $table->string('slug', 160)->unique();
            $table->mediumText('contenido')->nullable();
            $table->string('imagen')->nullable();
            $table->boolean('publicado')->default(true);
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('id_menu')->references('id_menu')->on('menus')->nullOnDelete();
        });

        Schema::create('banners', function (Blueprint $table) {
            $table->unsignedInteger('id_banner')->autoIncrement();
            $table->string('titulo', 120)->nullable();
            $table->string('imagen');
            $table->string('enlace')->nullable();
            $table->unsignedTinyInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
        });

        Schema::create('niveles', function (Blueprint $table) {
            $table->unsignedTinyInteger('id_nivel')->autoIncrement();
            $table->string('nombre', 40)->unique();
            $table->text('descripcion')->nullable();
            $table->string('imagen')->nullable();
            $table->unsignedTinyInteger('orden')->default(0);
        });

        Schema::create('categorias_noticias', function (Blueprint $table) {
            $table->unsignedSmallInteger('id_categoria')->autoIncrement();
            $table->string('nombre', 50)->unique();
            $table->string('slug', 60)->unique();
        });

        Schema::create('noticias', function (Blueprint $table) {
            $table->unsignedInteger('id_noticia')->autoIncrement();
            $table->unsignedSmallInteger('id_categoria')->nullable();
            $table->unsignedInteger('id_usuario')->nullable();
            $table->string('titulo', 200);
            $table->string('slug', 220)->unique();
            $table->string('resumen', 400)->nullable();
            $table->mediumText('contenido');
            $table->string('imagen')->nullable();
            $table->boolean('destacada')->default(false);
            $table->boolean('publicado')->default(false);
            $table->dateTime('fecha_publicacion')->nullable();
            $table->timestamp('creado_en')->useCurrent();
            $table->foreign('id_categoria')->references('id_categoria')->on('categorias_noticias')->nullOnDelete();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->nullOnDelete();
        });

        Schema::create('comunicados', function (Blueprint $table) {
            $table->unsignedInteger('id_comunicado')->autoIncrement();
            $table->string('titulo', 200);
            $table->text('contenido')->nullable();
            $table->string('archivo')->nullable();
            $table->date('fecha');
            $table->boolean('publicado')->default(true);
        });

        Schema::create('documentos', function (Blueprint $table) {
            $table->unsignedInteger('id_documento')->autoIncrement();
            $table->string('titulo', 150);
            $table->string('categoria', 50)->nullable();
            $table->string('archivo');
            $table->boolean('publicado')->default(true);
            $table->timestamp('subido_en')->useCurrent();
        });

        Schema::create('personal', function (Blueprint $table) {
            $table->unsignedInteger('id_personal')->autoIncrement();
            $table->string('nombres', 80);
            $table->string('apellidos', 80);
            $table->string('cargo', 80)->nullable();
            $table->enum('area', ['directivo', 'docente', 'administrativo', 'apoyo'])->default('docente');
            $table->unsignedTinyInteger('id_nivel')->nullable();
            $table->string('especialidad', 80)->nullable();
            $table->string('email', 120)->nullable();
            $table->string('foto')->nullable();
            $table->unsignedSmallInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->foreign('id_nivel')->references('id_nivel')->on('niveles')->nullOnDelete();
        });

        Schema::create('enlaces', function (Blueprint $table) {
            $table->unsignedInteger('id_enlace')->autoIncrement();
            $table->string('nombre', 80);
            $table->string('url');
            $table->string('logo')->nullable();
            $table->enum('tipo', ['institucional', 'interes', 'red_social'])->default('institucional');
            $table->unsignedTinyInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
        });

        Schema::create('galerias', function (Blueprint $table) {
            $table->unsignedInteger('id_galeria')->autoIncrement();
            $table->string('titulo', 120);
            $table->string('descripcion', 255)->nullable();
            $table->string('portada')->nullable();
            $table->date('fecha')->nullable();
            $table->boolean('publicado')->default(true);
        });

        Schema::create('galeria_items', function (Blueprint $table) {
            $table->unsignedInteger('id_item')->autoIncrement();
            $table->unsignedInteger('id_galeria');
            $table->enum('tipo', ['imagen', 'video'])->default('imagen');
            $table->string('ruta');
            $table->string('descripcion', 150)->nullable();
            $table->foreign('id_galeria')->references('id_galeria')->on('galerias')->cascadeOnDelete();
        });

        Schema::create('exalumnos', function (Blueprint $table) {
            $table->unsignedInteger('id_exalumno')->autoIncrement();
            $table->string('nombres', 80);
            $table->string('apellidos', 80);
            $table->year('promocion');
            $table->string('profesion', 100)->nullable();
            $table->string('email', 120)->nullable();
            $table->string('telefono', 15)->nullable();
            $table->text('testimonio')->nullable();
            $table->string('foto')->nullable();
            $table->boolean('aprobado')->default(false);
            $table->timestamp('registrado_en')->useCurrent();
        });

        Schema::create('solicitudes_ingreso', function (Blueprint $table) {
            $table->unsignedInteger('id_solicitud')->autoIncrement();
            $table->string('nombres_alumno', 80);
            $table->string('apellidos_alumno', 80);
            $table->date('fecha_nacimiento')->nullable();
            $table->unsignedTinyInteger('id_nivel')->nullable();
            $table->string('grado', 30)->nullable();
            $table->string('apoderado', 120);
            $table->string('telefono', 15);
            $table->string('email', 120)->nullable();
            $table->text('comentario')->nullable();
            $table->enum('estado', ['pendiente', 'contactado', 'aceptado', 'rechazado'])->default('pendiente');
            $table->timestamp('enviado_en')->useCurrent();
            $table->foreign('id_nivel')->references('id_nivel')->on('niveles')->nullOnDelete();
        });

        Schema::create('reclamaciones', function (Blueprint $table) {
            $table->unsignedInteger('id_reclamo')->autoIncrement();
            $table->string('codigo', 20)->unique();
            $table->string('nombres', 120);
            $table->enum('documento_tipo', ['DNI', 'CE', 'Pasaporte'])->default('DNI');
            $table->string('documento_num', 15);
            $table->string('direccion', 150)->nullable();
            $table->string('telefono', 15)->nullable();
            $table->string('email', 120);
            $table->boolean('es_menor')->default(false);
            $table->string('apoderado', 120)->nullable();
            $table->enum('tipo', ['reclamo', 'queja']);
            $table->text('detalle');
            $table->text('pedido')->nullable();
            $table->enum('estado', ['recibido', 'en_proceso', 'atendido'])->default('recibido');
            $table->text('respuesta')->nullable();
            $table->timestamp('fecha_registro')->useCurrent();
            $table->dateTime('fecha_respuesta')->nullable();
        });

        Schema::create('mensajes_contacto', function (Blueprint $table) {
            $table->unsignedInteger('id_mensaje')->autoIncrement();
            $table->string('nombre', 100);
            $table->string('email', 120);
            $table->string('telefono', 15)->nullable();
            $table->string('asunto', 150)->nullable();
            $table->text('mensaje');
            $table->boolean('leido')->default(false);
            $table->timestamp('enviado_en')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mensajes_contacto');
        Schema::dropIfExists('reclamaciones');
        Schema::dropIfExists('solicitudes_ingreso');
        Schema::dropIfExists('exalumnos');
        Schema::dropIfExists('galeria_items');
        Schema::dropIfExists('galerias');
        Schema::dropIfExists('enlaces');
        Schema::dropIfExists('personal');
        Schema::dropIfExists('documentos');
        Schema::dropIfExists('comunicados');
        Schema::dropIfExists('noticias');
        Schema::dropIfExists('categorias_noticias');
        Schema::dropIfExists('niveles');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('paginas');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('configuracion');
        Schema::dropIfExists('usuarios');
    }
};
