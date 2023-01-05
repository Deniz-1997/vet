<?php

namespace App\Interfaces;

/**
 * Interface HandlerInterface
 */
interface HandlerInterface
{

    /**
     * @param string $type
     * @param array $data
     * @return bool
     */
    public function support(string $type, array $data) : bool;

    /**
     * @param string $type
     * @param array $data
     * @return mixed
     */
    public function handle(string $type, array $data);

    /**
     * @param string $type
     * @param array $errors
     * @return mixed
     */
    public function handleErrors(string $type, array $errors);
}
