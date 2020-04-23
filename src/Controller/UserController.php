<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function update(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $userRepository->findById($this->getUser()->getId())[0];
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userUpdate = $request->request->get('user');

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());

            $user->setPassword($password);
            $user->setUsername($userUpdate['username']);
            $user->setEmail($userUpdate['email']);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('show', [
                'id' => $user->getId()
            ]);
        }

        return $this->render(
            'user/edit.html.twig',
            array('form' => $form->createView())
        );
    }
}