<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File; // Added for file operations
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Photo>
 */
class PhotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'file_path' => function () {
                $placeholderDir = database_path('seeders/media/placeholders');
                $files = File::glob($placeholderDir . '/*.{jpg,jpeg,png}', GLOB_BRACE);

                Storage::disk('public')->makeDirectory('photos');

                if (!empty($files)) {
                    $sourcePath = $files[array_rand($files)];
                    $filename = uniqid('photo_', true) . '.' . File::extension($sourcePath);
                    $destinationPath = 'photos/' . $filename;

                    // Copy the file to public storage
                    Storage::disk('public')->put($destinationPath, File::get($sourcePath));
                    return $destinationPath;
                } else {
                    // Fallback if no placeholder images are found
                    $fakeImage = UploadedFile::fake()->image($this->faker->unique()->word . '.jpg', 640, 480);
                    return $fakeImage->store('photos', 'public');
                }
            },
            'user_id' => User::factory(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => fn (array $attributes) => $this->faker->dateTimeBetween($attributes['created_at'], 'now'),
        ];
    }
}
