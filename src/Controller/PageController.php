<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;

// AbstractController proporciona $this->render(), createForm(), etc.
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class PageController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(EntityManagerInterface $entityManager, Request $request): Response {

        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() )
        {
            // guardar info
            $entityManager->persist($form->getData());
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        $comments = $entityManager
                    ->getRepository(Comment::class)
                    ->findBy( [], [
                        'id' => 'DESC'
                    ]
        );

        return $this->render('home.html.twig', 
            [
                // 'comments' => $entityManager
                //     ->getRepository(Comment::class)
                //     ->findAll()
                'comments' => $comments,
                'form'     => $form->createView(),
            ],);
    }    
}