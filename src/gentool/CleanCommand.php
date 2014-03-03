<?php namespace orakaro\IoC\gentool;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use orakaro\IoC\gentool\Template;

class CleanCommand extends Command
{

    /* Configuration of commands */
    protected function configure()
    {
        $this
            ->setName('clean:config')
            ->setDescription('Auto generate IoCConfigFile')
            ->addArgument(
                'IoC_config_file_path',
                InputArgument::OPTIONAL,
                'What is IoCConfig file path ?'
            )
        ;
    }

    /* Execute */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $IoC_config_file_path = $input->getArgument('IoC_config_file_path');
        if (!$IoC_config_file_path) {
            $output->writeln('You have to specify IoCConfig file to delete! ');
            return;
        }
        Template::clean($IoC_config_file_path);
    }


}