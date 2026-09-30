<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Str;

class PastoralController extends Controller
{
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
            'hero' => ['nullable', 'file', 'max:102400'],
            'gallery_1' => ['nullable', 'file', 'max:102400'],
            'gallery_2' => ['nullable', 'file', 'max:102400'],
            'gallery_3' => ['nullable', 'file', 'max:102400'],
            'gallery_4' => ['nullable', 'file', 'max:102400'],
            'gallery_5' => ['nullable', 'file', 'max:102400'],
            'gallery_6' => ['nullable', 'file', 'max:102400'],
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
            $file->move($directory, $filename);
            $images[$field] = '/uploads/pastoral/'.$filename;
        }

        $configDirectory = storage_path('app');
        if (! is_dir($configDirectory)) {
            mkdir($configDirectory, 0755, true);
        }
        file_put_contents($this->configPath(), json_encode($images, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);

        return redirect()->route('pastoral.edit')->with('success', 'Las imágenes de Pastoral fueron actualizadas.');
    }

    private function images(): array
    {
        if (! file_exists($this->configPath())) {
            return self::DEFAULT_IMAGES;
        }

        $saved = json_decode(file_get_contents($this->configPath()), true);

        return array_merge(self::DEFAULT_IMAGES, is_array($saved) ? $saved : []);
    }

    private function configPath(): string
    {
        return storage_path('app/pastoral-images.json');
    }
}