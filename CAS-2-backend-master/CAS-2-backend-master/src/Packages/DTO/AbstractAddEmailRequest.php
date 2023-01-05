<?php
namespace App\Packages\DTO;

use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AddEmailAbstract
 * @package App\Webslon\Bundle\EmailBundle\Model
 */
abstract class AbstractAddEmailRequest
{
    /**
     * @var string
     * @SWG\Property(
     *   property="",
     *   type="string",
     *   title="",
     *   format="email",
     *   description="Представление значения"
     * )
     * @Assert\NotBlank(message="email_bundle.email_from.not_blank")
     * @Assert\Email(message="webslon_email_bundle.email_from.incorrect_email")
     */
    public string $emailFrom;
    /**
     * @var string
     * @SWG\Property(type="string", format="email", description="Email получателя", default="emailTo@example.com")
     * @Assert\NotBlank(message="email_bundle.email_to.not_blank")
     * @Assert\Email(message="webslon_email_bundle.email_to.incorrect_email")
     */
    public string $emailTo;
    /**
     * @var string
     * @SWG\Property(type="string", description="Тема письма")
     * @Assert\NotBlank(message="webslon_email_bundle.subject.not_blank")
     */
    public string $subject;
    /**
     * @var string
     * @SWG\Property(type="string", format="email", description="Обратный адрес", default="replyTo@example.com")
     * @Assert\Email(message="webslon_email_bundle.reply_to.incorrect_email")
     */
    public string $replyTo;
    /**
     * @var string[]
     * @SWG\Property(type="array", items=@SWG\Items(type="string", format="email"), description="Массив с email'ами для копии")
     * @Assert\All({@Assert\Email(message="webslon_email_bundle.cc.incorrect_email")})
     */
    public array $cc;
    /**
     * @var string[]
     * @SWG\Property(type="array", items=@SWG\Items(type="string", format="email"), description="Массив с email'ами для скрытой копии")
     * @Assert\All({@Assert\Email(message="webslon_email_bundle.bcc.incorrect_email")})
     */
    public array $bcc;
}
