<?php

namespace App\Message;

final class SendNotification
{
    private int $notifyId;

    public function __construct(int $notifyId)
    {
        $this->notifyId = $notifyId;
    }

    public function getNotifyId(): int
    {
        return $this->notifyId;
    }
}
