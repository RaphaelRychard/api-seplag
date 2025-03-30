<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;
use App\Http\Resources\FileResource;
use App\Models\PersonsPhoto;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public function upload(FileUploadRequest $request)
    {
        // Realiza o upload
        $file = $request->file('file');

        $hash = md5_file($file->getRealPath());

        $bucket = config('filesystems.disks.minio.bucket');

        $path = $file->store('uploads', 'minio');

        $personsPhoto = PersonsPhoto::create([
            'pes_id' => $request->pes_id,
            'data'   => now()->format('Y-m-d'),
            'bucket' => $bucket,
            'hash'   => $hash,
            'path'   => $path,
        ]);

        return new FileResource((object)[
            'id'   => $personsPhoto->id,
            'path' => $path,
            'url'  => Storage::disk('minio')->temporaryUrl($path, now()->addMinutes(5)),
        ]);
    }
}
