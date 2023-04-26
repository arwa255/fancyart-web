<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Type;
use App\Form\TypeRType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class TypeController extends AbstractController
{

    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/type', name: 'app_type')]
    public function index(): Response
    {
        $data = $this->getDoctrine()->getRepository(Type::class)->findAll();
        return $this->render('\type\index.html.twig', [
            'list' => $data   
        ]);
    }



    #[Route('/type/add', name: 'add_type')]
    public function addcolis(ManagerRegistry $doctrine,Request $req): Response {
      
        $em = $doctrine->getManager();
        $type = new Type();
        $form = $this->createForm(TypeRType::class,$type);
        $form->handleRequest($req);
        

        if($form->isSubmitted() && $form->isValid()) {
          

            $em->persist($type);
            $em->flush();
            return $this->redirectToRoute('app_type');
        }

        return $this->renderForm('type/ajoutertype.html.twig',['form'=>$form]);

}



#[Route('/type/update/{id}', name: 'update_type')]
    public function update(Request $req, $id) {
      
      $type = $this->getDoctrine()->getRepository(Type::class)->find($id); 
      $form = $this->createForm(TypeRType::class,$type);
      $form->handleRequest($req);
    if($form->isSubmitted() && $form->isValid()) {
       


        $em = $this->getDoctrine()->getManager();
        $em->persist($type);
        $em->flush();




        return $this->redirectToRoute('app_type');
    }

    return $this->renderForm('type/modifier.html.twig',[
        'form'=>$form]);

}





#[Route('/type/delete/{id}', name: 'delete_type')]
public function delete($id) {
 
 
   
    $data = $this->getDoctrine()->getRepository(Type::class)->find($id); 

      $em = $this->getDoctrine()->getManager();
      $em->remove($data);
      $em->flush();


      
      return $this->redirectToRoute('app_type');
  }

}
   

