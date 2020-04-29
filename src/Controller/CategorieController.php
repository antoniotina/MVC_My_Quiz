<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\AddQuizType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class CategorieController extends AbstractController
{
    /**
     * @Route("/quiz", name="quiz")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Categorie::class);
        // look for *all* Product objects
        $categories = $repository->findAll();
        $question = $categories[0]->getQuestions();

        return $this->render('quiz/quiz.html.twig', compact('categories'));
    }

    /**
     * @Route("/quiz/{id}", name="showquiz")
     */
    public function showQuiz(Categorie $categorie)
    {
        $cache = new FilesystemAdapter();
        dd($categorie);
        return $this->render('quiz/showquiz.html.twig', compact('categorie'));
    }

    /**
     * @Route("/addquiz/{number}", name="addquiz")
     */
    public function addQuiz($number)
    {
        $categorie = new Categorie();
        $form = $this->createForm(AddQuizType::class, compact('categorie', 'number'));
        return $this->render('quiz/addquiz.html.twig',
            array('form' => $form->createView(), 'number' => $number)
        );
    }
}
