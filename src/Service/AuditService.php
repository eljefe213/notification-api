<?php

namespace App\Service;

class AuditService
{
    public function __construct(private string $projectDir) {}

    public function record(string $event, array $context = []): void
    {
        $dir = $this->projectDir. '/var/audit';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $line = json_encode([
            'ts' => (new \DateTimeImmutable())->format(DATE_ATOM),
            'event' => $event,
            'context' => $context,
        ], JSON_UNESCAPED_UNICODE);

        file_put_contents($dir. '/audit.log', $line.PHP_EOL, FILE_APPEND);
    }
}
