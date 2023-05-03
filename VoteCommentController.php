<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Utilisateur;
use App\Entity\VoteComment;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/vote/comment')]
class VoteCommentController extends AbstractController
{
    #[Route('/', name: 'app_vote_comment_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $voteComments = $entityManager
            ->getRepository(VoteComment::class)
            ->findAll();

        return $this->render('vote_comment/index.html.twig', [
            'vote_comments' => $voteComments,
        ]);
    }


    #[Route('/{id}', name: 'app_vote_comment_show', methods: ['GET'])]
    public function show(VoteComment $voteComment): Response
    {
        return $this->render('vote_comment/show.html.twig', [
            'vote_comment' => $voteComment,
        ]);
    }



    #[Route('/like/{id}', name: 'app_vote_comment_like')]

    public function Like(Request $request,$id,FlashyNotifier $flashy)
    {
        // $count = $this->count($id);
        $em = $this->getDoctrine()->getManager();
        $vote = new VoteComment();
        $comment = $em->getRepository(Commentaire::class)->find($id);
        $user = $em->getRepository(Utilisateur::class)->find(1);
        $votepre = $em->getRepository(VoteComment::class)->findOneBy(array("idcomment" => $comment->getIdCommentaire(), 'idClient' => $user->getId()));
        if ($votepre != null) {
            $vote->setIdClient($user);
            $vote->setIdcomment($comment);
            $vote->setType(1);
            $em->remove($votepre);
            $em->persist($vote);
            $em->flush();
            $flashy->success('Like');

        } else{
            $vote->setIdClient($user);
            $vote->setIdcomment($comment);
            $vote->setType(1);
            $flashy->success('Like');

            $em->persist($vote);
            $em->flush();

        }
        return $this->redirectToRoute('app_post_show', ['id' => $comment->getidPost()->getIdPost()]);
    }

    #[Route('/deslike/{id}', name: 'app_vote_comment_deslike')]

    public function DesLike(Request $request , $id,FlashyNotifier $flashy)
    {

        $em = $this->getDoctrine()->getManager();
        $vote = new VoteComment();
        $comment = $em->getRepository(Commentaire::class)->find( $id);
        $user = $em->getRepository(Utilisateur::class)->find(1);
        $votepre = $em->getRepository(VoteComment::class)->findOneBy(array("idcomment" => $comment->getIdCommentaire(), 'idClient' => $user->getId()));
        if ($votepre != null) {
            $vote->setIdClient($user);
            $vote->setIdcomment($comment);
            $vote->setType(2);
            $em->remove($votepre);
            $em->persist($vote);
            $em->flush();
            $flashy->error('Deslike');

        } else{
            $vote->setIdClient($user);
            $vote->setIdcomment($comment);
            $vote->setType(2);

            $em->persist($vote);
            $em->flush();
            $flashy->error('Deslike');


        }
        return $this->redirectToRoute('app_post_show', ['id' => $comment->getidPost()->getIdPost()]);
    }
}
