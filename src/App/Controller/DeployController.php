<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class HomeController
 *
 * The main controller of our application.
 * Shows the homepage and more.
 */
class DeployController extends Controller
{
    public function deployFromWebAction(Project $project)
    {
        try {
            $stack = $this->get('deployer')->deploy($project);
            // $this->addFlash('success', 'The project was sucessful deployed :-)');
        } catch(\Exception $e) {
            $this->addFlash('error', 'There was a failure during the deployment with message: ' . $e->getMessage());
            return $this->redirectToRoute('homepage');
        }

        return $this->render('App:Deploy:stack.html.twig', ['stack' => $stack]);
    }

    public function deployFromApiAction(Project $project)
    {
        try {
            $this->get('deployer')->deploy($project);
            $message = new JsonResponse(['message' => 'sucess !'], 200);
        } catch(\Exception $e) {
            $this->addFlash('error', 'There was a failure during the deployment with message:' . $e->getMessage());
            $message = new JsonResponse(['message' => 'sucess !'], 500);
        }

        return $message;
    }
}
