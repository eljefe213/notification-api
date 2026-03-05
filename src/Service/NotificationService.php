<?php

namespace App\Service;

use App\Interface\MailerTransportInterface;

final class NotificationService
{
    public function __construct(
        private MailerTransportInterface $transport,
        private AuditService $audit,
    ){}

    public function notify(string $to, string $subject, string $message): void
    {
        $this->transport->send($to, $subject, $message);

        $this->audit->record('notify.sent', [
            'to' =>$to,
            'subject' => $subject
        ]);
    }
}
