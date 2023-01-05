<?php
/**
 * Created by PhpStorm.
 * User: anboo
 * Date: 24.08.18
 * Time: 8:27
 */

namespace App\Interfaces;

use Doctrine\ORM\QueryBuilder;

/**
 * Interface ApiControllerInterface
 */
interface ApiControllerInterface
{
    public function getSerializationContext($method): array;
    public function getSerializationContextOptions(): array;
}
