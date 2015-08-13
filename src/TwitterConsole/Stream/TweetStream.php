<?php

namespace TwitterConsole\Stream;

class TweetStream extends \OauthPhirehose
{

    public function __construct($username, $password, $consumerKey, $consumerSecret)
    {
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
        parent::__construct($username, $password, \Phirehose::METHOD_FILTER);
    }

    public function enqueueStatus($status)
    {
        $data = json_decode($status, true);
        if (is_array($data) && isset($data['user']['screen_name'])) {
            print $data['user']['screen_name'] . ': ' . urldecode($data['text']) . "\n";
        }
    }
}
