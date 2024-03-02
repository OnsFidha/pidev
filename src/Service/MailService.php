<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;

class MailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(): void
    {
        $email = (new Email())
            ->from('onsfidha3@gmail.com')
            ->to('onsfidhaa@gmail.com')
            ->subject('Collaboration de Artistool')
            ->html('hiii');
    try {
        $this->mailer->send($email);
    } catch (TransportExceptionInterface $e) {
    throw new \RuntimeException("Impossible denv");
    }
    }
}
