<?php namespace orakaro\IoC\gentool;

require_once __DIR__ . '/../../vendor/autoload.php';
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class GenCommand extends Command
{

    /* Configuration of commands */
    protected function configure()
    {
        $this
            ->setName('gen:config')
            ->setDescription('Auto generate IoCConfigFile')
            ->addArgument(
                'config_file',
                InputArgument::OPTIONAL,
                'What is config file path ?'
            )
            ->addOption(
               'nofolder',
               null,
               InputOption::VALUE_NONE,
               'If set, the config file will be output directly'
            )
        ;
    }

    /* Execute */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $config_file = $input->getArgument('config_file');
        if (!$config_file) {
          $config_file = 'genConfig.php';
        }

        if (!file_exists($config_file)){
            $output->writeln('No config file specified and no genConfig.php in this folder! ');
            return;
        }

        $config = require_once $config_file;
        $dir = $config['config_dir'];
        $staticClasses = $config['static_classes'];

        if ($input->getOption('nofolder')) {
            $dir = '.';
        }
        $dir = $dir.'/';

        $output->writeln($dir);
        foreach ($staticClasses as $class => $method) {
            $output->writeln('Class: ' .$class);
            $output->writeln('Method: ' .$method);
        }
        return;
    }
}