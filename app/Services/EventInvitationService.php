<?php

namespace App\Services;

use App\Event;
use App\Mail\EventInvitationMail;
use App\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Mail;

class EventInvitationService
{
    private const TOKEN_LIFESPAN = 7;

    public function invite(Event $event, User $sender, User $recipient)
    {
        $payload = array(
            "iss" => env('APP_NAME'),
            "iat" => Carbon::now()->timestamp,
            'exp' => Carbon::now()->addDays(self::TOKEN_LIFESPAN)->timestamp,
            'event' => $event->id,
            'rid' => $recipient->id,
            'rmail' => $recipient->email,
        );

        $token = JWT::encode($payload, env('APP_KEY'));

        Mail::to($recipient->email)
            ->queue(new EventInvitationMail($event, $sender, $token, self::TOKEN_LIFESPAN));
    }
}
