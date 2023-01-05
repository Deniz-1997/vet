<?php

namespace App\Service\Trace;

use GuzzleHttp\Promise;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\RequestInterface;
use Zipkin\Tags;

class GuzzleMiddleware
{
    /**
     * @param $prefix
     * @param array $tags the default tags being added to the span.
     * @return callable
     */
    public static function create($prefix, array $tags = [])
    {
        return function (callable $handler) use ($tags, $prefix) {
            return function (RequestInterface $request, array $options) use ($prefix, $handler, $tags) {
                $span = Tracer::getInstance()->createSpan('call', $prefix.' '.$request->getMethod().' '.$request->getUri()->getPath(), 'CLIENT');
                $span->setName($prefix.' '.$request->getMethod().' '.(string)$request->getUri());
                $span->tag(Tags\HTTP_METHOD, $request->getMethod());
                $span->tag(Tags\HTTP_PATH, $request->getUri()->getPath());
                foreach ($tags as $key => $value) {
                    $span->tag($key, $value);
                }
                $scopeCloser = Tracer::getInstance()->getTracing()->getTracer()->openScope($span);
                $injector = Tracer::getInstance()->getTracing()->getPropagation()->getInjector(new \Zipkin\Propagation\RequestHeaders());
                $injector($span->getContext(), $request);
                $span->start();
                return $handler($request, $options)->then(
                    function (ResponseInterface $response) use ($span, $scopeCloser) {
                        $span->tag(Tags\HTTP_STATUS_CODE, $response->getStatusCode());
                        if ($response->getStatusCode() > 399) {
                            $response->getBody()->rewind();
                            $span->tag(Tags\ERROR, $response->getReasonPhrase());
                            $span->tag('response', $response->getBody()->getContents());
                            $response->getBody()->rewind();
                        }
                        $span->finish();
                        $scopeCloser();
                        return $response;
                    },
                    function ($reason) use ($span, $scopeCloser) {
                        $response = $reason instanceof RequestException
                            ? $reason->getResponse()
                            : null;
                        $span->tag(Tags\ERROR, $reason->getResponse());
                        if ($response !== null) {
                            $span->tag(Tags\HTTP_STATUS_CODE, $response->getStatusCode());
                        }
                        $span->finish();
                        $scopeCloser();
                        return Promise\rejection_for($reason);
                    }
                );
            };
        };
    }
}
