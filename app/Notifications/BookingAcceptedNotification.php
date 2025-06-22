<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingAcceptedNotification extends Notification
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
            ->subject('Jadwal Booking Anda Diterima')
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Jadwal booking Anda telah diterima oleh admin.')
            ->line('🗓️ Jadwal: ' . $this->booking->booking_date_time->format('d M Y, H:i'))
            ->line('📂 Kategori: ' . ucwords(str_replace('_', ' ', $this->booking->category)))
            ->when($this->booking->meeting_link, function ($mail) {
                $mail->line('🔗 Link Meeting: ' . $this->booking->meeting_link);

                if ($this->booking->meeting_link_note) {
                    $mail->line('📝 Catatan: ' . $this->booking->meeting_link_note);
                }

                return $mail;
            })
            ->line('Terima kasih telah menggunakan layanan kami.')
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
