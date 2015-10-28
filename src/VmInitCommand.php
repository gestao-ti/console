<?php

namespace GestaoTI\Console;

use InvalidArgumentException;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VmInitCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('vm:init')
            ->setDescription('Init virtual machine')
            ->addArgument('machine', InputArgument::REQUIRED, 'Virtual machine.');
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
        $machine = $input->getArgument('machine');
        $command = 'vagrant init gestao-ti/'.$machine;
        $path_machine = gestao_path_vms().DIRECTORY_SEPARATOR.$machine;

        if (is_dir($path_machine)) {
            throw new InvalidArgumentException($machine.' has already been initialized.');
        }

        exec("mkdir -p {$path_machine}");
        $process = new Process($command, $path_machine, null, null, null);
        $process->run(function ($type, $line) use ($output, $machine,$path_machine) {
            $output->write($line);
        });
        
        $output->writeln('<comment>==> Gestao: Machine <info>'.$machine.'</info> initialized in:</comment> '.$path_machine);

        copy(__DIR__.'/stubs/Gestao.yaml', $path_machine.'/Gestao.yaml');
        copy(__DIR__.'/stubs/after.sh', $path_machine.'/after.sh');
        copy(__DIR__.'/stubs/aliases', $path_machine.'/aliases');

        $output->writeln('<comment>==> Gestao: Creating Gestao.yaml file...</comment> <info>✔</info>');
        $output->writeln('<comment>==> Gestao: Gestao.yaml file created at:</comment> '.$path_machine.'/Gestao.yaml');

    }
}
