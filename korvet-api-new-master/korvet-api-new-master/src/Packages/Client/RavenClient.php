<?php

namespace App\Packages\Client;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class RavenClient.
 */
class RavenClient extends \Raven_Client
{
    public function __construct($options_or_dsn = null, array $options = array())
    {
        $options['curl_method'] = 'exec';
        parent::__construct($options_or_dsn, $options);
    }

    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;

    /**
     * @var array
     */
    private array $excludedExceptions = [];

    /**
     * @required
     * @param RequestStack $requestStack
     */
    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function captureException($exception, $data = null, $logger = null, $vars = null)
    {
        $exceptionClass = get_class($exception);

        if (in_array($exceptionClass, $this->getExcludedExceptions())) {
            return [];
        }

        if (empty($data['message']) && !empty($data['extra']['message'])) {
            $data['message'] = $data['extra']['message'];
        }

        $this->processTags($data);

        return parent::captureException($exception, $data, $logger, $vars);
    }

    /**
     * @param string $message
     * @param array $params
     * @param array $data
     * @param bool $stack
     * @param null $vars
     * @return mixed
     */
    public function captureMessage($message, $params = array(), $data = array(), $stack = false, $vars = null)
    {
        $this->processTags($data);
        return parent::captureMessage($message, $params, $data, $stack, $vars);
    }

    /**
     * @return array
     */
    public function getExcludedExceptions(): array
    {
        return $this->excludedExceptions;
    }

    /**
     * @param array $excludedExceptions
     */
    public function setExcludedExceptions(array $excludedExceptions)
    {
        $this->excludedExceptions = (array) $excludedExceptions;
    }

    /**
     * @param array $data
     */
    public function sanitize(&$data)
    {
        $old = $data;
        parent::sanitize($data);

        if (!empty($data['extra'])) {
            $data['extra'] = $this->serializer->serialize($old['extra'], 10);
        }

        if (!empty($data['contexts'])) {
            $data['contexts'] = $this->serializer->serialize($old['contexts'], 10);
        }
    }

    /**
     * @param array $data
     */
    private function processTags(&$data)
    {
        if ($this->requestStack && $request = $this->requestStack->getCurrentRequest()) {
            $requestId = $request->headers->get('X-Request-Id');

            $newTag = ['Request-Id' => $requestId];

            if (isset($data['tags']) && is_array($data['tags'])) {
                $data['tags'] = array_merge($data['tags'], $newTag);
            } else {
                $data['tags'] = $newTag;
            }
        }
    }
}
