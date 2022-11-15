<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Painting;
use App\Form\CommentType;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PaintingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{
   /**
    * @param PaintingRepository $paintingRepository
    * @param CategoryRepository $categoryRepository
    * @return Response
    */
    #[Route('/gallery', name: 'gallery')]
    public function index(PaintingRepository $paintingRepository, CategoryRepository $categoryRepository): Response
    {
       $paintings = $paintingRepository->findBy(
          ['isPublished' => true],
          ['id' => 'ASC'],
       );

       $categories = $categoryRepository->findBy(
          [],
          ['id'=> 'ASC'],
       );

        return $this->render('gallery/gallery.html.twig', [
            'paintings' => $paintings,
            'categories' => $categories,
        ]);
    }

   /**
    * @param Painting $painting
    * @param Request $request
    * @param CommentRepository $commentRepository
    * @param EntityManagerInterface $manager
    * @return Response
    */
    #[Route('/gallery/{slug}', name:'detail')]
      public function painting(Painting $painting, Request $request, CommentRepository $commentRepository, EntityManagerInterface $manager): Response
    {
       $comments = $commentRepository->findBy(
          ['painting' =>$painting ,'isPublished' =>true],
          ['id' => 'ASC']
       );

       $comment = new Comment;
       $form = $this->createForm(CommentType::class, $comment);
       $form->handleRequest($request);
       if($form->isSubmitted() && $form->isValid()){
          $comment->setCreatedAt(new \DateTimeImmutable())
                  ->setIsPublished(true)
                  ->setPainting($painting);
          $manager->persist($comment);
          $manager->flush();
          return $this->redirectToRoute('detail', ['slug' => $painting->getSlug()]);
       }


       return $this->render('gallery/detail.html.twig', [
          'painting' => $painting,
          'comments' => $comments,
          'commentForm' => $form->createView(),
       ]);
    }

}
