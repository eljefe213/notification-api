<?php

namespace App\Service\Mailer;

use App\Interface\MailerTransportInterface;

final class FileTransport implements MailerTransportInterface
{
    public function __construct(private string $projectDir) {}

    public function send(string $to, string $subject, string $message): void
    {
        $dir = $this->projectDir . '/var/emails';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $content = "TO: $to\nSUBJECT: $subject\n\n$message\n";
        file_put_contents($dir. '/'.date('Y-m-d_H-i-s'). '_' . uniqid() . '.txt', $content);
    }
}
