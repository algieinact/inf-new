<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\Residence;
use App\Models\Activity;
use App\Models\Booking;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:residence,activity',
            'id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $type = $request->type;
        $id = $request->id;

        $modelClass = $type === 'residence' ? Residence::class : Activity::class;

        // Check if item exists
        $item = $modelClass::findOrFail($id);

        // Check if user has completed booking for this item
        $hasCompletedBooking = auth()->user()->bookings()
            ->where('bookable_type', $modelClass)
            ->where('bookable_id', $id)
            ->where('status', 'completed')
            ->exists();

        if (!$hasCompletedBooking) {
            return response()->json(['message' => 'Anda hanya dapat memberikan rating setelah menyelesaikan booking'], 403);
        }

        // Check if user already rated this item
        $existingRating = Rating::where('user_id', auth()->id())
            ->where('rateable_type', $modelClass)
            ->where('rateable_id', $id)
            ->first();

        if ($existingRating) {
            // Update existing rating
            $existingRating->update([
                'rating' => $request->rating,
                'comment' => $request->comment
            ]);

            return response()->json(['message' => 'Rating berhasil diupdate']);
        }

        // Create new rating
        Rating::create([
            'user_id' => auth()->id(),
            'rateable_type' => $modelClass,
            'rateable_id' => $id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return response()->json(['message' => 'Rating berhasil diberikan']);
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

        $rating = Rating::where('user_id', auth()->id())
            ->where('rateable_type', $modelClass)
            ->where('rateable_id', $id)
            ->first();

        if (!$rating) {
            return response()->json(['message' => 'Rating tidak ditemukan'], 404);
        }

        $rating->delete();

        return response()->json(['message' => 'Rating berhasil dihapus']);
    }
}

