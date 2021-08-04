<?php
namespace App\Command;

ini_set('memory_limit', '-1');

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Location;

#[AsCommand(
    name: 'PostCodes:ImportPostCodesData',
    description: 'Imports the available postcodes and populates the database.',
)]
class ImportPostCodesDataCommand extends Command
{
    private $params;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, ContainerBagInterface $params)
    {
        $this->params = $params;
        $this->entityManager = $entityManager;
        parent::__construct();

    }
    protected function configure(): void
    {
        $this->addArgument(
            'number-pages',
            InputArgument::OPTIONAL,
            'Number pages to import between (120) available',
            4);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $numberOfPages = $input->getArgument('number-pages');

        $io->note('Migrating Post Codes total of ('.$numberOfPages.'/120) pages');

        $this->importData($numberOfPages);

        $io->success('Migration completed');

        return Command::SUCCESS;
    }


    public function importData($numberOfPages)
    {
        $root =$this->params->get('temp_storage').'/Data/CSV';
        $files = array_diff(scandir($root),['..', '.']);
        $pages = [];
        $counter = 0;
        while($counter <= $numberOfPages){
            shuffle($files);
            $pages[] = array_pop($files);
            $counter++;
        }
        foreach($pages as $file)
        {
            if (($fp = fopen($root . '/' . $file, "r")) !== FALSE) {
                while (($row = fgetcsv($fp, 1000, ",")) !== FALSE) {
                    $location = new Location;
                    $location->setPostCode($row[0]);
                    $location->setEastings($row[2]);
                    $location->setNorthings($row[3]);
                    $location->setLatitude();
                    $location->setLongitude();
                    $this->entityManager->persist($location);
                }
                fclose($fp);
            }
            $this->entityManager->flush();
        }
    }
}
