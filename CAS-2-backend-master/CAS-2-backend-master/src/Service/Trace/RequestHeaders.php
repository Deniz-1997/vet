<?php

namespace App\Service\Trace;

use Psr\Http\Message\RequestInterface;
use Zipkin\Propagation\Exceptions\InvalidPropagationCarrier;
use Zipkin\Propagation\Exceptions\InvalidPropagationKey;
use Zipkin\Propagation\Setter;

class RequestHeaders implements Setter
{
    /**
     * @param RequestInterface $carrier
     * {@inheritdoc}
     */
    public function put(&$carrier, $key, $value)
    {
        if ($key !== (string) $key) {
            throw InvalidPropagationKey::forInvalidKey($key);
        }
        if ($key === '') {
            throw InvalidPropagationKey::forEmptyKey();
        }

        if ($carrier instanceof RequestInterface) {
            $carrier = $carrier->withAddedHeader($key, $value);
            return;
        }
        throw InvalidPropagationCarrier::forCarrier($carrier);
    }
}
