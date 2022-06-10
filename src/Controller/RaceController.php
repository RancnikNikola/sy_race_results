<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// -------------------------------------------------------------------- //

use App\Form\RaceType;
use App\Entity\Race;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;


class RaceController extends AbstractController
{
    /**
     * @Route("/race", name="app_race")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $races = $doctrine->getRepository(Race::class)->findAll();

        if (!$races) {
            throw $this->createNotFoundException(
                'No race found'
            );
        }

        return $this->renderForm('race/index.html.twig', [
            'races' => $races,
        ]);
    }

}
