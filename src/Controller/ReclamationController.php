<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reclamation;
use App\Entity\Utilisateur;
use App\Form\ReclamationType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Builder\BuilderRegistryInterface;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCodeBundle\Response\QrCodeResponse;
use Dompdf\Dompdf;
use Dompdf\Options; 



class ReclamationController extends AbstractController
{

    private $entityManager;
    private $customQrCodeBuilder;
    
    public function __construct(EntityManagerInterface $entityManager, BuilderInterface $customQrCodeBuilder)
    {
        $this->entityManager = $entityManager;
        $this->customQrCodeBuilder = $customQrCodeBuilder;
    }


    #[Route('/reclamation', name: 'app_reclamation')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(Reclamation::class);
        $query = $repository->createQueryBuilder('r')
        ->orderBy('r.id_reclamation', 'DESC');
          
            
        $data = $paginator->paginate(
            $query, // Requête contenant les données à paginer (ici notre requête custom)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );
    
        return $this->render('reclamation/index.html.twig', [
            'list' => $data   
        ]);
    }
    



    #[Route('/reclamation/add', name: 'add_reclamation')]
    public function addreclamation(ManagerRegistry $doctrine,Request $req): Response {
      
        $em = $doctrine->getManager();
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class,$reclamation);
        // cree une nouvelle formulaire pour recuperer les recs
        $form->handleRequest($req);
        

        if($form->isSubmitted() && $form->isValid()) {
           
        $id=68;
        $utilisateur = $this->entityManager->getRepository(Utilisateur::class)->find($id);
        $reclamation->setIdUser($utilisateur);
        $this->entityManager->persist($reclamation);
        // affecter le user au rec
        $this->entityManager->flush();
        // mise a jour

            $em->persist($reclamation);
            // affecter la reclamation kemla lel base
            $em->flush();
            // mise a jour lel bd
            return $this->redirectToRoute('app_reclamation');
        }

        return $this->renderForm('reclamation/ajouterreclamation.html.twig',['form'=>$form]);

}

   


#[Route('/reclamation/update/{id}', name: 'update_reclamation')]
    public function update(Request $req, $id) {
      
      $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id); 
      $form = $this->createForm(ReclamationType::class,$reclamation);
      $form->handleRequest($req);
    if($form->isSubmitted() && $form->isValid()) {
       
    $id=68;
    $utilisateur = $this->entityManager->getRepository(Utilisateur::class)->find($id);
    $reclamation->setIdUser($utilisateur);
    $this->entityManager->persist($reclamation);
    $this->entityManager->flush();

    ////////////////////////////////////////////////////

        $em = $this->getDoctrine()->getManager();
        $em->persist($reclamation);
        $em->flush();


       
        return $this->redirectToRoute('app_reclamation');
    }

    return $this->renderForm('reclamation/modifierreclamation.html.twig',[
        'form'=>$form]);

}



#[Route('/reclamation/delete/{id}', name: 'delete_reclamation')]
public function delete($id) {
 
 
   
    $data = $this->getDoctrine()->getRepository(Reclamation::class)->find($id); 

      $em = $this->getDoctrine()->getManager();
      $em->remove($data);
      $em->flush();


     

      return $this->redirectToRoute('app_reclamation');
  }




  #[Route('/reclamation/pdf/{id}', name: 'app_pdfr')]
  public function pdf($id): Response
  {
      // Configure Dompdf according to your needs
      $pdfOptions = new Options();
      $pdfOptions->set('isRemoteEnabled', true);
  
      $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id); 
  
      // Instantiate Dompdf with our options
      $dompdf = new Dompdf($pdfOptions);
  
    
   
      // Retrieve the HTML generated in our twig file
      $html = $this->renderView('reclamation/pdf.html.twig', [
          'reclamation' => [$reclamation]
      ]);
  
  
      // Load HTML to Dompdf
      $dompdf->loadHtml($html);
  
      // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
      $dompdf->setPaper('A4', 'landscape');
  
    
      // Render the HTML as PDF
      $dompdf->render();
  
      // Output the generated PDF to Browser (force download)
      $output = $dompdf->output();
      $response = new Response($output);
      $response->headers->set('Content-Type', 'application/pdf');
      $response->headers->set('Content-Disposition', 'attachment;filename=mypdf.pdf');
      $response->headers->set('Pragma', 'public');
      $response->headers->set('Cache-Control', 'max-age=0');
  
      return $response;
  }


  #[Route('reclamation/qrcode/{id}', name: 'qrcode')]
  public function qrcode(BuilderInterface $customQrCodeBuilder , $id ): Response
  {
      $reclamation = $this->entityManager->getRepository(Reclamation::class)->find($id);

      $textrec = $reclamation->getTextRec();
      $idtype = $reclamation->getIdtype();
      $type = $idtype->getType();
      $idreponse = $reclamation->getReponse();
      $reponse = $idreponse->getTextRep();


      $data = "Les détails de votre reclamation sont :\n";
      $data .= "- Type : " . $type . "\n";
      $data .= "- Texte Reclamation : " . $textrec . "\n";
      $data .= "- Texte Reponse  : " . $reponse;
     
 
      $qrCode = $this->customQrCodeBuilder
          ->size(400)
          ->margin(20)
          ->data($data)
          ->build();
 
      return new QrCodeResponse($qrCode);
  } 




}
