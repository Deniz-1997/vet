<?php

namespace App\Entity;

use App\Entity\User\User;
use OpenApi\Annotations as SWG;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Packages\Annotation\Enqueue\Consume;
use App\Packages\Annotation\Enqueue\CrudBatchConsume;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Packages\AMQP\Router\Route;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserScheduleRepository")
 * @CrudBatchConsume({
 *     Route::TOPIC_CREATE_PREFIX: @Consume(
 *          queue="%kernel.environment%.korvet.entity.create.batchCrud",
 *     ),
 *     Route::TOPIC_UPDATE_PREFIX: @Consume(
 *          queue="%kernel.environment%.korvet.entity.update.batchCrud",
 *     ),
 *     Route::TOPIC_REPLACE_PREFIX: @Consume(
 *          queue="%kernel.environment%.korvet.entity.replace.batchCrud",
 *     ),
 *     Route::TOPIC_DELETE_PREFIX: @Consume(
 *          queue="%kernel.environment%.korvet.entity.delete.batchCrud",
 *     )
 * })
 */
class UserSchedule
{
    use OrmIdTrait, OrmCreatedAtTrait, OrmDeletedTrait;

    /**
     * @var \DateTime Начало периода
     * @Groups({"default"})
     * @SWG\Property(description="Начало периода")
     * @Assert\NotBlank(message="user_schedule.date_from.not_blank")
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $dateFrom;

    /**
     * @var \DateTime Конец периода
     * @Groups({"default"})
     * @SWG\Property(description="Конец периода")
     * @Assert\NotBlank(message="user_schedule.date_to.not_blank")
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $dateTo;

    /**
     * @var User
     * @Groups({"default"})
     * @Assert\NotBlank(message="user_schedule.user.not_blank")
     * @SWG\Property(description="Сотрудник")
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User", inversedBy="userSchedules")
     */
    private $employee;

    /**
     * @return \DateTime
     */
    public function getDateFrom(): \DateTime
    {
        return $this->dateFrom;
    }

    /**
     * @param \DateTime $dateFrom
     * @return UserSchedule
     */
    public function setDateFrom(\DateTime $dateFrom): UserSchedule
    {
        $this->dateFrom = $dateFrom;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateTo(): \DateTime
    {
        return $this->dateTo;
    }

    /**
     * @param \DateTime $dateTo
     * @return UserSchedule
     */
    public function setDateTo(\DateTime $dateTo): UserSchedule
    {
        $this->dateTo = $dateTo;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getEmployee(): ?User
    {
        return $this->employee;
    }

    /**
     * @param User|null $employee
     * @return UserSchedule
     */
    public function setEmployee(?User $employee): UserSchedule
    {
        $this->employee = $employee;
        return $this;
    }

}
