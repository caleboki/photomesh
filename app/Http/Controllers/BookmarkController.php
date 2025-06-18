<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class BookmarkController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(): InertiaResponse
    {
        $user = Auth::user();

        $bookmarkedPhotos = $user->bookmarks()
            ->with('user') // Eager load the original uploader
            ->latest('bookmarks.created_at') // Order by when it was bookmarked
            ->paginate(12);

        return Inertia::render('Bookmarks/Index', [
            'photos' => $bookmarkedPhotos,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Photo $photo): RedirectResponse
    {
        $this->authorize('bookmark', $photo);

        Auth::user()->bookmarks()->attach($photo->id);

        return back()->with('success', 'Photo bookmarked successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Photo $photo)
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
        $this->authorize('unbookmark', $photo);

        Auth::user()->bookmarks()->detach($photo->id);

        return back()->with('success', 'Photo unbookmarked successfully!');
    }
}
