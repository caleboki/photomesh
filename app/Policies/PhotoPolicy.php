<?php

namespace App\Policies;

use App\Models\Photo;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PhotoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Allow viewing lists of photos for now
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Photo $photo): bool
    {
        return true; // Allow viewing individual photos for now
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can upload photos
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Photo $photo): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Photo $photo): bool
    {
        return $user->id === $photo->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Photo $photo): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Photo $photo): bool
    {
        return false;
    }

    /**
     * Determine whether the user can bookmark the photo.
     */
    public function bookmark(User $user, Photo $photo): bool
    {
        // Prevent users from bookmarking their own photos
        if ($user->id === $photo->user_id) {
            return false;
        }
        // Prevent adding a duplicate bookmark
        return !$user->bookmarks()->where('photo_id', $photo->id)->exists();
    }

    /**
     * Determine whether the user can remove a bookmark from the photo.
     */
    public function unbookmark(User $user, Photo $photo): bool
    {
        // A user can unbookmark a photo if they have bookmarked it
        return $user->bookmarks()->where('photo_id', $photo->id)->exists();
    }
}
