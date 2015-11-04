<?php

namespace GestaoTI\Console;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VmEditCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('vm:edit')
            ->setDescription('Edit Vagrantfile of virtual machine')
            ->addArgument('machine', InputArgument::REQUIRED, 'Virtual machine on the vagrant.');
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
        $file = 'Gestao.yaml';
        $machine = $input->getArgument('machine');
        $path_machine = gestao_path_vms().DIRECTORY_SEPARATOR.$machine;

        if (!is_dir($path_machine)) {
            (new VmInitCommand())->execute($input, $output);
        }

        $command = $this->executable($path_machine.DIRECTORY_SEPARATOR.$file);

        $process = new Process($command, $path_machine, null, null, null);

        $process->run(function ($type, $line) use ($output) {
            $output->write($line);
        });
    }

    /**
     * Find the correct executable to run depending on the OS.
     *
     * @return string
     */
    protected function executable($file)
    {
        if (strpos(strtoupper(PHP_OS), 'WIN') === 0) {
            return 'start ' . $file;
        } elseif (strpos(strtoupper(PHP_OS), 'DARWIN') === 0) {
            return 'open ' . $file;
        }

        return 'xdg-open ' . $file . ' >/dev/null';
    }
}
