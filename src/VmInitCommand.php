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
     * The base path of the machine installation.
     *
     * @var string
     */
    protected $basePathMachine;

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
        $this->basePathMachine = gestao_path_vms().DIRECTORY_SEPARATOR.$machine;

        if (is_dir($path_machine)) {
            throw new InvalidArgumentException($machine.' has already been initialized.');
        }

        exec("mkdir -p {$path_machine}");
//        $process = new Process($command, $path_machine, null, null, null);
//        $process->run(function ($type, $line) use ($output, $machine,$path_machine) {
//            $output->write($line);
//        });

        if (!file_exists($path_machine.'/Gestao.yaml')) {
            copy(__DIR__.'/stubs/Gestao.yaml', $path_machine.'/Gestao.yaml');
            $this->updateName($machine);
            $output->writeln('<comment>==> Gestao: Creating Gestao.yaml file...</comment> <info>✔</info>');
        }

        if (!file_exists($path_machine.'/after.sh')) {
            copy(__DIR__.'/stubs/after.sh', $path_machine.'/after.sh');
            $output->writeln('<comment>==> Gestao: Creating after.sh file...</comment> <info>✔</info>');
        }

        if (!file_exists($path_machine.'/aliases.sh')) {
            copy(__DIR__.'/stubs/aliases', $path_machine.'/aliases');
            $output->writeln('<comment>==> Gestao: Creating aliases file...</comment> <info>✔</info>');
        }

        if (!file_exists($path_machine.'/Vagrantfile')) {
            copy(__DIR__.'/stubs/Vagrantfile', $path_machine.'/Vagrantfile');
            $output->writeln('<comment>==> Gestao: Creating Vagrantfile file...</comment> <info>✔</info>');
        }

        $output->writeln('<comment>==> Gestao: Machine <info>'.$machine.'</info> initialized in:</comment> '.$path_machine);

    }

    /**
     * Update paths in Homestead.yaml.
     *
     * @return void
     */
    protected function configurePaths()
    {
        $yaml = str_replace(
            '- map: ~/Projects', '- map: "'.str_replace('\\', '/',$this->basePathMachine).'"', $this->getGestaoFile()
        );

        $yaml = str_replace(
            'to: /home/projects/code', 'to: "/home/vagrant/'.$this->basePathMachine.'"', $yaml
        );

        file_put_contents($this->basePathMachine.'/Gestao.yaml', $yaml);
    }

    protected function updateName($name)
    {
        file_put_contents($this->basePathMachine.'/Gestao.yaml', str_replace(
            'cpus: 1', 'cpus: 1'.PHP_EOL.'name: gestao-ti/'.$name, $this->getGestaoFile()
        ));
    }

    protected function updateHostName($hostname)
    {
        file_put_contents($this->basePathMachine.'/Gestao.yaml', str_replace(
            'cpus: 1', 'cpus: 1'.PHP_EOL.'hostname: '.$hostname, $this->getGestaoFile()
        ));
    }

    protected function getGestaoFile()
    {
        return file_get_contents($this->basePathMachine.'/Gestao.yaml');
    }

}
