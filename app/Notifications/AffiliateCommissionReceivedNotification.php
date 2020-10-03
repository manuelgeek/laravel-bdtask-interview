<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AffiliateCommissionReceivedNotification extends Notification
{
    use Queueable;

    public $user;

    public $profit;

    public $affiliateProfit;

    /**
     * Create a new notification instance.
     *
     * @param  User  $user
     * @param $profit
     * @param $affiliateProfit
     */
    public function __construct(User $user, $profit, $affiliateProfit)
    {
        $this->user = $user;
        $this->profit = $profit;
        $this->affiliateProfit = $affiliateProfit;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('Hi '. $this->user->name)
            ->line('Commission Received: $'. $this->profit)
            ->line('Referral Commission Received: $'. $this->affiliateProfit)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
