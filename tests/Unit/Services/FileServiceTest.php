<?php

namespace Tests\Unit\Services;

use App\Service\Contract\FileServiceContract;
use Faker\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Smknstd\FakerPicsumImages\FakerPicsumImagesProvider;
use Tests\TestCase;

class FileServiceTest extends TestCase
{
    #[Test]
    public function it_upload_file_success()
    {
        Storage::fake('public');
        $path = 'uploads';
        $faker = Factory::create();
        $faker->addProvider(new FakerPicsumImagesProvider($faker));
        $fileUrl = $faker->imageUrl(640, 480);
        $imageContents = file_get_contents($fileUrl);
        $tempPath = sys_get_temp_dir().'/'.uniqid().'.jpg';
        file_put_contents($tempPath, $imageContents);
        $file = new UploadedFile(
            $tempPath,
            'faker_image.jpg',
            'image/jpeg',
            null,
            true
        );
        $service = app(FileServiceContract::class);
        $imagePath = $service->upload($file, $path);
        Storage::disk('public')->assertExists($path);
        Storage::disk('public')->assertExists($imagePath);
        $this->assertSame('public', Storage::getVisibility($imagePath));
    }
}
