<?php

namespace GestaoTI\Console;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VmCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('vm:up')
            ->setDescription('Start virtual machine')
            ->addOption('machine', null, InputOption::VALUE_NONE, 'Run the virtual machine on the vagrant.');
    }

    /**
     * Execute the command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $command = 'vagrant up';
        $machine = $input->getOption('machine');

        if ($machine) {
            $_ENV['VAGRANT_DOTFILE_PATH'] = $_ENV['VAGRANT_DOTFILE_PATH']
                .DIRECTORY_SEPARATOR
                .$machine
                .DIRECTORY_SEPARATOR
                .'.vagrant';
        }

        $process = new Process($command, realpath(__DIR__.'/../'), array_merge($_SERVER, $_ENV), null, null);

        $process->run(function ($type, $line) use ($output) {
            $output->write($line);
        });
    }
}
