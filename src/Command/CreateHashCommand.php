<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Kernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;

class CreateHashCommand extends Command
{
    protected static $defaultName = 'app:create-hash';

    protected function configure(): void
    {
        $this
            ->setDescription('Create news Hashs.')
            ->addArgument(
                'string',
                InputArgument::REQUIRED,
                'The string of the hash.',
            )
            ->addArgument(
                'request',
                InputArgument::REQUIRED,
                'The requests.',
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $stringEntrada = $input->getArgument('string');
        $qtdRequest    = $input->getArgument('request');

        for($i =1 ; $i <= $qtdRequest ;) {
            if($i == 1)
                $retorno = $this->createHash($stringEntrada);
            else{
                $stringEntrada = $retorno[0];
                $retorno = $this->createHash($retorno[0]);
            } 
            
            if($retorno[1] === 200)
                $i++;
            
            $output->writeln('insert >> ' . $stringEntrada . ' status >> '. $retorno[1]);
        }

        $output->writeln('finish.....');

        return Command::SUCCESS;
    }

    public function createHash($stringEntrada){
        $router   = "/create/$stringEntrada";
        $kernel   = new Kernel('prod', false);
        $request  = Request::create($router, 'GET');
        $response = $kernel->handle($request, HttpKernelInterface::MASTER_REQUEST, false);
        $arr = json_decode($response->getContent(), true);

        foreach ($arr as $key => $value) {
            if($key == 'hash'){
                $stringEntrada = $value;
            }
        }

        return array($stringEntrada, $response->getStatusCode()) ;
    }
}