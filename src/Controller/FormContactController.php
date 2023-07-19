<?php

namespace App\Controller;

use App\Entity\FormContact;
use App\Form\FormContactType;
use App\Repository\FormContactRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/form/contact')]
class FormContactController extends AbstractController
{
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/', name: 'app_form_contact_index', methods: ['GET'])]
    public function index(Request $request, FormContactRepository $formContactRepository, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $formContactRepository->createQueryBuilder('form_contact')
            ->orderBy('form_contact.dateAjout', 'DESC');

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1), // Current page number
            5 // Number of items per page
        );

        return $this->render('form_contact/index.html.twig', [
            'form_contacts' => $pagination,
        ]);
    }
    #[IsGranted('ROLE_USER')]
    #[Route('/new', name: 'app_form_contact_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FormContactRepository $formContactRepository): Response
    {
        $formContact = new FormContact();
        $formContact->setDateAjout(new \DateTime());

        $form = $this->createForm(FormContactType::class, $formContact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $formContact->setUser($user);
            $formContactRepository->save($formContact, true);

            return $this->redirectToRoute('app_form_contact_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('form_contact/new.html.twig', [
            'form_contact' => $formContact,
            'form' => $form,
        ]);
    }

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/{id}', name: 'app_form_contact_show', methods: ['GET'])]
    public function show(FormContact $formContact): Response
    {
        return $this->render('form_contact/show.html.twig', [
            'form_contact' => $formContact,
        ]);
    }
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/{id}/edit', name: 'app_form_contact_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FormContact $formContact, FormContactRepository $formContactRepository): Response
    {
        $form = $this->createForm(FormContactType::class, $formContact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formContactRepository->save($formContact, true);

            return $this->redirectToRoute('app_form_contact_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('form_contact/edit.html.twig', [
            'form_contact' => $formContact,
            'form' => $form,
        ]);
    }
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/{id}', name: 'app_form_contact_delete', methods: ['POST'])]
    public function delete(Request $request, FormContact $formContact, FormContactRepository $formContactRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $formContact->getId(), $request->request->get('_token'))) {
            $formContactRepository->remove($formContact, true);
        }

        return $this->redirectToRoute('app_form_contact_index', [], Response::HTTP_SEE_OTHER);
    }
}
