<?php

namespace App\Controller;

use DateTime;
use App\Entity\Taches;
use App\Form\TachesType;
use App\Repository\TachesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/taches')]
class TachesController extends AbstractController
{
    #[Route('/', name: 'app_taches_index', methods: ['GET'])]
    public function index(TachesRepository $tachesRepository): Response
    {
        return $this->render('taches/index.html.twig', [
            'taches' => $tachesRepository->findAll(),
        ]);
    }

    #[IsGranted("ROLE_USER")]
    #[Route('/new', name: 'app_taches_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TachesRepository $tachesRepository): Response
    {
        $tach = new Taches();
        $form = $this->createForm(TachesType::class, $tach);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $tach->setDateCreation(new DateTime());
            $tach->setUser($user);
            $tachesRepository->save($tach, true);
            return $this->redirectToRoute('app_taches_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('taches/new.html.twig', [
            'tach' => $tach,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_taches_show', methods: ['GET'])]
    public function show(Taches $tach): Response
    {
        return $this->render('taches/show.html.twig', [
            'tach' => $tach,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_taches_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Taches $tach, TachesRepository $tachesRepository): Response
    {
        $form = $this->createForm(TachesType::class, $tach);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tachesRepository->save($tach, true);

            return $this->redirectToRoute('app_taches_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('taches/edit.html.twig', [
            'tach' => $tach,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_taches_delete', methods: ['POST'])]
    public function delete(Request $request, Taches $tach, TachesRepository $tachesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tach->getId(), $request->request->get('_token'))) {
            $tachesRepository->remove($tach, true);
        }

        return $this->redirectToRoute('app_taches_index', [], Response::HTTP_SEE_OTHER);
    }
}
