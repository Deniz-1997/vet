<?php

namespace App\Service\Test\CRUD;

use App\Kernel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Translation\Translator;

abstract class ExecuteTests extends TestCase
{
    /**
     * @var AbstractCrudTest[]
     */
    private $tests = [];

    /**
     * @var integer
     */
    private $id;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Translator
     */
    protected $translator;

    public function setUp()
    {
        $kernel = new Kernel('test', false);
        $kernel->boot();
        $this->container = $kernel->getContainer();
        $this->translator = $this->container->get('translator');
    }

    public function addTest(AbstractCrudTest $test): void
    {
        $this->tests[] = $test;
    }

    public function execute(): void
    {
        foreach ($this->tests as $key => $test) {
            switch ($test) {
                case $test instanceof CreateTest:
                    $test->testExecute();
                    $this->id = $test->getId();
                    break;
                case $test instanceof NewlyCreatedTest && null !== $this->id:
                    $test->setCreatedId($this->id);
                    $test->testExecute();
                    break;
                default:
                    $test->testExecute();
            }
            // remove executed test from queue
            unset($this->tests[$key]);
        }
    }

    abstract public function test(): void;

    /**
     * @param $lang
     * @param $params
     * @return mixed
     */
    protected function getMessage($lang, $params)
    {
        return $this->translator->trans($lang, $params, 'exception');
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}

