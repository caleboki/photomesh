<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Http\Requests\PhotoUploadRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PhotoController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(): InertiaResponse
    {
        return Inertia::render('Photos/PhotoUpload');
    }

    /**
     * Display a listing of all photos from all users.
     */
    public function discover(): InertiaResponse
    {
        $photos = Photo::with('user') // Eager load the user relationship
            ->latest()
            ->paginate(12);

        // Get IDs of photos bookmarked by the current user for UI state
        $bookmarkedPhotoIds = Auth::user()
            ->bookmarks()
            ->pluck('photos.id')
            ->toArray();

        return Inertia::render('Photos/Discover', [
            'photos' => $photos,
            'bookmarkedPhotoIds' => $bookmarkedPhotoIds,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PhotoUploadRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = Auth::user();

        if (!$user) {
            // This should ideally be handled by auth middleware
            // but as a safeguard:
            return redirect()->route('login')->with('error', 'You must be logged in to upload photos.');
        }

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->storePublicly('photos');
        }

        $photo = Photo::create([
            'user_id' => $user->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'file_path' => $path,
        ]);

        // Placeholder redirect, actual route might be to photo.show or dashboard
        return redirect()->route('photos.index')->with('success', 'Photo uploaded successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Photo $photo): InertiaResponse
    {
        // Eager load the user who uploaded the photo
        $photo->load('user');

        // Check if the current user has bookmarked this photo
        $isBookmarked = auth()->user() ? auth()->user()->bookmarks()->where('photo_id', $photo->id)->exists() : false;

        return Inertia::render('Photos/Show', [
            'photo' => $photo,
            'isBookmarked' => $isBookmarked,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Photo $photo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Photo $photo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Photo $photo): RedirectResponse
    {
        $this->authorize('delete', $photo);

        // Delete the physical file
        if ($photo->file_path) {
            Storage::delete($photo->file_path);
        }

        $photo->delete();

        return redirect()->route('dashboard')->with('success', 'Photo deleted successfully!');
    }
}
