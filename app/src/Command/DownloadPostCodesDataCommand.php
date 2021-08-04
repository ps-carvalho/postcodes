<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag;
use Symfony\Component\Console\Input\ArrayInput;

#[AsCommand(
    name: 'PostCodes:DownloadPostCodesData',
    description: 'Downloads the postcodes data for OSDatahub.os.uk',
)]
class DownloadPostCodesDataCommand extends Command
{
    private $client;
    private $params;

    public function __construct(HttpClientInterface $client, ContainerBagInterface $params)
    {
        parent::__construct();
        $this->client = $client;
        $this->params = $params;
    }



    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');
//
//        if ($arg1) {
//            $io->note(sprintf('You passed an argument: %s', $arg1));
//        }
//
//        if ($input->getOption('option1')) {
//            // ...
//        }


//        $command = $this->getApplication()->find('doctrine:migrations:migrate');
//        $arguments = [];
//        $greetInput = new ArrayInput($arguments);
//        $returnCode = $command->run($greetInput, $output);


        $result = $this->downloadData();

//        if($result === 'download completed'){
//            $io->note($result);
//            $command = $this->getApplication()->find('PostCodes:importPostCodesData');
//            $arguments = [];
//            $greetInput = new ArrayInput($arguments);
//            $returnCode = $command->run($greetInput, $output);
//        }

        $io->success("Process completed");

        return Command::SUCCESS;
    }

    private function downloadData()
    {
        $response = $this->client->request(
            'GET',
            'https://api.os.uk/downloads/v1/products/CodePointOpen/downloads'
        );
        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        $url = $content[0]['url'];
        $this->getCSVPackage($url);
        return "download completed";
    }

    private function getCSVPackage(string $endpoint)
    {
        $response = $this->client->request(
            'GET',
            $endpoint
        );
        $file = $this->params->get('temp_storage').'data.zip';
        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $fileHandler = fopen($file, 'w');
        foreach ($this->client->stream($response) as $chunk) {
            fwrite($fileHandler, $chunk->getContent());
        }
        $unzip = new \ZipArchive;
        $out = $unzip->open($file);
        if ($out === TRUE) {
            $unzip->extractTo( $this->params->get('temp_storage'));
            $unzip->close();
            echo 'File unzipped';
        } else {
            echo 'Error';
        }
        return true;
    }
}
