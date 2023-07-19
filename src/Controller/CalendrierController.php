<?php

namespace App\Controller;

use App\Repository\CalendarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendrierController extends AbstractController
{
    #[Route('/calendrier', name: 'app_calendrier')]
    public function index(CalendarRepository $calendarRepository, Security $security): Response
    {
        $user = $security->getUser(); // Récupère l'utilisateur connecté

        if (!$user) {
            // Gérer le cas où aucun utilisateur n'est connecté
        }

        $user = $this->getUser(); // Récupère l'utilisateur connecté

        $events = $calendarRepository->findBy(['user' => $user]);

        $rendezvous = [];
        foreach ($events as $event) {
            $rendezvous[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
                'allDay' => $event->isAllDay(),
            ];
        }

        $data = json_encode($rendezvous);

        return $this->render('calendrier/index.html.twig', [
            'controller_name' => 'CalendrierController',
            'data' => $data,
        ]);
    }
}
