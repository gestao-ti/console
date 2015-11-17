<?php

namespace GestaoTI\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VmRunCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('vm:run')
             ->setDescription('Run commands through the virtual machine via SSH')
             ->addArgument('machine', InputArgument::REQUIRED, 'Virtual machine.')
             ->addArgument('ssh-command', InputArgument::REQUIRED, 'The command to pass through to the virtual machine.');
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

        $command = $input->getArgument('ssh-command');
        chdir($path_machine);
        passthru('vagrant ssh -c "'.$command.'"');
    }
}
