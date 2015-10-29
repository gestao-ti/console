<?php

namespace GestaoTI\Console;

use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('init')->setDescription('Init gestao ti console');
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
        if (!is_dir(gestao_path().'/scripts')) {
            mkdir(gestao_path().'/scripts');
            copy(__DIR__.'/scripts/gestao.rb', gestao_path().'/scripts/gestao.rb');
            copy(__DIR__.'/scripts/serve-apache2.sh', gestao_path().'/scripts/serve-apache2.sh');
            $output->writeln('<comment>==> Gestao: Creating scripts files...</comment> <info>✔</info>');
        } else {
            $output->writeln('<comment>==> Gestao: Scripts files...</comment> <info>✔</info>');
        }
    }
}
