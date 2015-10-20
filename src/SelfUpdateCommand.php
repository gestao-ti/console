<?php

namespace GestaoTI\Console;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SelfUpdateCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('self-update')
            ->setAliases(array('selfupdate'))
            ->setDescription('Update the console to the latest version.')
            ->setHelp('The <info>%command.name%</info> command updates the console to the latest available version.')
        ;
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
        $commands = [
            'composer global remove gestao-ti/console',
            'composer global require gestao-ti/console'
        ];

        $process = new Process(implode(' && ', $commands));
        $process->run(function ($type, $line) use ($output) {
            $output->write($line);
            $output->writeln('<info>Gestao console is already up to date.</info>');
        });

    }
}
