<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfController extends AbstractFOSRestController
{

    public function createPdf($userName, $email)
    {
        $date = new \DateTime('now');
        $nowDate = $date->format('Y-m-d');

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($pdfOptions);

        $html = $this->renderView('pdf/index.html.twig', [
            'title' => "Welcome to our PDF Test",
            'userName' => $userName,
            'email' => $email,
            'nowDate' => $nowDate
        ]);


        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');


        $dompdf->render();


        $output = $dompdf->output();


        $publicDirectory = $this->get('kernel')->getProjectDir() . '/pdf';

        $pdfFilepath = $publicDirectory . '/' . $userName . '.pdf';


        file_put_contents($pdfFilepath, $output);

    }

    public function deletePdf($pdfFilepath)
    {
        unlink($pdfFilepath);
    }
}