<?php

namespace GestaoTI\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class VmReloadCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('vm:reload')
            ->setDescription('Reload the virtual machine')
            ->addArgument('machine', InputArgument::REQUIRED, 'Virtual machine.');
    }

    /**
     * Execute the command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $machine = $input->getArgument('machine');
        $command = 'vagrant reload --provision';
        $path_machine = gestao_path_vms().DIRECTORY_SEPARATOR.$machine;

        $process = new Process($command, $path_machine, null, null, null);
        $process->run(function ($type, $line) use ($output, $machine, $path_machine) {
            $output->write($line);
        });
    }
}
