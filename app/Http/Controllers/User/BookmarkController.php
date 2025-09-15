<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\Residence;
use App\Models\Activity;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function index()
    {
        $bookmarks = auth()->user()->bookmarks()
            ->with('bookmarkable')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('user.bookmarks.index', compact('bookmarks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:residence,activity',
            'id' => 'required|integer'
        ]);

        $type = $request->type;
        $id = $request->id;

        // Determine the model class
        $modelClass = $type === 'residence' ? Residence::class : Activity::class;

        // Check if item exists
        $item = $modelClass::findOrFail($id);

        // Check if already bookmarked
        $existingBookmark = auth()->user()->bookmarks()
            ->where('bookmarkable_type', $modelClass)
            ->where('bookmarkable_id', $id)
            ->first();

        if ($existingBookmark) {
            return response()->json(['message' => 'Item sudah ada di bookmark'], 400);
        }

        // Create bookmark
        auth()->user()->bookmarks()->create([
            'bookmarkable_type' => $modelClass,
            'bookmarkable_id' => $id
        ]);

        return response()->json(['message' => 'Item berhasil ditambahkan ke bookmark']);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'type' => 'required|in:residence,activity',
            'id' => 'required|integer'
        ]);

        $type = $request->type;
        $id = $request->id;

        $modelClass = $type === 'residence' ? Residence::class : Activity::class;

        $bookmark = auth()->user()->bookmarks()
            ->where('bookmarkable_type', $modelClass)
            ->where('bookmarkable_id', $id)
            ->first();

        if (!$bookmark) {
            return response()->json(['message' => 'Bookmark tidak ditemukan'], 404);
        }

        $bookmark->delete();

        return response()->json(['message' => 'Item berhasil dihapus dari bookmark']);
    }
}

