<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileStorageService
{
    private array $allowedMimes = [
        'image/jpeg', 'image/jpg', 'image/png', 'application/pdf',
    ];

    private array $allowedExtensions = [
        'jpg', 'jpeg', 'png', 'pdf',
    ];

    private int $maxSizeKB = 2048; // 2MB

    /**
     * Store a file in the private disk under the given directory.
     */
    public function store(UploadedFile $file, string $directory): array
    {
        $this->validate($file);

        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid() . '.' . strtolower($extension);
        $path = Storage::disk(config('filesystems.private_disk'))->putFileAs($directory, $file, $filename);

        return [
            'path'      => $path,
            'type'      => $this->resolveType($extension),
            'filename'  => $filename,
            'original'  => $file->getClientOriginalName(),
            'size'      => $file->getSize(),
        ];
    }

    /**
     * Delete a private file.
     */
    public function delete(string $path): bool
    {
        return Storage::disk(config('filesystems.private_disk'))->delete($path);
    }

    /**
     * Get the contents of a private file as a response.
     */
    public function download(string $path, ?string $filename = null)
    {
        if (!Storage::disk(config('filesystems.private_disk'))->exists($path)) {
            abort(404);
        }

        return Storage::disk(config('filesystems.private_disk'))->download($path, $filename);
    }

    /**
     * Stream a private file for inline display.
     */
    public function inline(string $path)
    {
        if (!Storage::disk(config('filesystems.private_disk'))->exists($path)) {
            abort(404);
        }

        $content = Storage::disk(config('filesystems.private_disk'))->get($path);
        $mime = Storage::disk(config('filesystems.private_disk'))->mimeType($path);

        return response($content, 200)->header('Content-Type', $mime);
    }

    private function validate(UploadedFile $file): void
    {
        if (!in_array(strtolower($file->getClientOriginalExtension()), $this->allowedExtensions)) {
            abort(422, 'Format file tidak didukung. Gunakan JPG, PNG, atau PDF.');
        }

        if (!in_array($file->getMimeType(), $this->allowedMimes)) {
            abort(422, 'Tipe file tidak valid.');
        }

        if ($file->getSize() > $this->maxSizeKB * 1024) {
            abort(422, 'Ukuran file maksimal 5MB.');
        }
    }

    private function resolveType(string $extension): string
    {
        return match (strtolower($extension)) {
            'pdf'           => 'pdf',
            'jpg', 'jpeg', 'png' => 'image',
            default         => 'other',
        };
    }
}
