<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Painting;
use App\Entity\Technical;
use App\Form\CategoryType;
use App\Form\PaintingType;
use App\Form\TechnicalType;
use App\Repository\CommentRepository;
use App\Repository\PaintingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
   /**
    * @param PaintingRepository $paintingRepository
    * @param PaginatorInterface $paginator
    * @param Request $request
    * @return Response
    */
    #[Route('/admin', name: 'admin')]
    public function admin(PaintingRepository $paintingRepository, PaginatorInterface $paginator, Request $request): Response
    {
       $paintings = $paintingRepository->findBy(
          [],
          ['id' => 'DESC'],
       );

       $pagination = $paginator->paginate(
          $paintings,
          $request->query->getInt('page', 1),
          5
       );


        return $this->render('admin/admin.html.twig', [
           'paintings' => $pagination,
        ]);
    }

   /**
    * @param Painting $painting
    * @param EntityManagerInterface $manager
    * @return Response
    */
   #[Route('/admin/delete/{id}', 'delete')]
   public function delete(Painting $painting, EntityManagerInterface $manager):Response
   {
      $manager->remove($painting);
      $manager->flush();
      $this->addFlash('success', 'Suppression du tableau réussie');
      return $this->redirectToRoute('admin');
   }

   /**
    * @param Request $request
    * @param EntityManagerInterface $manager
    * @return Response
    */
   #[Route('/admin/new', name: 'new')]
   public function new(Request $request, EntityManagerInterface $manager):Response
   {
      $painting = new Painting;
      $form = $this->createForm(PaintingType::class, $painting);
      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid()){
         $painting->setCreatedAt(new \DateTimeImmutable())
                  ->setImage('default.jpg')
                  ->createSlug();
         $manager->persist($painting);
         $manager->flush();
         $this->addFlash('success', 'Création du tableau réussie' );
         return $this->redirectToRoute('admin');
      }

      return $this->renderForm('admin/new.html.twig', [
         'form' => $form,
      ]);
   }

   /**
    * @param Request $request
    * @param Painting $painting
    * @param EntityManagerInterface $manager
    * @return Response
    */
   #[Route('/admin/edit/{id}', name: 'edit')]
   public function edit(Request $request, Painting $painting, EntityManagerInterface $manager) :Response
   {
      $form = $this->createForm(PaintingType::class, $painting);
      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid()) {
         $painting->createSlug();
         $manager->flush();
         $this->addFlash('success', 'Modification réussie');
         return $this->redirectToRoute('admin');
      }
      return $this->renderForm('admin/edit.html.twig', [
         'form' => $form,
      ]);
   }

   /**
    * @param EntityManagerInterface $manager
    * @param Painting $paintings
    * @return Response
    */
   #[Route('/admin/view/{id}', name: 'view')]
   public function view(EntityManagerInterface $manager, Painting $paintings):Response
   {
      $paintings->setIsPublished(!$paintings->isIsPublished());
      $manager->flush();

      return $this->redirectToRoute('admin');
   }

   /**
    * @param Request $request
    * @param PaginatorInterface $paginator
    * @param CommentRepository $commentRepository
    * @param PaintingRepository $paintingRepository
    * @return Response
    */
   #[Route('admin/comment', name: 'comment')]
   public function comment(Request $request, PaginatorInterface $paginator, CommentRepository $commentRepository, PaintingRepository $paintingRepository): Response
   {
      $paintings = $paintingRepository->findBy(
         [],
         ['id' => 'ASC'],
      );
      $comment = $commentRepository->findBy(
         [],
         ['painting' => 'ASC','id' => 'DESC'],
      );

      $pagination = $paginator->paginate(
         $comment,
         $request->query->getInt('page', 1),
         10
      );


      return $this->render('admin/comment.html.twig', [
         'comments' => $pagination,
         'paintings' => $paintings,
      ]);

   }

   /**
    * @param EntityManagerInterface $manager
    * @param Comment $comment
    * @return Response
    */
   #[Route('/admin/comment/view/{id}', name: 'comment_view')]
   public function commentView(EntityManagerInterface $manager, Comment $comment):Response
   {
      $comment->setIsPublished(!$comment->isIsPublished());
      $manager->flush();
      return $this->redirectToRoute('comment');
   }

   /**
    * @param Comment $comment
    * @param EntityManagerInterface $manager
    * @return Response
    */
   #[Route('/admin/comment/delete/{id}', 'comment_delete')]
   public function commentDelete(Comment $comment, EntityManagerInterface $manager):Response
   {
      $manager->remove($comment);
      $manager->flush();
      $this->addFlash('success', 'Suppression du commentaire réussie');
      return $this->redirectToRoute('comment');
   }

   /**
    * @param Request $request
    * @param EntityManagerInterface $manager
    * @return Response
    */
   #[Route('/admin/newCat', name: 'newCat')]
   public function newCat(Request $request, EntityManagerInterface $manager):Response
   {
      $category = new Category();
      $formCat = $this->createForm(CategoryType::class, $category);
      $formCat->handleRequest($request);
      if($formCat->isSubmitted() && $formCat->isValid()){
         $manager->persist($category);
         $manager->flush();
         $this->addFlash('success', 'Nouvelle catégorie ajouté' );
         return $this->redirectToRoute('admin');
      }

      return $this->renderForm('admin/new.html.twig', [
         'form' => $formCat,
      ]);
   }

   /**
    * @param Request $request
    * @param EntityManagerInterface $manager
    * @return Response
    */
   #[Route('/admin/newTech', name: 'newTech')]
   public function newTech(Request $request, EntityManagerInterface $manager):Response
   {
      $technical = new Technical();
      $formTech = $this->createForm(TechnicalType::class, $technical);
      $formTech->handleRequest($request);
      if($formTech->isSubmitted() && $formTech->isValid()){
         $manager->persist($technical);
         $manager->flush();
         $this->addFlash('success', 'Nouvelle technique ajouté' );
         return $this->redirectToRoute('admin');
      }

      return $this->renderForm('admin/new.html.twig', [
         'form' => $formTech,
      ]);
   }

}