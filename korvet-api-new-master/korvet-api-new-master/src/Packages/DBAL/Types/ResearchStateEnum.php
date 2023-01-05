<?php

namespace App\Packages\DBAL\Types;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;


/**
 * Class ResearchStateEnum
 * @EnumAnnotation
 * @package App\Packages\DBAL\Types
 */
class ResearchStateEnum extends Enum
{
    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = ResearchStateEnum::class;

    public const CORRUPTED = 'CORRUPTED';
    public const CREATE = 'CREATE';
    public const SENT = 'SENT';
    public const RECEIVED = 'RECEIVED';
    public const PROCESSING = 'PROCESSING';
    public const DONE = 'DONE';

    /**
     * @var array
     */
    protected static $choices = [
        self::CORRUPTED => 'enum.research_state.corrupted',
        self::CREATE => 'enum.research_state.create',
        self::SENT => 'enum.research_state.sent',
        self::RECEIVED => 'enum.research_state.received',
        self::PROCESSING => 'enum.research_state.processing',
        self::DONE => 'enum.research_state.done',
    ];

}
