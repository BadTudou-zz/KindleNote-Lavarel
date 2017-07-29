<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SystemNotification extends Notification
{
    use Queueable;
    public  const TYPE_SUCCESS = 'success';
    // static public const TYPE_INFO = 'info';
    // static public const TYPE_ERROR = 'danger';
    // static public const TYPYE_WARNING = 'warning';
    // static public const TYPE_DANGER = 'danger';

    private $type;
    private $msg;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($type, $msg)
    {
        $this->type = $type;
        $this->msg = $msg;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
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
            'type'=>$this->type,
            'msg'=>$this->msg
        ];
    }

    // public function toDatabase($notifiable)
    // {

    // }
}
