<?php

namespace App\Services;

use App\Models\Booking;
use App\Notifications\BookingNotification;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function sendBookingNotification(Booking $booking, string $type)
    {
        switch ($type) {
            case 'new_booking':
                $this->sendNewBookingNotification($booking);
                break;
            case 'booking_approved':
                $this->sendBookingApprovedNotification($booking);
                break;
            case 'booking_rejected':
                $this->sendBookingRejectedNotification($booking);
                break;
            case 'booking_cancelled':
                $this->sendBookingCancelledNotification($booking);
                break;
            case 'payment_received':
                $this->sendPaymentReceivedNotification($booking);
                break;
        }
    }

    protected function sendNewBookingNotification(Booking $booking)
    {
        // Notify provider
        $booking->bookable->provider->notify(new BookingNotification(
            title: 'Booking Baru',
            message: "Anda menerima booking baru untuk {$booking->bookable->name} dari {$booking->user->name}",
            category: 'booking_status',
            booking: $booking
        ));

        // Send email if enabled
        // Mail::to($booking->bookable->provider->email)->send(new NewBookingMail($booking));
    }

    protected function sendBookingApprovedNotification(Booking $booking)
    {
        // Notify user
        $booking->user->notify(new BookingNotification(
            title: 'Booking Disetujui',
            message: "Booking Anda untuk {$booking->bookable->name} telah disetujui. Silakan lakukan pembayaran.",
            category: 'booking_status',
            booking: $booking
        ));
    }

    protected function sendBookingRejectedNotification(Booking $booking)
    {
        // Notify user
        $booking->user->notify(new BookingNotification(
            title: 'Booking Ditolak',
            message: "Booking Anda untuk {$booking->bookable->name} ditolak. Alasan: {$booking->rejection_reason}",
            category: 'booking_status',
            booking: $booking
        ));
    }

    protected function sendBookingCancelledNotification(Booking $booking)
    {
        // Notify provider
        $booking->bookable->provider->notify(new BookingNotification(
            title: 'Booking Dibatalkan',
            message: "Booking untuk {$booking->bookable->name} dari {$booking->user->name} telah dibatalkan",
            category: 'booking_status',
            booking: $booking
        ));
    }

    protected function sendPaymentReceivedNotification(Booking $booking)
    {
        // Notify provider
        $booking->bookable->provider->notify(new BookingNotification(
            title: 'Pembayaran Diterima',
            message: "Pembayaran untuk booking {$booking->booking_code} telah diterima",
            category: 'payment_status',
            booking: $booking
        ));

        // Notify user
        $booking->user->notify(new BookingNotification(
            title: 'Pembayaran Berhasil',
            message: "Pembayaran Anda untuk {$booking->bookable->name} telah berhasil diproses",
            category: 'payment_status',
            booking: $booking
        ));
    }

    public function markAsRead($notificationId, $userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        $notification = $user->notifications()->where('id', $notificationId)->firstOrFail();
        return (bool) $notification->markAsRead();
    }

    public function markAllAsRead($userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        foreach ($user->unreadNotifications as $notification) {
            $notification->markAsRead();
        }
        return true;
    }
}

