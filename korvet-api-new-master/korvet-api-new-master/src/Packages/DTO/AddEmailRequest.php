<?php
namespace App\Packages\DTO;

use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AddEmailRequest
 * @package App\Webslon\Bundle\EmailBundle\Model
 */
class AddEmailRequest extends AbstractAddEmailRequest
{
    /**
     * @var string
     * @SWG\Property(type="string", description="Тело письма")
     */
    private string $body;
    /**
     * @var integer
     * @SWG\Property(type="integer", description="Id темы оформления, если тема не указана - будет использована тема по умолчанию.")
     * @Assert\Type(type="integer", message="webslon_email_bundle.theme.incorrect_type")
     */
    private int $theme;
}
