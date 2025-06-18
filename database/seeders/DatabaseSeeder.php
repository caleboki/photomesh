<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Photo;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 5 users
        $users = User::factory(5)->create();

        // Create 30 photos, assigning them to users
        $photos = Photo::factory(30)->make()->each(function ($photo) use ($users) {
            $photo->user_id = $users->random()->id;
            $photo->save();
        });

        // Create 50 bookmarks
        $bookmarksToCreate = 50;
        $createdBookmarks = 0;
        $bookmarkCombinations = []; // To track unique user_id, photo_id pairs

        while ($createdBookmarks < $bookmarksToCreate && $photos->isNotEmpty() && $users->isNotEmpty()) {
            $user = $users->random();
            $photo = $photos->random();

            // Ensure user doesn't bookmark their own photo
            if ($user->id === $photo->user_id) {
                continue;
            }

            $combination = $user->id . '-' . $photo->id;

            // Ensure the bookmark is unique
            if (!in_array($combination, $bookmarkCombinations)) {
                $user->bookmarks()->attach($photo->id);
                $bookmarkCombinations[] = $combination;
                $createdBookmarks++;
            }
            // Safety break if we can't find new combinations (e.g., all possible valid bookmarks made)
            if (count($bookmarkCombinations) >= $users->count() * $photos->count()) {
                break;
            }
        }

        // Example of calling other seeders if they exist (like the PhotoSeeder we created but might not use directly)
        // $this->call([
        //     PhotoSeeder::class,
        // ]);
    }
}
