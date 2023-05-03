<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/evenement')]
class EvenementAdminController extends AbstractController
{
    #[Route('/sortByAscNbDispo', name: 'sort_by_asc_nbdispo')]
    public function sortAscNbDispo(EntityManagerInterface $entityManager, EvenementRepository $evenementRepository, Request $request)
    {
        $evenements = $entityManager
            ->getRepository(Evenement::class)
            ->findAll();

        $query = $request->query->get('q');
        $evenements = $this->getDoctrine()
            ->getRepository(Evenement::class)
            ->findByNomevent($query);

        $evenements = $evenementRepository->sortByAscNbDispo();
    
        return $this->render("evenementAdmin/index.html.twig",[
            'evenements' => $evenements,
            'query' => $query,
        ]);
    }
    
    #[Route('/sortByDescNbDispo', name: 'sort_by_desc_nbdispo')]
    public function sortDescNbDispo(EntityManagerInterface $entityManager, EvenementRepository $evenementRepository, Request $request)
    {
        $evenements = $entityManager
            ->getRepository(Evenement::class)
            ->findAll();

        $query = $request->query->get('q');
        $evenements = $this->getDoctrine()
            ->getRepository(Evenement::class)
            ->findByNomevent($query);

        $evenements = $evenementRepository->sortByDescNbDispo();
    
        return $this->render("evenementAdmin/index.html.twig",[
            'evenements' => $evenements,
            'query' => $query,
        ]);
    }

    #[Route('/search', name: 'evenement_search')]
    public function search(Request $request, EvenementRepository $evenementRepository): Response
    {
        $query = $request->query->get('q');
        $evenements = $evenementRepository->findByNomevent($query);

        return $this->render('evenementAdmin/search.html.twig', [
            'evenements' => $evenements,
            'query' => $query,
        ]);
    }

    #[Route('/', name: 'admin_evenement_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, Request $request, EvenementRepository $evenementRepository, PaginatorInterface $paginator): Response
    {
        $evenements = $entityManager
            ->getRepository(Evenement::class)
            ->findAll();
        $articles = $paginator->paginate(
            $evenements, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            1 // Nombre de résultats par page
        );

        $query = $request->query->get('q');
        return $this->render('evenementAdmin/index.html.twig', [
            'evenements' => $articles,
            'query' => $query,
        ]);
    }

    #[Route('/new', name: 'admin_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,FlashyNotifier $flashy): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('evenement')['image1'];
            $uploads_directory = $this->getParameter('image_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $uploads_directory,
                $filename
            );
            $evenement->setImage1($filename);
            $entityManager->persist($evenement);
            $entityManager->flush();
            $flashy->error('Ajouter Avec Sucess');

            return $this->redirectToRoute('admin_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenementAdmin/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{idevent}', name: 'admin_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenementAdmin/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{idevent}/edit', name: 'admin_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('evenement')['image1'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $uploads_directory,
                $filename
            );
            $evenement->setImage1($filename);
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('admin_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenementAdmin/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{idevent}', name: 'admin_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getIdevent(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
}
