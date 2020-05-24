<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DonateController extends AbstractController{

    /**
     * This is the donation hub for made with love (jmj)
     * @Route("/donate")
     */
    public function index(){
        return $this->render('donate.html.twig', ['paypal_hosted_button_id' => $_ENV['PAYPAL_HOSTED_BUTTON_ID']]);
    }
}