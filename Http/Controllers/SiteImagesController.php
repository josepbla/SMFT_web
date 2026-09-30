<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class SiteImagesController extends Controller
{
    public const DEFAULTS = [
        'home_hero' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=2000&q=85',
        'services_hero' => 'https://www.lareparacion.edu.pe/sitepad-data/uploads/2026/05/IMAGEN-2.jpeg',
        'contact_1' => 'https://www.lareparacion.edu.pe/sitepad-data/uploads/2026/05/SANTISIMO-1.jpeg',
        'contact_2' => 'https://www.lareparacion.edu.pe/sitepad-data/uploads/2026/05/IMAGEN-2.jpeg',
        'contact_3' => 'https://www.lareparacion.edu.pe/sitepad-data/uploads/2026/05/IMAGEN-55.jpeg',
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
        }

        $configDirectory = storage_path('app');
        if (! is_dir($configDirectory)) {
            mkdir($configDirectory, 0755, true);
        }
        if (@file_put_contents(storage_path('app/site-images.json'), json_encode($images, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX) === false) {
            return back()->withErrors(['upload' => 'Las imágenes se subieron, pero no se pudo guardar la configuración.']);
        }

        return redirect()->route('site-images.edit')->with('success', 'Las imágenes del proyecto fueron guardadas correctamente.');
    }

    public static function value(string $key): string
    {
        return self::all()[$key] ?? self::DEFAULTS[$key] ?? '';
    }

    public static function all(): array
    {
        $path = storage_path('app/site-images.json');
        if (! file_exists($path)) {
            return self::DEFAULTS;
        }
        $saved = json_decode(file_get_contents($path), true);
        return array_merge(self::DEFAULTS, is_array($saved) ? $saved : []);
    }
}