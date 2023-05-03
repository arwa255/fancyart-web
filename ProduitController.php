<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/produit')]
class ProduitController extends AbstractController
{
    #[Route('/chart', name: 'chart')]

    public function produitsParCategorie()
    {
        $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();
        $data = array();
        $data[] = ['Categorie', 'Nombre de produits'];
        foreach ($produits as $produit) {
            $categorie = $produit->getIdCategorie()->getType();
            if (!isset($data[$categorie])) {
                $data[$categorie] = 1;
            } else {
                $data[$categorie]++;
            }
        }
        $dataArray = array();
        foreach ($data as $categorie => $nombre) {
            $dataArray[] = array((string)$categorie, $nombre);
        }

        $dataArray = array_values($dataArray); // Réindexe le tableau numériquement
        array_unshift($dataArray);
      //  dd($dataArray);
        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable($dataArray);
        $pieChart->getOptions()->setTitle('Nombre de produits par categorie');
        $pieChart->getOptions()->setHeight(400);
        $pieChart->getOptions()->setWidth(600);
        $pieChart->setElementID('my');
        //dd($pieChart);
        return $this->render('/produit/chart.html.twig', array('piechart' => $pieChart));
    }

    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(Request $request,EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $produits = $entityManager
            ->getRepository(Produit::class)
            ->findAll();
        $articles = $paginator->paginate(
            $produits, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            1 // Nombre de résultats par page
        );
        return $this->render('produit/index.html.twig', [
            'produits' => $articles,
        ]);
    }
    #[Route('/pdf', name: 'app_pdf')]
    public function pdf(EntityManagerInterface $entityManager): Response
    {

        $produits = $entityManager
            ->getRepository(Produit::class)
            ->findAll();
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('produit/pdf.html.twig', [
            'produits' => $produits,
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
    #[Route('/front', name: 'app_front_index', methods: ['GET'])]
    public function indexfront(EntityManagerInterface $entityManager): Response
    {
        $produits = $entityManager
            ->getRepository(Produit::class)
            ->findAll();

        return $this->render('produit/indexFront.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/rate', name: 'rate')]

    public function rateAction(\Symfony\Component\HttpFoundation\Request $request){
        $data = $request->getContent();
        $obj = json_decode($data,true);

        $em = $this->getDoctrine()->getManager();
        $rate =$obj['rate'];
        $idc = $obj['produit'];
        $produit = $em->getRepository(Produit::class)->find($idc);
        $note = ($produit->getRate()*$produit->getVote() + $rate)/($produit->getVote()+1);
        $produit->setVote($produit->getVote()+1);
        $produit->setRate($note);
        $em->persist($produit);
        $em->flush();
        return new Response($produit->getRate());
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,FlashyNotifier $flashy): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produit->getUploadFile();
            $produit->setVote(0);
            $produit->setRate(0);
            $entityManager->persist($produit);
            $entityManager->flush();
            $flashy->success('Ajouter Avec Sucess');
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($produit->getFile() != null){
                $produit->getUploadFile();
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }


    #[Route('/deletepost/{id}', name: 'app_produit_delete')]
    public function delete($id,FlashyNotifier $flashy): Response
    {
        $em = $this->getDoctrine()->getManager();
        $res = $em->getRepository(Produit::class)->find($id);
        $em->remove($res);
        $em->flush();
        $flashy->error('Supprimer Avec Sucess');
        return $this->redirectToRoute('app_produit_index');
    }


}
