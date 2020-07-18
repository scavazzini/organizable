<?php

namespace App;

use Carbon\Carbon;
use Firebase\JWT\JWT;

class Invite
{
    private const JWT_LIFESPAN = 7;
    private const JWT_ALGORITHM = 'HS256';

    private $issuedAt;
    private $expiration;
    private $sender;
    private $event;
    private $recipient;

    public function __construct($sender, $event, $recipient)
    {
        $this->issuedAt = Carbon::now();
        $this->expiration = Carbon::now()->addDays(self::JWT_LIFESPAN);
        $this->sender = $sender;
        $this->event = $event;
        $this->recipient = $recipient;
    }

    public static function parse(string $jwtToken): self
    {
        $payload = JWT::decode($jwtToken, env('APP_KEY'), array(self::JWT_ALGORITHM));

        $sender = User::findOrFail($payload->sid);
        $recipient = User::findOrFail($payload->rid);
        $event = Event::findOrFail($payload->event);
        $issuedAt = Carbon::createFromTimestamp($payload->iat);
        $expiration = Carbon::createFromTimestamp($payload->exp);

        $inviteToken = new self($sender, $event, $recipient);
        $inviteToken->setIssuedAt($issuedAt);
        $inviteToken->setExpiration($expiration);

        return $inviteToken;
    }

    public function __toString(): string
    {
        $payload = array(
            "iss" => env('APP_NAME'),
            "iat" => $this->issuedAt->timestamp,
            'exp' => $this->expiration->timestamp,
            'event' => $this->event->id,
            'sid' => $this->sender->id,
            'rid' => $this->recipient->id,
            'rmail' => $this->recipient->email,
        );

        return JWT::encode($payload, env('APP_KEY'));
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function getSender()
    {
        return $this->sender;
    }

    public function getRecipient()
    {
        return $this->recipient;
    }

    public function getExpiration()
    {
        return $this->expiration;
    }

    public function setIssuedAt(\DateTime $issuedAt): void
    {
        $this->issuedAt = $issuedAt;
    }

    public function setExpiration(\DateTime $expiration): void
    {
        $this->expiration = $expiration;
    }
}
