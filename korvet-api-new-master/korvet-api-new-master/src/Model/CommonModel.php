<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CommonModel
 */
class CommonModel
{
    const FIELDS = 'fields';

    const FILTER = 'filter';

    const GROUP = 'group';

    const ORDER = 'order';

    const LIMIT = 'limit';

    const OFFSET = 'offset';

    const DOWNLOAD = 'download';

    /**
     * List fields add to result
     * 
     * @var string
     */
    public string $fields;
    
    /**
     * List filters
     *
     * @var string
     */
    public $filter = [];
    /**
     * @var string
     */
    public $group = [];
    /**
     * @var string
     */
    public $order = [];
    /**
     * @var string
     */
    public $limit = 20;
    /**
     * смещение для limit
     * @var string
     */
    public $offset = 0;
    /**
     * Format file to download
     *
     * @var string
     */
    public string $download = '';
    
    /**
     * Allowed filers
     *
     * @return array
     */
    public function getAllowedFilters(): array
    {
        return [
            self::FIELDS,
            self::FILTER,
            self::ORDER,
            self::GROUP,
            self::LIMIT,
            self::OFFSET,
            self::DOWNLOAD,
        ];
    }
}
