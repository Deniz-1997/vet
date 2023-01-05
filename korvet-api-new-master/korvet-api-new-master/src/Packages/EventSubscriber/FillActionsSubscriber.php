<?php


namespace App\Packages\EventSubscriber;

use App\Packages\DBAL\Types\ActionTypeEnum;
use App\Packages\EventDispatcher\GetItemEvent;
use App\Packages\EventDispatcher\GetListEvent;
use App\Packages\Fetcher\LayoutFetcher;
use App\Repository\Reference\ActionRepository;
use App\Service\SerializeService;
use App\Traits\EntityActionsTrait;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class FillActionsSubscriber
 */
class FillActionsSubscriber implements EventSubscriberInterface
{
    /** @var ActionRepository */
    private $actionRepository;

    /** @var Security */
    private $authorizationChecker;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var UserInterface */
    private $currentUser;

    /** @var RequestStack */
    private $requestStack;

    /** @var SerializerInterface */
    private $serializer;

    /** @var LayoutFetcher */
    private $layoutFetcher;

    /**
     * FillActionsSubscriber constructor.
     *
     * @param ActionRepository $actionRepository
     * @param Security $authorizationChecker
     * @param TokenStorageInterface $tokenStorage
     * @param RequestStack $requestStack
     * @param \App\Service\SerializeService $serializer
     * @param LayoutFetcher $layoutFetcher
     */
    public function __construct(
        ActionRepository $actionRepository,
        Security $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        RequestStack $requestStack,
        SerializeService $serializer,
        LayoutFetcher $layoutFetcher
    ) {
        $this->actionRepository = $actionRepository;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
        $this->serializer = $serializer;
        $this->layoutFetcher = $layoutFetcher;

        if ($tokenStorage->getToken() && $tokenStorage->getToken()->getUser()) {
            $this->currentUser = $tokenStorage->getToken()->getUser();
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            GetListEvent::NAME => 'onGetListEvent',
            GetItemEvent::NAME => 'onGetItemEvent',
        ];
    }

    /**
     * @param GetItemEvent $getItemEvent
     */
    public function onGetItemEvent(GetItemEvent $getItemEvent)
    {
        if (!$modes = $this->getModes()) {
            return;
        }

        /** @var EntityActionsTrait $item */
        $item = $getItemEvent->getItem();
        $preparedResponse = [];

        $entityClass = $getItemEvent->getEntityClass();

        if (in_array('data', $modes)) {
            $preparedResponse = array_merge(
                $preparedResponse,
                json_decode($this->serializer->serialize($item, 'json', $getItemEvent->getSerializationContext()), true)
            );
        }

        if (in_array('actions', $modes)) {
            $criteria = Criteria::create()->where(
                Criteria::expr()->orX(
                    Criteria::expr()->eq('viewItemEnabled', true),
                    Criteria::expr()->eq('getItemEnabled', true)
                ))
                ->andWhere(Criteria::expr()->eq('entityClass.className', $entityClass))
                ->andWhere(Criteria::expr()->eq('type', ActionTypeEnum::ENTITY))
                ->andWhere(Criteria::expr()->eq('deleted', false));

            $actions = $this->filterActions(
                $this->actionRepository->createQueryBuilder('a')
                    ->addCriteria($criteria)
                    ->leftJoin('a.roles', 'r')
                    ->getQuery()
                    ->getResult()
            );

            if (method_exists($item, 'getItemActions') && $listActions = $item->getItemActions($this->currentUser, $actions)) {
                $permittedActions = $listActions;
            } else {
                $permittedActions = $actions;
            }

            $preparedResponse['actions'] = $permittedActions;
        }

        if (in_array('listActions', $modes)) {
            $permittedListActions = $this->filterActions(
                $this->actionRepository->findBy([
                    'getListEnabled' => true,
                    'entityClass.className' => $entityClass,
                    'type' => ActionTypeEnum::ENTITY,
                    'deleted' => false,
                ])
            );

            $preparedResponse['listActions'] = $permittedListActions;
        }

        if (in_array('layout', $modes)) {
            $preparedResponse['layout'] = $this->layoutFetcher->getLayout($entityClass, true);
        }

        $getItemEvent->setResponse($preparedResponse);
    }

    /**
     * @param GetListEvent $getListEvent
     */
    public function onGetListEvent(GetListEvent $getListEvent)
    {
        $entityClass = $getListEvent->getEntityClass();
        $modes = $this->getModes();

        $additionalFields = [];

        if (in_array('layout', $modes)) {
            $additionalFields['layout'] = $this->layoutFetcher->getLayout($entityClass, true);
        }
        $additionalFields['columns'] = $this->layoutFetcher->getLayout($entityClass, false);

        $getListEvent->setResponse($additionalFields);
    }

    /**
     * @return array
     */
    private function getModes()
    {
        if (!$this->requestStack || !$this->requestStack->getCurrentRequest()) {
            return [];
        }
        $request = $this->requestStack->getCurrentRequest();

        if (!$request->query->has('mode')) {
            return [];
        }
        $modeParameter = $request->query->get('mode', '');

        if (is_array(json_decode($modeParameter, true))) {
            return json_decode($modeParameter, true);
        }

        return [$modeParameter];
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
