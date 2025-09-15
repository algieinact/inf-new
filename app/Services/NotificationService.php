<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Booking;
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
        Notification::create([
            'user_id' => $booking->bookable->provider_id,
            'title' => 'Booking Baru',
            'message' => "Anda menerima booking baru untuk {$booking->bookable->name} dari {$booking->user->name}",
            'type' => 'booking_status'
        ]);

        // Send email if enabled
        // Mail::to($booking->bookable->provider->email)->send(new NewBookingMail($booking));
    }

    protected function sendBookingApprovedNotification(Booking $booking)
    {
        // Notify user
        Notification::create([
            'user_id' => $booking->user_id,
            'title' => 'Booking Disetujui',
            'message' => "Booking Anda untuk {$booking->bookable->name} telah disetujui. Silakan lakukan pembayaran.",
            'type' => 'booking_status'
        ]);
    }

    protected function sendBookingRejectedNotification(Booking $booking)
    {
        // Notify user
        Notification::create([
            'user_id' => $booking->user_id,
            'title' => 'Booking Ditolak',
            'message' => "Booking Anda untuk {$booking->bookable->name} ditolak. Alasan: {$booking->rejection_reason}",
            'type' => 'booking_status'
        ]);
    }

    protected function sendBookingCancelledNotification(Booking $booking)
    {
        // Notify provider
        Notification::create([
            'user_id' => $booking->bookable->provider_id,
            'title' => 'Booking Dibatalkan',
            'message' => "Booking untuk {$booking->bookable->name} dari {$booking->user->name} telah dibatalkan",
            'type' => 'booking_status'
        ]);
    }

    protected function sendPaymentReceivedNotification(Booking $booking)
    {
        // Notify provider
        Notification::create([
            'user_id' => $booking->bookable->provider_id,
            'title' => 'Pembayaran Diterima',
            'message' => "Pembayaran untuk booking {$booking->booking_code} telah diterima",
            'type' => 'payment_status'
        ]);

        // Notify user
        Notification::create([
            'user_id' => $booking->user_id,
            'title' => 'Pembayaran Berhasil',
            'message' => "Pembayaran Anda untuk {$booking->bookable->name} telah berhasil diproses",
            'type' => 'payment_status'
        ]);
    }

    public function markAsRead($notificationId, $userId)
    {
        return Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->update(['is_read' => true]);
    }

    public function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }
}

