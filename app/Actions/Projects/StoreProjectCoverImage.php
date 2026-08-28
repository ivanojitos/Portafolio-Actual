<?php

namespace App\Actions\Projects;

use App\Models\Media;
use App\Models\Project;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class StoreProjectCoverImage
{
    public function execute(
        Project $project,
        UploadedFile $file,
        string $altText
    ): Media {
        $path = $file->store(
            'projects/'.$project->getKey(),
            'public'
        );

        try {
            return DB::transaction(
                function () use (
                    $project,
                    $file,
                    $altText,
                    $path
                ): Media {
                    $project->media()->update([
                        'is_primary' => false,
                    ]);

                    return $project->media()->create([
                        'disk' => 'public',
                        'path' => $path,

                        'original_name' => Str::limit(
                            basename($file->getClientOriginalName()),
                            255,
                            ''
                        ),

                        'mime_type' => $file->getMimeType()
                            ?? 'application/octet-stream',

                        'size_bytes' => $file->getSize(),

                        'alt_text' => trim($altText),
                        'metadata' => null,
                        'is_primary' => true,
                        'position' => 0,
                    ]);
                }
            );
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($path);

            throw $exception;
        }
    }
}
