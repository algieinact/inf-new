<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Residence;
use App\Models\Activity;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $providerId = auth()->id();

        // Statistics
        $totalResidences = Residence::where('provider_id', $providerId)->count();
        $totalActivities = Activity::where('provider_id', $providerId)->count();

        $totalBookings = Booking::whereHas('bookable', function ($query) use ($providerId) {
            $query->where('provider_id', $providerId);
        })->count();

        $pendingBookings = Booking::whereHas('bookable', function ($query) use ($providerId) {
            $query->where('provider_id', $providerId);
        })->where('status', 'pending')->count();

        $totalRevenue = DB::table('transactions')
            ->join('bookings', 'transactions.booking_id', '=', 'bookings.id')
            ->join('residences', function ($join) use ($providerId) {
                $join->on('bookings.bookable_id', '=', 'residences.id')
                     ->where('bookings.bookable_type', 'like', '%Residence%')
                     ->where('residences.provider_id', $providerId);
            })
            ->where('transactions.payment_status', 'paid')
            ->sum('transactions.final_amount');

        $totalRevenue += DB::table('transactions')
            ->join('bookings', 'transactions.booking_id', '=', 'bookings.id')
            ->join('activities', function ($join) use ($providerId) {
                $join->on('bookings.bookable_id', '=', 'activities.id')
                     ->where('bookings.bookable_type', 'like', '%Activity%')
                     ->where('activities.provider_id', $providerId);
            })
            ->where('transactions.payment_status', 'paid')
            ->sum('transactions.final_amount');

        // Recent bookings
        $recentBookings = Booking::whereHas('bookable', function ($query) use ($providerId) {
            $query->where('provider_id', $providerId);
        })->with(['user', 'bookable'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Popular items
        $popularResidences = Residence::where('provider_id', $providerId)
            ->withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->limit(5)
            ->get();

        $popularActivities = Activity::where('provider_id', $providerId)
            ->withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->limit(5)
            ->get();

        // Prepare stats array
        $stats = [
            'total_residences' => $totalResidences,
            'total_activities' => $totalActivities,
            'total_bookings' => $totalBookings,
            'pending_bookings' => $pendingBookings,
            'total_revenue' => $totalRevenue,
            'monthly_bookings' => $totalBookings, // You can modify this to get monthly data
            'monthly_revenue' => $totalRevenue, // You can modify this to get monthly data
            'approval_rate' => $totalBookings > 0 ? round((($totalBookings - $pendingBookings) / $totalBookings) * 100, 1) : 0,
        ];

        // Combine recent items
        $recentItems = $popularResidences->concat($popularActivities)->sortByDesc('created_at')->take(5);

        return view('provider.dashboard', compact(
            'stats',
            'recentBookings',
            'recentItems'
        ));
    }
}

