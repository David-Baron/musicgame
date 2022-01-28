<?php 
namespace App\Controller\Musicgame;

use App\Entity\MusicgameTrack;
use App\Service\CsvFileUploader;
use App\Controller\AdminController;
use App\Repository\MusicgameRepository;
use App\Form\Custom\FileImporterCsvType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ImportTrackController extends AdminController
{
    private MusicgameRepository $musicgameRepository;
    private EntityManagerInterface $em;

    public function __construct(MusicgameRepository $musicgameRepository, EntityManagerInterface $em)
    {
        $this->musicgameRepository = $musicgameRepository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/musicgames/{slug}/tracks/import", name="admin_musicgame_game_track_import", methods={"GET","POST"})
     */
    public function trackList(Request $request, CsvFileUploader $csvFileUploader)
    {
        $musicgame = $this->musicgameRepository->findOneBy(['slug' => $request->get('slug')]);
        $importForm = $this->createForm(FileImporterCsvType::class);
        $importForm->add('tracksOnline', ChoiceType::class, [
            'choices' => [
                'Yes' => 1,
                'No' => 0,
            ]
        ]);
        $importForm->handleRequest($request);
        if ($importForm->isSubmitted() && $importForm->isValid()) {
            $csvFile = $importForm->get('file')->getData();
            $online = $importForm->get('tracksOnline')->getData();
            if ($csvFile) {
                $filename = $csvFileUploader->upload($csvFile);
                $csvFiles = [];
                // If the file is too large we split it.
                if(filesize($this->getParameter('upload_directory').$filename) > 100000) {
                    $csvFiles[] = $filename;
                } else {
                    $csvFiles[] = $filename; 
                }
                foreach ($csvFiles as $filename) {
                    $this->insertDataFromCsvFile($musicgame, $filename, $online);
                }
                $this->addFlash('success', 'Tracks are registered in database!');
                return $this->redirectToRoute('admin_musicgame_game_track_list', ['slug' => $musicgame->getSlug()]);
            }
            
        }
        return $this->returnView('musicgame/game-track-import.html.twig', [
            'musicgame' => $musicgame,
            'importForm' => $importForm->createView()
        ]);
    }

    public function insertDataFromCsvFile($musicgame, string $filename, bool $online)
    {
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        $csv = fopen($this->getParameter('upload_directory').$filename, 'r');
        $t = 0;
        $headers = [];
        while (!feof($csv)) {
            $row = fgetcsv($csv, 0, ';', '"');
            if (empty($headers))
                $headers = $row;
            else if (is_array($row)) {
                
                $track = new MusicgameTrack();
                $track->setArtist($row[0]);
                $track->setTitle($row[1]);
                $track->setIsOnline($online);
                $track->setFullname($track->getArtist().' - '.$track->getTitle());
                //$track->setMusicgame($musicgame);
                $musicgame->addTrack($track);
                //$em->persist($track);
                $this->em->flush();
            }
            /*  if (($t % 50 === 0)) {
                $em->flush();
                $em->clear();
            }
            $t++; */
        }
        $t=0;   
        fclose($csv);
        //$em->flush();
        $this->em->clear();
        unlink($this->getParameter('upload_directory').$filename);
    }

    private function generateFileName($inputFileName, $partNumber)
    {
        return pathinfo($inputFileName, PATHINFO_FILENAME).'_part_'.$partNumber.'.csv';
    }

    private function generateOutputFilePath($inputFileName, $partNumber): string
    {
        $outputFileName = $this->generateFileName($inputFileName, $partNumber);
        return $this->getParameter('upload_directory').$outputFileName;
    }

    public function split($inputFileName, $splitSize = 100000)
    {
        $in = fopen($this->getParameter('upload_directory').$inputFileName, 'r');
        $out = null;
        $rowCount = 0;
        $partNumber = 0;
        
        while (!feof($in)) {
            if (($rowCount % $splitSize) == 0 ) {
                $outputFilePath = $this->generateOutputFilePath($inputFileName, $partNumber);
                if ($rowCount > 0 && $out !== null) {
                    fclose($out);
                }
                $partNumber++;
                $out = fopen($outputFilePath, 'w');
            }
            $data = fgetcsv($in , 0 , ';');
            if ($data)
                fputcsv($out, $data , ';');
            $rowCount++;
        }

        fclose($out);
    }
}