<?php

namespace App\Service;

use App\Packages\AMQP\RabbitRestClient;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;


class TelegramBotService
{
    private const BOT_API = 'https://api.telegram.org';
    /**
     * @var string
     */
    private $apiKey;
    /**
     * @var string
     */
    private $channel;

    /** @var LoggerInterface */
    private LoggerInterface $logger;

    /** @var RabbitRestClient */
    private RabbitRestClient $rabbitmqClient;

    /** @var bool $_now */
    private bool $_now = false;

    /** @var bool $markdown */
    private bool $markdown = false;

    public function __construct(LoggerInterface $logger, RabbitRestClient $rabbitmqClient)
    {
        $this->logger = $logger;
        $this->rabbitmqClient = $rabbitmqClient;
        $this->apiKey = $_ENV['TELEGRAM_TOKEN'];
        $this->channel = $_ENV['TELEGRAM_GROUP_ID'];

        if (!$this->apiKey || !$this->channel) {
            $this->logger->error('Не найдены переменные TELEGRAM_GROUP_ID или TELEGRAM_TOKEN в файле env');
        }
    }

    public function send_message(string $message): void
    {
        if (empty($this->apiKey) || empty($this->channel)) {
            return;
        }

        $message = $this->symbolReplacement($message);

        $url = self::BOT_API . '/bot' . $this->apiKey . '/sendMessage?chat_id='
            . $this->channel . '&text=' . $message;

        if ($this->markdown) {
            $url .= '&parse_mode=markdown';
        }

        if ($this->_now) {
            file_get_contents($url);
        } else {
            $this->rabbitmqClient->request(Request::METHOD_GET, $url);
        }
    }

    public function send_photo(string $message, string $image): void
    {
        if (empty($this->apiKey) || empty($this->channel)) {
            return;
        }

        $message = $this->symbolReplacement($message);

        $url = self::BOT_API . '/bot' . $this->apiKey . '/sendPhoto?chat_id='
            . $this->channel . '&caption=' . $message;

        if ($this->markdown) {
            $url .= '&parse_mode=markdown';
        }

        $image = $GLOBALS["_SERVER"]["HTTP_ORIGIN"].'/'.$image;
        $this->rabbitmqClient
            ->setBody(['photo' => $image])
            ->setHeaders([
                'content-type' => 'multipart/form-data',
                'Host' => 'api.telegram.org'
            ])
            ->request(Request::METHOD_POST, $url);
    }

    /**
     * @param bool $bool
     * @return $this
     */
    public function setNow(bool $bool): TelegramBotService
    {
        $this->_now = $bool;
        return $this;
    }

    /**
     * @param string $channel
     * @return $this
     */
    public function setChannel(string $channel): TelegramBotService
    {
        $this->channel = $channel;
        return $this;
    }

    /**
     * @param bool $markdown
     * @return $this
     */
    public function setMarkDown(bool $markdown): TelegramBotService
    {
        $this->markdown = $markdown;
        return $this;
    }

    private function symbolReplacement(string $message): string
    {
        $message = str_replace('_', '\_', $message);
        return str_replace('&', '%26', $message);
    }
}
