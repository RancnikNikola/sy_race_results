<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// -------------------------------------------------------------------- //

use App\Entity\Results;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ResultsRepository;
use App\Form\ResultsType;


class ResultsController extends AbstractController
{
    /**
     * @Route("/results", name="app_results")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Results::class);

        $mediumDistanceResults = $repository->findBy(
            array('distance' => 'medium'), 
            array('placement' => 'ASC')
        );

        $longDistanceResults = $repository->findBy( 
            array('distance' => 'long'), 
            array('placement' => 'ASC')
        );

        $mediumAvarage = [];
        foreach ($mediumDistanceResults as $ree) {

           $mediumAvarage[] = $ree->getRaceTime()->format('H:i:s');
        }

        $totaltime = null;
        foreach($mediumAvarage as $time){
                $timestamp = strtotime($time);
                $totaltime += $timestamp;
        }

        $MediumAverageTime = ($totaltime/count($mediumAvarage));
        $MediumAverageTime = date('H:i:s', $MediumAverageTime);


        $longAvarage = [];
        foreach ($longDistanceResults as $ldr) {

           $longAvarage[] = $ldr->getRaceTime()->format('H:i:s');
        }

        $totaltimelong = null;
        foreach($longAvarage as $time){
                $timestamp = strtotime($time);
                $totaltimelong += $timestamp;
        }

        $longAverageTime = ($totaltimelong/count($longAvarage));
        $longAverageTime = date('H:i:s', $longAverageTime);
     

        return $this->render('results/index.html.twig', [
            'mediumDistanceResults' => $mediumDistanceResults,
            'longDistanceResults' => $longDistanceResults,
            'mediumAvarage' => $MediumAverageTime,
            'longAvarage' => $longAverageTime
        ]);
    }
  

     /**
     * @Route("/result/edit/{id}", name="edit_result")
     */
    public function edit(ManagerRegistry $doctrine, $id, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $result = $entityManager->getRepository(Results::class)->find($id);

        $form = $this->createForm(ResultsType::class, $result);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$result) {
                throw $this->createNotFoundException(
                    'No race found for id '.$id
                );
            }

            $fullName = $form->get('fullName')->getData();
            $raceTime = $form->get('raceTime')->getData();

            $result
                ->setFullName($fullName)
                ->setRaceTime($raceTime);
            
            $entityManager->flush();

            return $this->redirectToRoute('app_results');
        }

        return $this->renderForm('results/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
