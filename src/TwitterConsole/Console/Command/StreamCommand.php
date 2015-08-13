<?php

namespace TwitterConsole\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TwitterConsole\Stream\TweetStream;

class StreamCommand extends Command
{
    private $stream;

    public function __construct(\OauthPhirehose $stream, $name = null)
    {
        $this->stream = $stream;
        parent::__construct($name);
    }

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

        $this->stream->setTrack(array('#'.$tag));
        $this->stream->consume();
    }
}
