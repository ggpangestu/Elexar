<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class BookingRescheduleAcceptedNotification extends Notification
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
        return (new MailMessage)
            ->subject('Konfirmasi Jadwal Baru Anda')
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Terima kasih telah mengonfirmasi jadwal baru.')
            ->line('📅 Jadwal: ' . $this->booking->booking_date_time->format('d M Y, H:i'))
            ->line('🔗 Link Meeting: ' . $this->booking->meeting_link)
            ->line('Kami nantikan kehadiran Anda sesuai jadwal.')
            ->salutation('Salam, Tim ELEXAR');
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
