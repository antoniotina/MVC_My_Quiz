<?php

namespace App\Controller;

use App\Entity\History;
use App\Entity\User;
use App\Repository\HistoryRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/sendemail", name="sendemail")
     */
    public function index(HistoryRepository $historyRepository)
    {
        return $this->render('admin/emailhub.html.twig');
    }

    /**
     * @Route("/stqd", name="stqd")
     */
    public function sendToQuizDoers(HistoryRepository $historyRepository, Request $request, MailerInterface $mailer)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($request->get('content')) {
            $histories = $historyRepository->findAll();
            $users = [];
            foreach ($histories as $key => $value) {
                array_push($users, $value->getUser());
            }
            $users = array_unique($users);

            $email = (new Email())
                ->from('antonio.tina@epitech.eu');
            foreach ($users as $key => $value) {
                $email->addTo($value->getEmail());
            }

            $email->subject($request->get('subject'))
                ->html($request->get('content'));

            $mailer->send($email);
        }
        $route = "stqd";
        return $this->render('admin/sendmail.html.twig', ['route' => 'stqd']);
    }

    /**
     * @Route("/stnqd", name="stnqd")
     */
    public function sendToNoQuizDoers(HistoryRepository $historyRepository, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($request->get('content')) {
            // SELECT email FROM `user` LEFT JOIN history ON user.id = history.user_id WHERE history.user_id IS NULL;
            $query = $entityManager->createQuery(
                'SELECT user.email 
                FROM App\Entity\User AS user 
                LEFT JOIN App\Entity\History AS history 
                WITH user.id = history.user 
                WHERE history.user IS NULL'
            );
            $emails = $query->getResult();

            $email = (new Email())
                ->from('antonio.tina@epitech.eu');
            foreach ($emails as $key => $value) {
                $email->addTo($value['email']);
            }

            $email->subject($request->get('subject'))
                ->html($request->get('content'));

            $mailer->send($email);
        }

        $route = "stnqd";
        return $this->render('admin/sendmail.html.twig', ['route' => 'stnqd']);
    }

    /**
     * @Route("/stau", name="stau")
     */
    public function sendToActiveUsers(HistoryRepository $historyRepository, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($request->get('content')) {
            // SELECT email FROM `user` LEFT JOIN history ON user.id = history.user_id WHERE history.user_id IS NULL;
            $query = $entityManager->createQuery(
                "SELECT user.email FROM App\Entity\User AS user WHERE user.last_connection > DATE_ADD(CURRENT_DATE(), '-1', 'month')"
            );
            $emails = $query->getResult();
            $email = (new Email())
                ->from('antonio.tina@epitech.eu');
            foreach ($emails as $key => $value) {
                $email->addTo($value['email']);
            }

            $email->subject($request->get('subject'))
                ->html($request->get('content'));

            $mailer->send($email);
        }

        return $this->render('admin/sendmail.html.twig', ['route' => 'stau']);
    }

    /**
     * @Route("/stiu", name="stiu")
     */
    public function sendToInactiveUsers(HistoryRepository $historyRepository, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($request->get('content')) {
            // SELECT email FROM `user` LEFT JOIN history ON user.id = history.user_id WHERE history.user_id IS NULL;
            $query = $entityManager->createQuery(
                "SELECT user.email FROM App\Entity\User AS user WHERE user.last_connection < DATE_ADD(CURRENT_DATE(), '-1', 'month')"
            );
            $emails = $query->getResult();
            $email = (new Email())
                ->from('antonio.tina@epitech.eu');
            foreach ($emails as $key => $value) {
                $email->addTo($value['email']);
            }

            $email->subject($request->get('subject'))
                ->html($request->get('content'));

            $mailer->send($email);
        }

        return $this->render('admin/sendmail.html.twig', ['route' => 'stiu']);
    }

    /**
     * @Route("/graph", name="graph")
     */
    public function graph(HistoryRepository $historyRepository, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $query = $entityManager->createQuery(
            "SELECT COUNT(history.date) FROM App\Entity\History AS history WHERE history.date > DATE_ADD(CURRENT_DATE(), '-1', 'day')"
        );
        $history24 = $query->getResult()[0][1];
        $query = $entityManager->createQuery(
            "SELECT COUNT(history.date) FROM App\Entity\History AS history WHERE history.date > DATE_ADD(CURRENT_DATE(), '-1', 'week')"
        );
        $historyWeek = $query->getResult()[0][1];
        $query = $entityManager->createQuery(
            "SELECT COUNT(history.date) FROM App\Entity\History AS history WHERE history.date > DATE_ADD(CURRENT_DATE(), '-1', 'month')"
        );
        $historyMonth = $query->getResult()[0][1];
        $query = $entityManager->createQuery(
            "SELECT COUNT(history.date) FROM App\Entity\History AS history WHERE history.date > DATE_ADD(CURRENT_DATE(), '-1', 'year')"
        );
        $historyYear = $query->getResult()[0][1];

        return $this->render('admin/graph.html.twig', compact('historyYear', 'history24', 'historyWeek', 'historyMonth'));
    }
}
