<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/show/{id}", name="show")
     */
    public function show(User $user)
    {
        return $this->render('user/show.html.twig', compact('user'));
    }

    /**
     * @Route("/user/update", name="user_update")
     */
    public function update(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository, MailerInterface $mailer)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $userRepository->findById($this->getUser()->getId())[0];
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setToken(sha1(random_bytes(200)));
            $user->setValidated_at(null);

            $entityManager->persist($user);
            $entityManager->flush();

            $email = (new Email())
                ->from('antonio.tina@epitech.eu')
                ->to($user->getEmail())
                ->subject('Activate email')
                ->html("Your data has been updated, {$user->getUsername()}<br><a href='http://127.0.0.1:8000/email_act/{$user->getToken()}'>Activate email again</a>")
            ;

            $mailer->send($email);

            return $this->render('home.html.twig');
        }

        return $this->render(
            'user/edit.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/email_act/{token}", name="email_act", methods={"GET"})
     */
    public function activateEmail($token, UserRepository $userRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $userRepository->findByToken($token)[0];
        $user->setValidated_at(new \DateTime());

        $entityManager->persist($user);
        $entityManager->flush();
        
        return $this->render('home.html.twig');
    }
}