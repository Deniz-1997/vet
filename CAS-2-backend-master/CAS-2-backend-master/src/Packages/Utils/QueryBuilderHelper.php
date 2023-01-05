<?php

namespace App\Packages\Utils;

use Doctrine\ORM\QueryBuilder;
use Error;
use Exception;
use RuntimeException;
use Throwable;

/**
 * Class QueryBuilderHelper
 */
class QueryBuilderHelper
{
    public static function showQuery(QueryBuilder $query): string
    {
        // define vars
        $output    = NULL;
        $out_query = $query->getQuery()->getSql();
        $out_param = $query->getQuery()->getParameters();
        $len = strlen($out_query);
        $j=0;
        // replace params
        for($i=0; $i < $len; $i++) {
            try {
                $output .= ( strpos($out_query[$i], '?') !== FALSE ) ? "'" .str_replace('?', $out_param[$j++]->getValue(), $out_query[$i]). "'" : $out_query[$i];
            } catch (Error | Throwable |Exception | RuntimeException $exception) {
                $mess = $exception->getMessage();
            }
        }

        // output
        return sprintf("%s", $output);
    }
}
