<?php

namespace App\Controller;

use App\Entity\Categorie;
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
        dd($cache);
        return $this->render('quiz/showquiz.html.twig', compact('categorie'));
    }

    /**
     * @Route("/addquiz/{number}", name="addquiz")
     */
    public function addQuiz($number)
    {
        return $this->render('quiz/addquiz.html.twig');
    }
}
