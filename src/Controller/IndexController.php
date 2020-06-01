<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\History;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController{

    /**
     * This is the main landing page of the made with love web application
     * @Route("/")
     */
    public function index(){
        //get the history from the database
        $history = $this->getDoctrine()->getRepository(History::Class)->findAll();

        //pass the data to the template
        return $this->render('index.html.twig', ['history' => $history]);
    }
}