<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class UserBookingConfirmationNotification extends Notification
{
    use Queueable;

    protected $booking;

    /**
     * Create a new notification instance.
     *
     * @param $booking
     */
    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];  // Send via mail and store in database
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Your booking has been confirmed!')
                    ->line('Booking Details:')
                    ->line('Service Name: ' . $this->booking->service->service_name)
                    ->line('Booking Date: ' . $this->booking->booking_date)
                    ->line('Booking Time: ' . $this->booking->booking_time)
                    ->line('Total Price: $' . $this->booking->total_price)
                    ->action('View Your Booking', url('/booking/' . $this->booking->id))  // Adjust URL as needed
                    ->line('Thank you for using our service!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [

            'booking_id' => $this->booking->id,
            'message' => 'Your booking has been confirmed!',

        ];
    }
}
