<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Residence;
use App\Models\Activity;
use App\Models\MarketplaceProduct;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard with marketplace-style content
     */
    public function index()
    {
        // Get featured/latest residences (limit to 6)
        $residences = Residence::where('is_active', true)
            ->where('available_slots', '>', 0)
            ->with(['provider', 'category', 'ratings'])
            ->latest()
            ->limit(6)
            ->get();

        // Get upcoming activities (limit to 6)
        $activities = Activity::where('is_active', true)
            ->where('available_slots', '>', 0)
            ->where('event_date', '>', now())
            ->with(['provider', 'category', 'ratings'])
            ->orderBy('event_date')
            ->limit(6)
            ->get();

        // Get latest marketplace products (limit to 6)
        $products = MarketplaceProduct::active()
            ->available()
            ->with(['seller', 'category', 'ratings'])
            ->latest()
            ->limit(6)
            ->get();

        return view('user.dashboard', compact('residences', 'activities', 'products'));
    }

    /**
     * Display user's history page (bookings, transactions, etc.)
     */
    public function history()
    {
        $user = auth()->user();
        
        // Get user's bookings
        $bookings = $user->bookings()
            ->with(['bookable'])
            ->latest()
            ->paginate(10, ['*'], 'bookings');

        // Get user's bookmarks
        $bookmarks = $user->bookmarks()
            ->with(['bookmarkable'])
            ->latest()
            ->paginate(10, ['*'], 'bookmarks');

        // Get user's marketplace transactions if they exist
        $transactions = collect();
        if (class_exists('App\Models\MarketplaceTransaction')) {
            $transactions = $user->marketplaceTransactions()
                ->with(['product'])
                ->latest()
                ->paginate(10, ['*'], 'transactions');
        }

        return view('user.history', compact('bookings', 'bookmarks', 'transactions'));
    }
}
