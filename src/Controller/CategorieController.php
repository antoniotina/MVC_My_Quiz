<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Reponse;
use App\Entity\Question;
use App\Entity\User;
use App\Form\AddQuizType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;

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
        return $this->render('quiz/showquiz.html.twig', compact('categorie'));
    }

    /**
     * @Route("/addquiz/{number}", name="addquiz")
     */
    public function addQuiz($number, Request $request, CategorieRepository $categorieRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categorie = new Categorie();
        $form = $this->createForm(AddQuizType::class, compact('categorie', 'number'));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getViewData();
        
            if(!$categorieRepository->findByName($data['name']))
            {
                $categorie->setName($data['name']);

                for ($i = 1; $i <= $data['number']; $i++) {

                    $answerArray = [];
                    $questionArray["question$i"] = new Question();
                    $questionArray["question$i"]->setQuestion($data["question$i"]);

                    for ($z = 1; $z <= 3; $z++) {

                        $answerArray["question$i-reponse$z"] = new Reponse();

                        if ($data["question$i-answer"] == $z) {
                            $answerArray["question$i-reponse$z"]->setReponseExpected(1);
                        } else {
                            $answerArray["question$i-reponse$z"]->setReponseExpected(0);
                        }

                        $answerArray["question$i-reponse$z"]->setReponse($data["question$i-reponse$z"]);
                        $questionArray["question$i"]->addReponse($answerArray["question$i-reponse$z"]);
                    }

                    $categorie->addQuestion($questionArray["question$i"]);

                }
                $entityManager->persist($categorie);
                $entityManager->flush();
                
                return $this->render(
                    'quiz/addquiz.html.twig',
                    array('form' => $form->createView(), 'number' => $number)
                );
            }

            return $this->render(
                'quiz/addquiz.html.twig',
                array('form' => $form->createView(), 'number' => $number, 'error' => "This name is already taken, pick another one")
            );
        } else {
            return $this->render(
                'quiz/addquiz.html.twig',
                array('form' => $form->createView(), 'number' => $number)
            );
        }
    }
}
