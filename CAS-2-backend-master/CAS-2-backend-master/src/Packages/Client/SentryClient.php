<?php


namespace App\Packages\Client;


use Sentry\SentryBundle\SentrySymfonyClient;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class SentryClient
 */
class SentryClient extends SentrySymfonyClient
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * SentryClient constructor.
     * @param string|null $dsn
     * @param array $options
     */
    public function __construct(?string $dsn = null, array $options = [])
    {
        parent::__construct($dsn, $options);

        $this->processTags($options);
    }

    /**
     * @required
     */
    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
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

