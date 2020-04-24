<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(UserRepository $userRepository)
    {
        if ($this->getUser()) {
            $this->lastConnection($userRepository);
            $user = $userRepository->findById($this->getUser()->getId())[0];
            if ($user->getValidated_at() === null) {
                dd(header("Location: http://" . $_SERVER['SERVER_NAME'] . "/logout"));
            }
        }
        return $this->render('home.html.twig');
    }

    private function lastConnection($userRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $userRepository->findById($this->getUser()->getId())[0];

        $user->setLast_connection(new \DateTime());
        $entityManager->persist($user);
        $entityManager->flush();
    }
}
