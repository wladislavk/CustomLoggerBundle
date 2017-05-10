<?php

namespace VKR\CustomLoggerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use VKR\CustomLoggerBundle\Services\LogCreator;

class CreateLogsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('vkr:logger:create')
            ->setDescription('Creates all non-existent files specified in manifest')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var LogCreator $logCreator */
        $logCreator = $this->getContainer()->get('vkr_custom_logger.log_creator');

        $logCreator->createLogs($output);
        $output->writeln('Logs created successfully');
    }
}
