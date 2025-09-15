<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Residence;
use App\Models\Activity;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\UserActivity;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // User statistics
        $totalUsers = User::count();
        $totalProviders = User::whereHas('roles', function ($q) {
            $q->where('name', 'provider');
        })->count();
        $totalRegularUsers = User::whereHas('roles', function ($q) {
            $q->where('name', 'user');
        })->count();

        // Business statistics
        $totalResidences = Residence::count();
        $activeResidences = Residence::where('is_active', true)->count();
        $totalActivities = Activity::count();
        $activeActivities = Activity::where('is_active', true)
            ->where('registration_deadline', '>', now())
            ->count();

        // Booking statistics
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $completedBookings = Booking::where('status', 'completed')->count();

        // Revenue statistics
        $totalRevenue = Transaction::where('payment_status', 'paid')->sum('final_amount');
        $monthlyRevenue = Transaction::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('final_amount');

        // Recent activities
        $recentUsers = User::with('roles')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recentBookings = Booking::with(['user', 'bookable'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Chart data - Monthly bookings
        $monthlyBookings = Booking::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        // Chart data - Monthly revenue
        $monthlyRevenues = Transaction::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(final_amount) as revenue')
        )->where('payment_status', 'paid')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('revenue', 'month')
            ->toArray();

        // Top providers by booking count
        $topProviders = User::whereHas('roles', function ($q) {
            $q->where('name', 'provider');
        })->withCount(['providedResidences as residence_bookings' => function ($q) {
            $q->join('bookings', function ($join) {
                $join->on('residences.id', '=', 'bookings.bookable_id')
                     ->where('bookings.bookable_type', 'like', '%Residence%');
            });
        }])->withCount(['providedActivities as activity_bookings' => function ($q) {
            $q->join('bookings', function ($join) {
                $join->on('activities.id', '=', 'bookings.bookable_id')
                     ->where('bookings.bookable_type', 'like', '%Activity%');
            });
        }])->orderByDesc(DB::raw('residence_bookings + activity_bookings'))
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProviders',
            'totalRegularUsers',
            'totalResidences',
            'activeResidences',
            'totalActivities',
            'activeActivities',
            'totalBookings',
            'pendingBookings',
            'completedBookings',
            'totalRevenue',
            'monthlyRevenue',
            'recentUsers',
            'recentBookings',
            'monthlyBookings',
            'monthlyRevenues',
            'topProviders'
        ));
    }

    public function analytics()
    {
        // User growth data
        $userGrowth = User::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )->whereDate('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Booking status distribution
        $bookingStatus = Booking::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // Popular categories
        $popularResidenceCategories = DB::table('residences')
            ->join('categories', 'residences.category_id', '=', 'categories.id')
            ->join('bookings', function ($join) {
                $join->on('residences.id', '=', 'bookings.bookable_id')
                     ->where('bookings.bookable_type', 'like', '%Residence%');
            })
            ->select('categories.name', DB::raw('COUNT(bookings.id) as booking_count'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('booking_count', 'desc')
            ->get();

        $popularActivityCategories = DB::table('activities')
            ->join('categories', 'activities.category_id', '=', 'categories.id')
            ->join('bookings', function ($join) {
                $join->on('activities.id', '=', 'bookings.bookable_id')
                     ->where('bookings.bookable_type', 'like', '%Activity%');
            })
            ->select('categories.name', DB::raw('COUNT(bookings.id) as booking_count'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('booking_count', 'desc')
            ->get();

        return view('admin.analytics', compact(
            'userGrowth',
            'bookingStatus',
            'popularResidenceCategories',
            'popularActivityCategories'
        ));
    }
}

