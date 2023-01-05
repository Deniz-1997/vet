<?php

namespace App\Packages\EventSubscriber;

use App\Entity\Reference\Action;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use App\Packages\EventDispatcher\EventRequest;

/**
 * Class ActionSubscriber
 */
class ActionSubscriber implements EventSubscriberInterface
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var RoleHierarchyInterface */
    private $roleHierarchy;

    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;

    /**
     * ActionSubscriber constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param RoleHierarchyInterface $roleHierarchy
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        RoleHierarchyInterface $roleHierarchy,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->roleHierarchy = $roleHierarchy;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'onAfterProcessWebslonBundleCMSBundleEntityReferenceActionGetlist' => 'onAfterActionGetList'
        );
    }

    /**
     * filter granted items(child actions) for response
     * @param EventRequest $event
     */
    public function onAfterActionGetList(EventRequest $event)
    {
        $data = [];

        /** @var $action Action */
        foreach ($event->getData() as $action) {
            $items = $action->getItems();
            if (!empty($items)) {
                /** @var $items ArrayCollection */
                $permittedListActions = $this->filterActions($items->toArray());
                $action->setItems($permittedListActions);
            }

            $data[] = $action;
        }

        $event->setData($data);
    }

    /**
     * @param array $actions
     * @return array
     */
    private function filterActions(array $actions)
    {
        foreach ($actions as $k => $action) {
            foreach ($action->getRoles() as $role) {
                $roleCode = $this->getFormattedCode($role->getCode());
                if (!$this->authorizationChecker->isGranted($roleCode)) {
                    unset($actions[$k]);
                }
            }
        }

        return array_values($actions);
    }

    /**
     * @param string $code
     * @return string
     */
    private function getFormattedCode(string $code)
    {
        if (mb_substr(mb_strtoupper($code), 0, 5) !== 'ROLE_') {
            $code = 'ROLE_'.mb_strtoupper($code);
        }

        return mb_strtoupper($code);
    }
}
