<?php

namespace App\Controller;

use App\Repository\PaintingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(PaintingRepository $paintingRepository): Response
    {
       $paintings = $paintingRepository->findBy(
          ['isPublished' => true],
          ['id' => 'ASC'],
          3
       );

        return $this->render('home/home.html.twig', [
            'paintings' => $paintings,
        ]);
    }
}
