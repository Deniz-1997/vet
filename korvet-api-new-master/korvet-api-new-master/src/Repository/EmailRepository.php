<?php

namespace App\Repository;

use App\Enum\EmailStatus;
use Doctrine\ORM\EntityRepository;

class EmailRepository extends EntityRepository
{
    /**
     * @return array|null
     */
    public function getNewAndNotDeletedEmails(): ?array
    {
        return $this->createQueryBuilder('email')
            ->where('email.status = :status')
            ->andWhere('email.deleted = :deleted')
            ->setParameters([
                'status' => EmailStatus::NEW,
                'deleted' => false
            ])
            ->getQuery()
            ->getResult();
    }
}
