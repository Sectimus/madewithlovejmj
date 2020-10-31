<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Entity\Post;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController{

    /**
     * This is the contact page of the made with love web application
     * @Route(
     * "/contact",
     * condition="context.getMethod() in ['GET']"
     * )
     */
    public function index(){
        return $this->render('contact.html.twig', ['sent'=>false]);
    }

    /**
     * Handles the submission of contact forms
     * @Route(
     * "/contact",
     * condition="context.getMethod() in ['POST'] and request.headers.get('Content-Type') matches ';application/x-www-form-urlencoded;i'"
     * )
     */
    public function contactSubmission(Request $request, \Swift_Mailer $mailer){
        $post['name'] = $request->request->get('name');
        $post['email'] = $request->request->get('email');
        $post['phone'] = $request->request->get('phone');
        $post['message'] = $request->request->get('message');

        //TODO ensure all params are there

        // Remove all illegal characters from email
        $email = filter_var($post['email'], FILTER_SANITIZE_EMAIL);

        // Validate e-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new Response("Please enter a valid email address", Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
        }


        //send confirmation to the client
        $message = (new \Swift_Message('We have recieved your message!'))
        ->setFrom('system.amelia.magee@gmail.com')
        ->setTo($email)
        ->setBody(
            $this->renderView(
                'emails/client.html.twig',
                ['name' => $post['name'], 'email' => $post['email'], 'phone' => $post['phone'], 'message' => $post['message']]
            ),
            'text/html'
        );
        $mailer->send($message);

        //send alert to the staff (contact emails env has all whitespace stripped and comma delimited)
        $staffEmails = explode(',', preg_replace('/\s+/', '', $_ENV['CONTACT_EMAILS']));
        //set the current date to cater to BST and format as "6:46am on 19/05/2020"
        $date = (new DateTime("now", new DateTimeZone('Europe/London')))->format('g:ia \o\n d/m/Y');
        //from name has to be base64 encoded due to the (JMJ) addition displaying as an alias instead. https://github.com/swiftmailer/swiftmailer/issues/339
        $fromname = '=?UTF-8?B?'. base64_encode('Made With Love (JMJ)') . '?=';
        $message = (new \Swift_Message('Message recieved from '.$post['name'].' at '.$date))
        ->setFrom($_ENV['SYSTEM_EMAIL'], $fromname)
        ->setTo($staffEmails)
        ->setBody(
            $this->renderView(
                'emails/staff.html.twig',
                ['name' => $post['name'], 'email' => $post['email'], 'phone' => $post['phone'], 'message' => $post['message'], 'date' => $date]
            ),
            'text/html'
        );
        $mailer->send($message);

        //TODO store backup in db


        return $this->render('contact.html.twig', ['sent'=>true]);
    }
}