<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function homeAction()
    {
        $projects = $this->getDoctrine()->getRepository('App:Project')->findAll();

        return $this->render('App:Home:home.html.twig', ['projects' => $projects]);
    }
}
