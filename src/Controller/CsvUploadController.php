<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// --------------------------------------------- //

use App\Entity\CsvUploadFile;
use App\Form\CsvUploadFileType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Results;
use App\Entity\Race;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\DateTime;
use App\Form\RaceType;
use Symfony\Component\Finder\Finder;


class CsvUploadController extends AbstractController
{

    /**
     * @Route("/csv/upload", name="csv_upload")
     */
    public function create(Request $request, SluggerInterface $slugger, ManagerRegistry $doctrine)
    {

        $uploadedCsv = new CsvUploadFile();
        $race = new Race();
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(CsvUploadFileType::class, $uploadedCsv);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $csvFile */
            $csvFile = $form->get('csvFile')->getData();

            if ($csvFile) {
                $originalFilename = pathinfo($csvFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'.'.'csv';

                try {
                    $csvFile->move(
                        $this->getParameter('csv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $uploadedCsv->setCsvFile($newFilename);
              
            }

            $racename = $form["races"]["raceName"]->getData();
            $racedate = $form["races"]["date"]->getData();

            $race
                ->setRaceName($racename)
                ->setDate($racedate);

            $entityManager->persist($race);
            $entityManager->flush();

            $pathis = ('../public/uploads/csv/'.$uploadedCsv);
           
            $handle = fopen($pathis, 'r');
            $data = fgetcsv($handle, 1000, ';');

            $all_record_arr = [];

            while (($data = fgetcsv($handle, 1000, ';')) !== false)
            {
                $all_record_arr[] = $data;
            }
            
            foreach($all_record_arr as $rec) {

                $results = new Results();

                $results
                    ->setFullName($rec[0])
                    ->setDistance($rec[1])
                    ->setRaceTime(new \DateTime($rec[2]))
                    ->setPlacement('1')
                    ->setRace($race);

                $entityManager->persist($results);
                $entityManager->flush();

                return $this->redirectToRoute('app_results');
            }

            fclose($handle);
        }
        
        return $this->renderForm('csv_upload/create.html.twig', [
            'form' => $form
        ]);
        
    }
}
