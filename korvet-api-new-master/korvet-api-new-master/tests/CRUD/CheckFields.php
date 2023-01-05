<?php

namespace App\Service\Test\CRUD;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

class CheckFields extends TestCase
{
    /**
     * @var array
     */
    private $data;

    public function __construct(array $data)
    {
        parent::__construct();
        $this->data = $data;
    }

    /**
     * @param array $fieldsAndTypes
     */
    public function checkTypes(array $fieldsAndTypes): void
    {
        foreach ($fieldsAndTypes as $key => $type) {
            try {
                if (\is_callable($type)) {
                    $type($this->data[$key]);
                } elseif (\is_array($type)) {
                    (new CheckFields($this->data[$key]))->checkTypes($type);
                } elseif (\count($explode = explode('||', $type)) >= 2) {
                    static::assertThat($this->data[$key], static::logicalOr(
                        static::isType($explode[0]),
                        static::isType($explode[1])
                    ));
                } else {
                    static::assertInternalType($type, $this->data[$key]);
                }
            } catch (\Exception $e) {
                throw new ExpectationFailedException(
                    str_replace('that', $key, $e->getMessage()),
                    null
                );
            }
        }
    }

    /**
     * @param array $request
     */
    public function checkSame(array $request): void
    {
        foreach ($request as $field => $value) {
            if (\is_array($value)) {
                static::assertArraySubset($value, $this->data[$field]);
            } else {
                static::assertSame($request[$field], $this->data[$field]);
            }
        }
    }
}
