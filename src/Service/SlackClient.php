<?php

namespace App\Service;

use App\Helper\LoggerTrait;
use Nexy\Slack\Client;

/**
 * Class SlackClient.
 */
class SlackClient
{
    use LoggerTrait;

    /** @var Client */
    private $slack;

    /**
     * SlackClient constructor.
     */
    public function __construct(Client $slack)
    {
        $this->slack = $slack;
    }

    public function sendMessage(string $from, string $message)
    {
        if ($this->logger) {
            $this->logger->info('Beaming a message to Slack!');
        }

        $message = $this->slack->createMessage()
                               ->from($from)
                               ->withIcon(':ghost:')
                               ->setText($message);
        $this->slack->sendMessage($message);
    }
}
