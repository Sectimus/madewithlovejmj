<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController{

    /**
     * This is the main landing page of the made with love web application
     * @Route("/")
     */
    public function index(){
        //get the posts from the database
        $posts = $this->getDoctrine()->getRepository(Post::Class)->findAll();

        //pass the posts to the template
        return $this->render('index.html.twig', ['posts' => $posts]);
    }
}