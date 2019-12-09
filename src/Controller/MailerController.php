<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\NamedAddress;


class MailerController extends AbstractFOSRestController
{


    public function sendEmail(MailerInterface $mailer, $temporaryToken,$userName)
    {
        $publicDirectory = $this->get('kernel')->getProjectDir() . '/pdf';

        $pdfFilepath = $publicDirectory . '/' . $userName . '.pdf';

        $email = (new Email())
            ->from(new NamedAddress('mailtrap@example.com', 'Mailtrap'))
            ->to('newuser@example.com')
            ->subject('Best practices of building HTML emails')
            ->html("your verify endpoint: GET <a href='http://127.0.0.1:8000/registration/$temporaryToken'>http://127.0.0.1:8000/registration/$temporaryToken</a>")
            ->attachFromPath($pdfFilepath, 'Welcome');



        $mailer->send($email);

        $this->forward('App\Controller\PdfController::deletePdf', [
            'pdfFilepath' => $pdfFilepath
        ]);

        return new Response('Check your email box to verify your user');
    }
}