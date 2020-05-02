<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\History;
use App\Entity\Reponse;
use App\Entity\Question;
use App\Entity\User;
use App\Repository\HistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HistoryController extends AbstractController
{
    /**
     * @Route("/quizhistory", name="history")
     */
    public function index(HistoryRepository $historyRepository)
    {
        $history = $historyRepository->findAll();
        if($this->getUser())
        {
            $myhistory = $this->getUser()->getHistory();
        }
        return $this->render('quiz/history.html.twig', compact('history', 'myhistory'));
    }
}