<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class BookingRescheduledNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $urlAccept = route('reschedule.respond', [
            'token' => $this->booking->reschedule_token,
            'action' => 'accept'
        ]);

        $urlReject = route('reschedule.respond', [
            'token' => $this->booking->reschedule_token,
            'action' => 'reject'
        ]);

        return (new MailMessage)
        ->subject('Penjadwalan Ulang Booking Anda')
        ->greeting('Halo, ' . $notifiable->name)
        ->line('Admin telah mengubah jadwal booking Anda.')
        ->line('📅 Jadwal Baru: ' . $this->booking->booking_date_time->format('d M Y, H:i'))
        ->line('📝 Alasan Reschedule: ' . $this->booking->reschedule_reason)
        ->line('Klik tombol di bawah ini untuk melihat dan merespon jadwal baru:')
        ->action('🔁 Tinjau Jadwal Baru', route('reschedule.show', ['token' => $this->booking->reschedule_token]))
        ->line('Harap konfirmasi sebelum: ' . $this->booking->reschedule_expires_at->format('d M Y, H:i'))
        ->salutation('Terima kasih, Tim ELEXAR');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
