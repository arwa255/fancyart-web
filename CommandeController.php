<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Produit;
use App\Entity\Utilisateur;
use App\Form\CommandeType;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commande')]
class CommandeController extends AbstractController
{


    #[Route('/pdf', name: 'app_pdf')]
    public function pdf(): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $em = $this->getDoctrine()->getManager();
        $res = $em->getRepository(Commande::class)->findAll();

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('commande/pdf.html.twig', [
            'commandes' => $res,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();
        $pdf = $dompdf->output();

        // Send some text response
        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="document.pdf"'
        ]);
    }

    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $commandes = $entityManager
            ->getRepository(Commande::class)
            ->findAll();

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/admin', name: 'app_commande_admin', methods: ['GET'])]
    public function admin(Request $request,EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {

        $donnees = $entityManager
            ->getRepository(Commande::class)
            ->findAll();

        $articles = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            2 // Nombre de résultats par page
        );

        return $this->render('commande/adminindex.html.twig', [
            'commandes' => $articles,
        ]);
    }



    #[Route('/accepter/{id}', name: 'accepter')]
    public function accepter($id,FlashyNotifier $flashy): Response
    {
        $em = $this->getDoctrine()->getManager();
        $res = $em->getRepository(Commande::class)->find($id);
        $res->setEtat("Accepter");
        $em->persist($res);
        $em->flush();
        $flashy->success('Accepter');
        return $this->redirectToRoute('app_commande_admin');
    }


    #[Route('/refuser/{id}', name: 'refuser')]
    public function refuser($id,FlashyNotifier $flashy): Response
    {
        $em = $this->getDoctrine()->getManager();
        $res = $em->getRepository(Commande::class)->find($id);
        $res->setEtat("Refuser");
        $em->persist($res);
        $em->flush();
        $flashy->error('Refuser');


        return $this->redirectToRoute('app_commande_admin');
    }



    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        $commande = new Commande();
        $panier = $session->get("panier", []);
        $total = 0;
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);
        foreach($panier as $id => $quantite){
            $product =  $entityManager
                ->getRepository(Produit::class)
                ->find($id);
            $dataPanier[] = [
                "produit" => $product,
                "quantite" => $quantite
            ];
            $total += $product->getPrix() * $quantite;
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $entityManager
                ->getRepository(Utilisateur::class)
                ->find(1);
            $commande->setEtat("ATT");
            $commande->setPrix($total);
            $commande->setIdUser($user);
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('delete_all', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
            compact("dataPanier","total")
        ]);
    }

    #[Route('/{idCommande}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{idCommande}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }


    #[Route('/delete/{id}', name: 'delete_commande')]
    public function delete(EntityManagerInterface $entityManager,$id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $res = $entityManager->getRepository(Commande::class)->find($id);
        $em->remove($res);
        $em->flush();
        return $this->redirectToRoute('app_commande_index');
    }


}
