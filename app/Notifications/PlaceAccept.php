<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Place;
use App\AcceptedPlaces;
use App\User;
use Auth;

class PlaceAccept extends Notification
{
    use Queueable;

    public $place;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Place $place)
    {
        $this->place = $place;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database','broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'notification_object' => $this->place,
            'type' => "placeAcc"
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url('/home');
        $accepted = AcceptedPlaces::where('place_id',$this->place->id)->first();
        $user = User::find($accepted->person_id);

        return (new MailMessage)
            ->subject('Place has been accepted !')
            ->greeting('Hey, '. $user->name.' !')
            ->line('Your place "'. $this->place->title .'" has been accepted!')
            ->action('Open profile', $url)
            ->line('Thank you for using our application!');
    }


    public function toBroadcast($notifiable)
    {
        return [
            'data' => [
                'notification_object' => $this->place,
                'type' => "placeAcc"
            ]
        ];
    }


}
