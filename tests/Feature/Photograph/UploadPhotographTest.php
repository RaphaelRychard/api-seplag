<?php

declare(strict_types = 1);

use App\Models\Person;
use Illuminate\Contracts\Auth\Authenticatable;

use Illuminate\Http\UploadedFile;

use function Pest\Laravel\postJson;

beforeEach(fn (): Authenticatable => login());

it('should upload a valid image file', function () {
    Storage::fake('minio');

    $file   = UploadedFile::fake()->create('photo.jpg', 100, 'image/jpeg');
    $person = Person::factory()->create();

    $response = postJson(route('api.photograph.upload'), [
        'file'   => $file,
        'pes_id' => $person->id,
    ]);

    $response->assertCreated()
        ->assertJsonStructure(['data' => ['id', 'path', 'url'],
        ]);

    Storage::disk('minio')
        ->assertExists('uploads/' . md5_file($file->getRealPath()) . '.jpg');
});

it('should return validation errors if file and pes_id are missing', function () {
    $response = postJson(route('api.photograph.upload'), []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['file', 'pes_id']);
});

it('should return validation error if pes_id does not exist', function () {
    Storage::fake('minio');

    $file = UploadedFile::fake()->create('photo.jpg', 100, 'image/jpeg');

    $response = postJson(route('api.photograph.upload'), [
        'file'   => $file,
        'pes_id' => 9999,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['pes_id']);
});

it('should fail if file type is not allowed', function () {
    Storage::fake('minio');

    $file   = UploadedFile::fake()->create('file.pdf', 1000, 'application/pdf');
    $person = Person::factory()->create();

    $response = postJson(route('api.photograph.upload'), [
        'file'   => $file,
        'pes_id' => $person->id,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['file']);
});

it('should fail if the file exceeds the maximum allowed size', function () {
    Storage::fake('minio');

    $content = file_get_contents(__DIR__ . '/../../Fixtures/big_image_6000.jpg');
    $tooBig  = UploadedFile::fake()->createWithContent('photo_teste_big_image.jpg', $content);
    $person  = Person::factory()->create();

    $response = postJson(route('api.photograph.upload'), [
        'file'   => $tooBig,
        'pes_id' => $person->id,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['file']);
});

it('should fail if file mimetype does not match allowed types', function () {
    Storage::fake('minio');

    $content = file_get_contents(__DIR__ . '/../../Fixtures/Edital.pdf');

    $file   = UploadedFile::fake()->createWithContent('Edital.pdf', $content);
    $person = Person::factory()->create();

    $response = postJson(route('api.photograph.upload'), [
        'file'   => $file,
        'pes_id' => $person->id,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['file']);
});

it('should return a temporary URL for the uploaded file', function () {
    Storage::fake('minio');

    $content = file_get_contents(__DIR__ . '/../../Fixtures/small_image_640.jpg');

    $file   = UploadedFile::fake()->createWithContent('photo_teste.jpg', $content);
    $person = Person::factory()->create();

    $response = postJson(route('api.photograph.upload'), [
        'file'   => $file,
        'pes_id' => $person->id,
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.url', fn ($url) => str_contains($url, '/uploads/'));
});
