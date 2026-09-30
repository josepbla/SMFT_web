<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Str;

class PastoralController extends Controller
{
    private const CONFIG_PREFIX = 'pastoral_image_';

    private const DEFAULT_IMAGES = [
        'hero' => 'https://www.lareparacion.edu.pe/sitepad-data/uploads/2026/05/Capilla.jpeg',
        'gallery_1' => 'https://www.lareparacion.edu.pe/sitepad-data/uploads/2026/05/SANTISIMO-1.jpeg',
        'gallery_2' => 'https://www.lareparacion.edu.pe/sitepad-data/uploads/2026/06/Pastoral-266-Virgen-Maria.jpg',
        'gallery_3' => 'https://www.lareparacion.edu.pe/sitepad-data/uploads/2026/05/IMAGEN-55.jpeg',
        'gallery_4' => 'https://www.lareparacion.edu.pe/sitepad-data/uploads/2026/06/20250606_084713.jpg',
        'gallery_5' => 'https://www.lareparacion.edu.pe/sitepad-data/uploads/2026/05/Capilla.jpeg',
        'gallery_6' => 'https://www.lareparacion.edu.pe/sitepad-data/uploads/2026/05/WhatsApp-Image-2023-09-10-at-12.18.04-AM.jpeg',
    ];

    public function show(): View
    {
        return view('pastoral', [
            'images' => $this->images(),
            'menus' => DB::table('menus')->whereNull('id_padre')->where('visible', true)->orderBy('orden')->get(),
        ]);
    }

    public function edit(): View
    {
        return view('admin.pastoral-images', ['images' => $this->images()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'hero' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:102400'],
            'gallery_1' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:102400'],
            'gallery_2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:102400'],
            'gallery_3' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:102400'],
            'gallery_4' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:102400'],
            'gallery_5' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:102400'],
            'gallery_6' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:102400'],
        ]);

        $images = $this->images();
        $directory = public_path('uploads/pastoral');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        foreach (array_keys(self::DEFAULT_IMAGES) as $field) {
            if (! $request->hasFile($field)) {
                continue;
            }

            $file = $request->file($field);
            $filename = $field.'-'.Str::uuid().'.'.$file->extension();
            $contents = @file_get_contents($file->getRealPath());
            $destination = $directory.DIRECTORY_SEPARATOR.$filename;
            if ($contents === false || @file_put_contents($destination, $contents, LOCK_EX) === false) {
                return back()->withErrors([$field => 'No se pudo guardar esta imagen en el servidor.']);
            }

            $images[$field] = '/uploads/pastoral/'.$filename;
            DB::table('configuracion')->updateOrInsert(
                ['clave' => self::CONFIG_PREFIX.$field],
                ['valor' => $images[$field]],
            );
        }

        return redirect()->route('pastoral.edit')->with('success', 'Las imágenes de Pastoral fueron actualizadas.');
    }

    private function images(): array
    {
        $keys = array_map(fn (string $key): string => self::CONFIG_PREFIX.$key, array_keys(self::DEFAULT_IMAGES));
        $saved = DB::table('configuracion')->whereIn('clave', $keys)->pluck('valor', 'clave')->all();
        $images = self::DEFAULT_IMAGES;

        foreach (array_keys(self::DEFAULT_IMAGES) as $key) {
            $value = $saved[self::CONFIG_PREFIX.$key] ?? null;
            if (is_string($value) && $value !== '') {
                $images[$key] = $value;
            }
        }

        return $images;
    }
}