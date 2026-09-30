<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Str;

class SiteImagesController extends Controller
{
    private const CONFIG_PREFIX = 'site_image_';

    public const DEFAULTS = [
        'home_hero' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=2000&q=85',
        'services_hero' => 'https://www.lareparacion.edu.pe/sitepad-data/uploads/2026/05/IMAGEN-2.jpeg',
        'contact_1' => 'https://www.lareparacion.edu.pe/sitepad-data/uploads/2026/05/SANTISIMO-1.jpeg',
        'contact_2' => 'https://www.lareparacion.edu.pe/sitepad-data/uploads/2026/05/IMAGEN-2.jpeg',
        'contact_3' => 'https://www.lareparacion.edu.pe/sitepad-data/uploads/2026/05/IMAGEN-55.jpeg',
        'services_gallery_1' => 'https://www.lareparacion.edu.pe/sitepad-data/uploads/2026/05/SANTISIMO-1.jpeg',
        'services_gallery_2' => 'https://www.lareparacion.edu.pe/sitepad-data/uploads/2026/05/IMAGEN-2.jpeg',
        'services_gallery_3' => 'https://www.lareparacion.edu.pe/sitepad-data/uploads/2026/05/IMAGEN-9.jpeg',
        'services_gallery_4' => 'https://www.lareparacion.edu.pe/sitepad-data/uploads/2026/05/IMAGEN-5.jpeg',
        'services_gallery_5' => 'https://www.lareparacion.edu.pe/sitepad-data/uploads/2026/05/IMAGEN-55.jpeg',
        'services_gallery_6' => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?auto=format&fit=crop&w=900&q=80',
    ];

    public function edit(): View
    {
        return view('admin.site-images', ['images' => self::all()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = [];
        foreach (array_keys(self::DEFAULTS) as $key) {
            $rules[$key] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:102400'];
        }
        $request->validate($rules);

        $images = self::all();
        $directory = public_path('uploads/site');
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        $writeProbe = $directory.DIRECTORY_SEPARATOR.'.write-test-'.Str::uuid();
        if (@file_put_contents($writeProbe, '') === false || ! @unlink($writeProbe)) {
            return back()->withErrors(['upload' => 'La carpeta de imágenes no tiene permisos de escritura.']);
        }

        foreach (array_keys(self::DEFAULTS) as $key) {
            if (! $request->hasFile($key)) {
                continue;
            }
            $file = $request->file($key);
            if (! $file->isValid()) {
                return back()->withErrors([$key => 'No se pudo recibir la imagen: '.$file->getErrorMessage()]);
            }

            $filename = $key.'-'.Str::uuid().'.'.$file->extension();
            $contents = @file_get_contents($file->getRealPath());
            $destination = $directory.DIRECTORY_SEPARATOR.$filename;
            if ($contents === false || @file_put_contents($destination, $contents, LOCK_EX) === false) {
                return back()->withErrors([$key => 'No se pudo guardar esta imagen en el servidor.']);
            }

            $images[$key] = '/uploads/site/'.$filename;
            DB::table('configuracion')->updateOrInsert(
                ['clave' => self::CONFIG_PREFIX.$key],
                ['valor' => $images[$key]],
            );
        }

        return redirect()->route('site-images.edit')->with('success', 'Las imágenes del proyecto fueron guardadas correctamente.');
    }

    public static function value(string $key): string
    {
        return self::all()[$key] ?? self::DEFAULTS[$key] ?? '';
    }

    public static function all(): array
    {
        $keys = array_map(fn (string $key): string => self::CONFIG_PREFIX.$key, array_keys(self::DEFAULTS));
        $saved = DB::table('configuracion')->whereIn('clave', $keys)->pluck('valor', 'clave')->all();
        $images = self::DEFAULTS;

        foreach (array_keys(self::DEFAULTS) as $key) {
            $value = $saved[self::CONFIG_PREFIX.$key] ?? null;
            if (is_string($value) && $value !== '') {
                $images[$key] = $value;
            }
        }

        return $images;
    }
}