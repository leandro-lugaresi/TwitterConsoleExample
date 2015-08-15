<?php

namespace TwitterConsole\Stream;

class TweetStream extends \OauthPhirehose
{
    private $producer;

    public function __construct(
        $username,
        $password,
        $consumerKey,
        $consumerSecret
        //OldSound\RabbitMqBundle\RabbitMq\ProducerInterface $producer
    ) {
        //$this->producer = $producer;
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
        //$this->producer->publish("teste tpscp");
        parent::__construct($username, $password, \Phirehose::METHOD_FILTER);
    }

    public function checkFilterPredicates()
	{
		$this->_walllist = array();
		$tmp = $this->_Redis->hGetAll('jiwalls');
		foreach($tmp as $wallid => $clientCount)
		{
			if($clientCount > 0)
			{
				$this->_walllist[$wallid] = $wallid;
			}
		}
		if(empty($this->_walllist) || !isset($this->_walllist['jitt']))
		{
			$this->_walllist['jitt'] = 'jitt';
		}
		$this->setTrack($this->_walllist);
	}

    public function enqueueStatus($status)
    {
        $data = json_decode($status, true);
        if (is_array($data) && isset($data['user']['screen_name'])) {
            $this->producer->publish($status);
        }
    }
}
