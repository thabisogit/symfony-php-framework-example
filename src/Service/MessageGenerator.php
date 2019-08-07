<?php
// src/Service/MessageGenerator.php
namespace App\Service;

use Psr\Log\LoggerInterface;

class MessageGenerator
{
    private $logger;
    public $message;

    public function __construct(LoggerInterface $logger, $message)
    {
        $this->logger = $logger;
        $this->message = $message;
    }
    
    public function getHappyMessage()
    {
        $this->logger->info('About to find a happy message!');
        $messages = [
            'You did it! You updated the system! Amazing!',
            $this->message,
            'Great work! Keep going!',
        ];

        $index = array_rand($messages);

        return $messages[$index];
    }
}