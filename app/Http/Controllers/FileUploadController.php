<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;
use App\Http\Resources\FileResource;
use App\Models\PersonsPhoto;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public function upload(FileUploadRequest $request): JsonResponse
    {
        $file      = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $hash      = md5_file($file->getRealPath()) . '.' . $extension;

        $path = $file->storeAs('uploads', $hash, 'minio');

        $bucket = config('filesystems.disks.minio.bucket');

        $personsPhoto = PersonsPhoto::create([
            'pes_id' => $request->pes_id,
            'data'   => now()->format('Y-m-d'),
            'bucket' => $bucket,
            'hash'   => $hash,
        ]);

        return (new FileResource((object) [
            'id'   => $personsPhoto->id,
            'path' => $path,
            'url'  => Storage::disk('minio')->temporaryUrl("uploads/{$hash}", now()->addMinutes(5)),
        ]))->response()->setStatusCode(201);
    }
}
