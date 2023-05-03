<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Utilisateur;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/mespost', name: 'app_post_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $posts = $entityManager
            ->getRepository(Post::class)
            ->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/postClient', name: 'app_post_indexUser', methods: ['GET'])]
    public function indexUser(EntityManagerInterface $entityManager): Response
    {
        $posts = $entityManager
            ->getRepository(Post::class)
            ->findAll();

        return $this->render('post/indexUser.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user=$entityManager->getRepository(Utilisateur::class)->find(26);
            $post->setImage("3.jpg");
            $post->getUploadFile();
            $post->setIdUser($user);
            $post->setDateP(new \DateTime());
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{idPost}', name: 'app_post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/{idPost}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($post->getFile() != null){
                $post->getUploadFile();
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager,$id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $res = $em->getRepository(Post::class)->find($id);
        $em->remove($res);
        $em->flush();
        return $this->redirectToRoute('app_post_index');
    }
}
