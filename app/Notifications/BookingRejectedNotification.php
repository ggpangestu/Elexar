<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingRejectedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $booking;

    public function __construct($booking)
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
        return (new MailMessage)
            ->subject('Jadwal Booking Anda Ditolak')
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Kami mohon maaf, permintaan booking Anda telah ditolak oleh admin.')
            ->line('📂 Kategori: ' . ucwords(str_replace('_', ' ', $this->booking->category)))
            ->line('🗓️ Jadwal: ' . $this->booking->booking_date_time->format('d M Y, H:i'))
            ->line('Alasan penolakan mungkin karena konflik jadwal atau alasan lainnya.')
            ->line('Silakan mencoba membuat booking baru atau menghubungi admin jika diperlukan.')
            ->salutation('Salam hangat, Tim Kami');
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
