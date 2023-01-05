<?php

namespace App\Packages\Monolog\Guzzle;

use App\Model\Env;
use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class MessageFormatter
 */
class MessageFormatter extends \GuzzleHttp\MessageFormatter
{
    /**
     * @var array
     */
    private array $loggingContext;

    /**
     * MessageFormatter constructor.
     * @param array $loggingContext
     * @param string $template
     */
    public function __construct(array $loggingContext = [], string $template = \GuzzleHttp\MessageFormatter::CLF)
    {
        parent::__construct($template);
        $this->loggingContext = $loggingContext;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface|null $response
     * @param Exception|null $error
     * @return false|string
     */
    public function format(RequestInterface $request, ?ResponseInterface $response = null, ?\Throwable $error = null): string
    {
        $array = [];
        $array['request'] = $array['response'] = [];
        $array['request']['headers'] = $array['response']['headers'] = [];
        $array['guzzle'] = true;
        $array['env'] = Env::getenv('APP_ENV');
        $array['time'] = time();

        $requestHeaders = [];
        foreach ($request->getHeaders() as $header => $headerValue) {
            $requestHeaders[$header] = implode(' ', $headerValue);
        }
        $array['request']['headers'] = json_encode($requestHeaders);

        $request->getBody()->rewind();

        $array['request']['method'] = $request->getMethod();
        $array['request']['uri'] = (string)$request->getUri();
        $array['request']['body'] = (string)$request->getBody();
        $array['request']['protocol'] = $request->getProtocolVersion();

        if ($response) {
            $response->getBody()->rewind();
            
            $array['response']['body'] = (string)$response->getBody();
            $array['response']['statusCode'] = $response->getStatusCode();
            $array['response']['phrase'] = $response->getReasonPhrase();

            $responseHeaders = [];
            foreach ($response->getHeaders() as $header => $headerValue) {
                $responseHeaders[$header] = implode(' ', $headerValue);
            }
            $array['response']['headers'] = json_encode($responseHeaders);

            $response->getBody()->rewind();
        }
        
        $array['message'] = sprintf('Matched request %s', $array['request']['uri']);

        if ($error) {
            $array['error'] = [
                'code' => $error->getCode(),
                'message' => $error->getMessage(),
            ];
        }

        $array = array_merge($array, $this->loggingContext);

        $request->getBody()->rewind();

        return json_encode($array);
    }
}
