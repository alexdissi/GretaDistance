<?php

namespace App\Controller;

use DateTime;
use Dompdf\Dompdf;
use App\Entity\Cours;
use Twig\Environment;
use Symfony\Component\Filesystem\Filesystem;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/cours')]
class CoursController extends AbstractController
{
    #[Route('/', name: 'app_cours_index', methods: ['GET'])]
    #[IsGranted("ROLE_USER")]
    public function index(Request $request, CoursRepository $coursRepository, PaginatorInterface $paginator, CategorieRepository $categorieRepository): Response
    {
        $queryBuilder = $coursRepository->createQueryBuilder('cours')
            ->orderBy('cours.id', 'DESC');

        $categorieId = $request->query->get('categorie');
        if ($categorieId) {
            $categorie = $categorieRepository->find($categorieId);
            if ($categorie) {
                $queryBuilder
                    ->join('cours.categorie', 'categorie')
                    ->andWhere('categorie = :categorie')
                    ->setParameter('categorie', $categorie);
            }
        }

        $categories = $categorieRepository->findAll();
        $query = $queryBuilder->getQuery();

        $cours = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('cours/index.html.twig', [
            'cours' => $cours,
            'categories' => $categories,
        ]);
    }


    #[Route('/new', name: 'app_cours_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CoursRepository $coursRepository, CategorieRepository $categorieRepository): Response
    {
        $cour = new Cours();
        $form = $this->createForm(CoursType::class, $cour, [
            'categorie' => $categorieRepository->findAll(),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // upload de la photo de profil
            /** @var UploadedFile $pdf */
            $pdf = $form['fichier']->getData();
            $video = $form['video']->getData();
            $image = $form['image']->getData();
            $user = $this->getUser();

            if ($pdf && $video && $image) {
                $originalFilename = pathinfo($pdf->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilenamePdf = $safeFilename . '-' . uniqid() . '.' . $pdf->guessExtension();
                $newFilenameVideo = $safeFilename . '-' . uniqid() . '.' . $video->guessExtension();
                $newFilenameImage = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                // Move the files to their respective directories
                try {
                    $pdf->move(
                        $this->getParameter('pdf_directory'),
                        $newFilenamePdf
                    );
                    $video->move(
                        $this->getParameter('video_directory'),
                        $newFilenameVideo
                    );
                    $image->move(
                        $this->getParameter('image_directory'),
                        $newFilenameImage
                    );
                } catch (FileException $e) {
                }
                $cour->setFichier($newFilenamePdf);
                $cour->setVideo($newFilenameVideo);
                $cour->setImage($newFilenameImage);
            }
            $cour->setDateAjout(new DateTime());
            $cour->setUser($user);
            $coursRepository->save($cour, true);
            return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cours/new.html.twig', [
            'cour' => $cour,
            'form' => $form,
        ]);
    }
    #[IsGranted("ROLE_USER")]
    #[Route('/{id}', name: 'app_cours_show', methods: ['GET'])]
    public function show(Cours $cour): Response
    {
        $user = $cour->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        return $this->render('cours/show.html.twig', [
            'cour' => $cour,
            'user' => $user
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cours_edit', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_ADMIN")]
    public function edit(Request $request, Cours $cour, CoursRepository $coursRepository, Security $security): Response
    {
        // Vérifier si l'utilisateur connecté est l'auteur du cours
        $user = $security->getUser();
        if ($user !== $cour->getUser()) {
            return $this->render('error/404.html.twig');
        }

        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coursRepository->save($cour, true);

            return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cours/edit.html.twig', [
            'cour' => $cour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cours_delete', methods: ['POST'])]
    public function delete(Request $request, Cours $cour, CoursRepository $coursRepository): Response
    {


        if ($this->isCsrfTokenValid('delete' . $cour->getId(), $request->request->get('_token'))) {
            if ($cour->getFichier() !== null) {
                $filesystem = new Filesystem();
                $filesystem->remove([$cour->getFichier()]);
            }

            $coursRepository->remove($cour, true);
        }

        return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/pdf', name: 'app_generate_pdf', methods: ['GET'])]
    public function generatePdf(Request $request, Environment $twig, EntityManagerInterface $entityManager, int $id): Response
    {
        $cours = $entityManager->getRepository(Cours::class)->find($id);

        if (!$cours) {
            throw $this->createNotFoundException(
                'Aucun cours à générer pour l\'ID ' . $id
            );
        }

        $titre = $cours->getTitre();
        $contenu = $cours->getContenu();
        $dateAjout = $cours->getDateAjout();
        $auteur = $cours->getUser();
        $image = $cours->getImage();

        $html = $twig->render('cours/cours_pdf_template.html.twig', [
            'titre' => $titre,
            'contenu' => $contenu,
            'dateAjout' => $dateAjout,
            'auteur' => $auteur,
            'image' => $image,
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF
        $dompdf->render();

        // Generate the PDF file content
        $pdfContent = $dompdf->output();

        // Create a response object with the PDF content
        $response = new Response($pdfContent);

        // Set the content type header to indicate it's a PDF file
        $response->headers->set('Content-Type', 'application/pdf');

        return $response;
    }
}
