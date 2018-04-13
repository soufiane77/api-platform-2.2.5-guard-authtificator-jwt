<?php
/**
 * Created by PhpStorm.
 * User: s.aqajjef
 * Date: 10/04/2018
 * Time: 14:33
 */
namespace App\Command {

    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class MyCommand extends Command
    {
        protected function configure()
        {
            $this
                ->setName('api:test')
                ->setDescription('Command pour testé les nouvelles fonctionnalité 4.1')
                ->setHelp('Test des section ');

        }

        protected function execute(InputInterface $input, OutputInterface $output)
        {
            $section = $output->section();
            $section->writeln('Welcome to the installation Process!');
            $section->writeln('Downloading the file...');
            $section->writeln('Uncompressing the file...');
            $section->writeln('Copying the contents...');
// ...
            $section->clear(3);
            $section->writeln('The installation is complete!');
        }




    }
}