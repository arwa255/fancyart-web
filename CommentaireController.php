<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Evenement;
use App\Entity\VoteComment;
use App\Form\CommentaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commentaire')]
class CommentaireController extends AbstractController
{


    public function count($id)
    {
        $count = 0;
        $em = $this->getDoctrine()->getManager();
        $commentaire = $em->getRepository("EvenementBundle:Commentaire")->findBy(array('idevenement'=>$id));
        foreach ($commentaire as $e){
            $count = $count + 1;
        }

        return $count;

    }
    function filterwords($text){
        $filterWords = array('fuck', 'nike', 'pute','bitch');
        $filterCount = sizeof($filterWords);
        for ($i = 0; $i < $filterCount; $i++) {
            $text = preg_replace_callback('/\b' . $filterWords[$i] . '\b/i', function($matches){return str_repeat('*', strlen($matches[0]));}, $text);
        }
        return $text;
    }

    #[Route('/', name: 'app_commentaire_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $commentaires = $entityManager
            ->getRepository(Commentaire::class)
            ->findAll();

        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaires,
        ]);
    }

    #[Route('/new/{id}', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,$id): Response
    {
        $count = $this->count($id);

        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository(Evenement::class)->find($id);
        $vote = $em->getRepository(VoteComment::class)->findAll();
        $Comm = $em->getRepository(Commentaire::class)->findBy(array("idevenement" => $event));
        $user = $this->getUser();
        $comment = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setContenu($this->filterwords($comment->getContenu()));
            $comment->setIdUser($user);
            $comment->setIdPost($event);
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('Comment', ['id' => $id]);
        }
        return $this->render('evenement/show.html.twig', array(
            'form' => $form->createView(),
            'comment' => $Comm,
            'event' => $event,
            'c'=>$count ,
            'user'=>$user,
            'vote'=>$vote


        ));
    }

    #[Route('/{id}', name: 'app_commentaire_show'   )]
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/{idc}/edit/{ide}', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }



    #[Route('/deletec/{idc}/{ide}', name: 'app_commentaire_delete')]
    public function delete($idc): Response
    {
        $em = $this->getDoctrine()->getManager();
        $res = $em->getRepository(Commentaire::class)->find($idc);
        $em->remove($res);
        $em->flush();
        return $this->redirectToRoute('app_post_show', ['id' => $idc]);
    }
}
