<?php

namespace App\Interface;

Interface MailerTransportInterface
{
    public function send(string $to, string $subject, string $message): void;
}
