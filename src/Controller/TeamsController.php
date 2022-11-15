<?php

namespace App\Controller;

use App\Repository\TeamsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamsController extends AbstractController
{
    #[Route('/teams', name: 'teams')]
    public function index(TeamsRepository $teamsRepository): Response
    {
       $teams = $teamsRepository->findBy(
          [],
          ['id' => 'ASC']
       );
        return $this->render('teams/teams.html.twig', [
            'teams' => $teams,
        ]);
    }
}
