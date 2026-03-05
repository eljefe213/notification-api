<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class NotificationController
{
    public function __construct(private NotificationService $service) {}

    #[Route('/api/notify', name: 'api_notify', methods: ['POST'])]
    public function notify(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return new JsonResponse([
                'status' => 'error',
                'error' => ['body' => 'invalid JSON'],
            ], 400);
        }

        $errors = $this->validate($data);
        if ($errors !== []) {
            return new JsonResponse([
                'status' => 'error',
                'error' => $errors,
            ], 400);
        }

        $this->service->notify($data['to'], $data['subject'], $data['message']);

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Notification sent',
        ], 200);
    }

    private function validate(array $data) : array
    {
        $errors = [];

        $to = $data['to'] ?? null;
        $subject = $data['subject'] ?? null;
        $message = $data['message'] ?? null;

        if (!is_string($to) || $to === '' || strlen($to) > 255 || !filter_var($to, FILTER_VALIDATE_EMAIL) ) {
            $errors['subject'] = 'invalid email';
        }

        if (!is_string($subject) || trim($subject) || strlen($subject) > 255 ) {
            $errors['subject'] = 'invalid subject';
        }

        if (!is_string($message) || trim($message) === '' || strlen($message) > 2000 ) {
            $errors['message'] = 'invalid message';
        }

        return $errors;
    }

}
