<?php

namespace App\Jobs;

use App\Mail\UpcomingEventsMail;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class NotifyUsersWithUpcomingEvents implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;
    use SerializesModels {
        __serialize as traitSerialize;
    }

    private $days;
    private $onStartCallback;
    private $onUpdateCallback;
    private $onFinishCallback;

    public function __construct(int $days = 3)
    {
        $this->days = $days;
    }

    public function handle(UserRepositoryInterface $userRepository)
    {
        $users = $userRepository->getUsersWithUpcomingEvents($this->days);

        $this->onStart(count($users));

        foreach ($users as $user) {
            Mail::to($user->email)->queue(new UpcomingEventsMail($user, $user->events->all()));
            $this->onUpdate();
        }

        $this->onFinish();
    }

    public function setOnStart(callable $onStartCallback)
    {
        $this->onStartCallback = $onStartCallback;
    }

    public function setOnUpdate(callable $onUpdateCallback)
    {
        $this->onUpdateCallback = $onUpdateCallback;
    }

    public function setOnFinish(callable $onFinishCallback)
    {
        $this->onFinishCallback = $onFinishCallback;
    }

    private function onStart(int $count)
    {
        if (is_callable($this->onStartCallback)) {
            ($this->onStartCallback)($count);
        }
    }

    private function onUpdate()
    {
        if (is_callable($this->onUpdateCallback)) {
            ($this->onUpdateCallback)();
        }
    }

    private function onFinish()
    {
        if (is_callable($this->onFinishCallback)) {
            ($this->onFinishCallback)();
        }
    }

    public function __serialize()
    {
        // Remove callbacks before serialization
        $this->onStartCallback = null;
        $this->onUpdateCallback = null;
        $this->onFinishCallback = null;

        return $this->traitSerialize();
    }
}
