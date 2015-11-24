<?php

namespace GestaoTI\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class SelfUpdateCommand extends Command
{
    /**
     * @var OutputInterface
     */
    private $output;

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('self-update')
            ->setAliases(['selfupdate'])
            ->setDescription('Update the console to the latest version.')
            ->setHelp('The <info>%command.name%</info> command updates the console to the latest available version.')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force update.');
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
        $commands = [
            'composer global remove gestao-ti/console',
            'composer global require gestao-ti/console',
        ];

        if (!$this->installerIsUpdated() || $input->getOption('force')) {
            $process = new Process(implode(' && ', $commands), null, null, null, null);
            $process->run(function ($type, $line) use ($output) {
                $output->write($line);
            });
        }
        $this->output->writeln('<info>Gestao Console up to date.</info>');
        rmdir(gestao_path().'/scripts');
    }

    public function installerIsUpdated()
    {
        $isUpdated = false;
        $localVersion = $this->getApplication()->getVersion();

        if (false === $remoteManifest = @file_get_contents('https://raw.githubusercontent.com/gestao-ti/console/master/manifest.json')) {
            throw new \RuntimeException('The new version of the Gestao Console couldn\'t be downloaded from the server.');
        }

        $manifest = json_decode($remoteManifest);
        $remoteVersion = $manifest->version;
        if ($localVersion === $remoteVersion) {
            $this->output->writeln('<info>Gestao Console is already up to date.</info>');
            $isUpdated = true;
        } else {
            $this->output->writeln(sprintf('<info>Updating</info> Gestao Console to <comment>%s</comment> version', $remoteVersion));
        }

        return $isUpdated;
    }
}
