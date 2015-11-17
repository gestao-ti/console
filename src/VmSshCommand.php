<?php

namespace GestaoTI\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VmSshCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('vm:ssh')
             ->setDescription('Login to the Homestead machine via SSH')
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
        $path_machine = gestao_path_vms().DIRECTORY_SEPARATOR.$machine;

        chdir($path_machine);
        passthru('vagrant ssh');
    }
}
