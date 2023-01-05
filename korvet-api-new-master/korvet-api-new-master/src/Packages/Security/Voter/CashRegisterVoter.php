<?php

namespace App\Packages\Security\Voter;

use App\Entity\Cash\CashierSchedule;
use App\Interfaces\CashierUserInterface;
use App\Repository\Cash\CashierScheduleRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use App\Entity\Reference\CashRegister;

/**
 * Class CashRegisterVoter
 */
class CashRegisterVoter extends Voter
{

    const ATTRIBUTE_AVAILABLE_CASH_REGISTER = 'ATTRIBUTE_AVAILABLE_CASH_REGISTER';

    const ATTRIBUTE_ALLOW_CLOSE_SHIFT = 'ATTRIBUTE_ALLOW_CLOSE_SHIFT';

    const ATTRIBUTE_ALLOW_OPEN_SHIFT = 'ATTRIBUTE_ALLOW_OPEN_SHIFT';

    const ATTRIBUTE_ALLOW_REGISTER_CASH_RECEIPT = 'ATTRIBUTE_ALLOW_REGISTER_CASH_RECEIPT';

    /** @var CashierScheduleRepository */
    private CashierScheduleRepository $cashierScheduleRepository;

    /** @var Security */
    private Security $authorizationChecker;

    /**
     * CashRegisterVoter constructor.
     *
     * @param CashierScheduleRepository $cashierScheduleRepository
     * @param Security $authorizationChecker
     */
    public function __construct(CashierScheduleRepository $cashierScheduleRepository, Security $authorizationChecker)
    {
        $this->cashierScheduleRepository = $cashierScheduleRepository;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param string $attribute
     * @param CashRegister $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        return is_object($subject) && $subject instanceof CashRegister &&
            in_array($attribute, [
                self::ATTRIBUTE_AVAILABLE_CASH_REGISTER,
                self::ATTRIBUTE_ALLOW_CLOSE_SHIFT,
                self::ATTRIBUTE_ALLOW_REGISTER_CASH_RECEIPT,
                self::ATTRIBUTE_ALLOW_OPEN_SHIFT,
            ]);
    }

    /**
     * @param string $attribute
     * @param CashRegister $subject
     * @param TokenInterface $token
     * @return bool|void
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        if (!$subject instanceof CashRegister) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        if (!$this->authorizationChecker->isGranted('ROLE_CASHIER')) {
            return VoterInterface::ACCESS_DENIED;
        }

        if ($this->authorizationChecker->isGranted('ROLE_ADMIN_CASH_REGISTER')) {
            return VoterInterface::ACCESS_GRANTED;
        }

        /** @var CashierUserInterface $cashier */
        $cashier = $token->getUser();

        $accessBySchedule = false;
        $cashierSchedules = $this->cashierScheduleRepository->findCashierScheduleForDateTime($subject, new \DateTime());
        /** @var CashierSchedule $cashierSchedule */
        foreach ($cashierSchedules as $cashierSchedule) {
            if ($cashierSchedule->getCashier() && $cashierSchedule->getCashier()->getId() == $cashier->getId()) {
                $accessBySchedule = true;
            }
        }

        switch ($attribute) {
            case self::ATTRIBUTE_AVAILABLE_CASH_REGISTER:
                return $accessBySchedule;

            case self::ATTRIBUTE_ALLOW_CLOSE_SHIFT:
            case self::ATTRIBUTE_ALLOW_REGISTER_CASH_RECEIPT:
            case self::ATTRIBUTE_ALLOW_OPEN_SHIFT:
//                $accessAllowCloseShiftIgnoreSchedule = false;
//                $lastSchedule = $this->cashierScheduleRepository->findLastCashierScheduleForCashier($subject, $cashier);
//                if ($lastSchedule) {
//                    $currentDateTime = new \DateTime();
//                    if ($lastSchedule->getDateTo() && $lastSchedule->getDateTo() < $currentDateTime) {
//                        if ($currentDateTime->diff($lastSchedule->getDateTo())->d < 1) {
//                            $accessAllowCloseShiftIgnoreSchedule = getenv('ALLOW_CLOSE_SHIFT_IGNORE_SCHEDULE') == 1;
//                        }
//                    }
//                }

                if (in_array($attribute, [self::ATTRIBUTE_ALLOW_CLOSE_SHIFT, self::ATTRIBUTE_ALLOW_OPEN_SHIFT])) {
                    return $accessBySchedule || $this->authorizationChecker->isGranted('ROLE_SENIOR_CASHIER');
                }

                return $accessBySchedule;
        }
    }
}
