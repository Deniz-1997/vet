<?php

namespace App\Packages\Annotation;


use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * @Annotation
 *
 * Class Response
 */
class Response extends \OpenApi\Annotations\Response
{
    const DEFAULT_LIST_RESPONSE = 'list';

    const DEFAULT_ITEM_RESPONSE = 'item';

    const DEFAULT_UPDATE_RESPONSE = 'update';

    const DEFAULT_DELETE_RESPONSE = 'update';

    const DEFAULT_ERROR_UPDATE_RESPONSE = 'error.update';

    const DEFAULT_ERROR_CREATE_RESPONSE = 'error.create';

    const DEFAULT_ERROR_DELETE_RESPONSE = 'error.delete';


    /**
     * Identifer for unique response and overriding default reponses
     *
     * @var string
     */
    public string $id;

    /**
     * @var string
     */
    public string $model;
}
