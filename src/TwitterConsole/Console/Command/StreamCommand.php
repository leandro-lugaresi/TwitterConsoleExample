<?php

namespace TwitterConsole\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class StreamCommand extends Command
{
    protected function configure()
    {
        $this->setName('twitter:stream')
            ->setDescription('This command will get data from Twitter Stream API')
            ->addArgument(
                'tag',
                InputArgument::OPTIONAL,
                'The hashtag dude!'
            );
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tag = $input->getArgument('tag');

        $output->writeln($tag);
    }
}
