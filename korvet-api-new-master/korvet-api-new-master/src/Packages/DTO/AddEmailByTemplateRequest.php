<?php
namespace App\Packages\DTO;


use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AddEmailByTemplateRequest
 * @package App\Webslon\Bundle\EmailBundle\Model
 */
class AddEmailByTemplateRequest extends AbstractAddEmailRequest
{
    /**
     * @var integer
     * @SWG\Property(type="integer", description="Id шаблона оформления")
     * @Assert\Type(type="integer", message="webslon_email_bundle.template.incorrect_type")
     */
    private int $template;
    /**
     * @SWG\Property(type="", description="Объект с ключами и значениями параметров шаблона", default={"param1": "value1", "param2": "value2"})
     */
    private $parameters;
}
