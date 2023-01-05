<?php

namespace App\Service\Trace;

use App\Model\Env;
use Symfony\Component\HttpFoundation\Request;
use Zipkin\DefaultTracing;
use Zipkin\Endpoint;
use Zipkin\Propagation\Map;
use Zipkin\Reporters\Http;
use Zipkin\Samplers\BinarySampler;
use Zipkin\Span;
use Zipkin\Tracing;
use Zipkin\TracingBuilder;

/**
 * Class Tracer
 */
class Tracer
{
    /** @var Span */
    private Span $mainSpan;

    /** @var Span[] */
    private array $spans;

    /** @var Tracing */
    private Tracing $tracing;

    /** @var string */
    private $serviceName;

    /** @var ?Tracer */
    private static ?Tracer $instance = null;

    /**
     * Tracer constructor.
     * @param string $serviceName
     */
    private function __construct($serviceName = null)
    {
        $this->serviceName = $serviceName ? $serviceName : Env::getenv('MICROSERVICE_NAME');
    }

    /**
     * @param string $serviceName
     * @return Tracer
     */
    public static function getInstance($serviceName = null): Tracer
    {
        if (self::$instance) {
            return self::$instance;
        }

        self::$instance = new self($serviceName);

        $request = Request::createFromGlobals();
        $endpoint = Endpoint::create(self::$instance->serviceName ? self::$instance->serviceName : $request->getHost());
        $reporter = new Http(['endpoint_url' => Env::getenv('ZIPKIN_ENDPOINT_URL')], new AsyncCurlFactory());
        $sampler = BinarySampler::createAsAlwaysSample();

        $tracing = TracingBuilder::create()
            ->havingLocalEndpoint($endpoint)
            ->havingSampler($sampler)
            ->havingReporter($reporter)
            ->build();

        $carrier = array_map(function ($header) {
            return $header[0];
        }, $request->headers->all());

        $extractor = $tracing->getPropagation()->getExtractor(new Map());
        $extractedContext = $extractor($carrier);

        $tracer = $tracing->getTracer();

        $span = $tracer->nextSpan($extractedContext);
        $span->start();
        $span->setKind('SERVER');
        $span->setName('receive request '.$request->getHost().$request->getRequestUri());

        self::$instance->tracing = $tracing;
        self::$instance->mainSpan = $span;
        self::$instance->registerSpan('_main', $span);

        return self::$instance;
    }

    /**
     * @param string $id
     * @param string $name
     * @param string $kind
     * @param array $tags
     * @param Span|null $parentSpan
     * @return Span
     */
    public function createSpan(string $id, string $name, string $kind, array $tags = [], Span $parentSpan = null): Span
    {
        if (!$parentSpan) {
            $parentSpanContext = $this->mainSpan->getContext();
        } else {
            $parentSpanContext = $parentSpan->getContext();
        }

        $tracer = $this->tracing->getTracer();

        $childSpan = $tracer->newChild($parentSpanContext);
        $childSpan->start();
        $childSpan->setKind($kind);
        $childSpan->setName($name);

        foreach ($tags as $tag => $value) {
            $childSpan->tag($tag, $value);
        }
        
        $this->registerSpan($id, $childSpan);

        return $childSpan;
    }

    /**
     * @return Tracing
     */
    public function getTracing(): Tracing
    {
        return $this->tracing;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function finishSpan(string $id): bool
    {
        if ($this->getSpan($id)) {
            $this->spans[$id]->finish();
            return true;
        }

        return false;
    }

    /**
     * @param $id
     * @param Span $span
     */
    private function registerSpan($id, Span $span)
    {
        $this->spans[$id] = $span;
    }

    /**
     * @param $id
     * @return Span|null
     */
    private function getSpan($id): ?Span
    {
        return $this->spans[$id] ?? null;
    }

    /**
     * @return void
     */
    public function flush()
    {
        $this->mainSpan->finish();
        $this->tracing->getTracer()->flush();
    }
}
