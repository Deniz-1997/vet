<?php

namespace App\Controller;


use App\Entity\Reference\Station;
use App\Entity\User\User;
use App\Exception\ApiException;
use App\Service\CAS\GetStationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Packages\Response\BaseResponse;


/**
 * @Route("/api/userSync")
 */
class UserSyncCasUserController extends AbstractController
{
    private EntityManagerInterface $casEntityManager;
    private EntityManagerInterface $defaultEntityManager;
    private GetStationService $getStationService;

    public function __construct(EntityManagerInterface $casEntityManager,
                                EntityManagerInterface $defaultEntityManager,
                                GetStationService $getStationService)
    {
        $this->casEntityManager = $casEntityManager;
        $this->defaultEntityManager = $defaultEntityManager;
        $this->getStationService = $getStationService;
    }

    /**
     * @param TokenStorageInterface $tokenStorage
     * @return Response
     *
     * @Route("/", methods={"GET"})
     */
    public function syncCasUser(TokenStorageInterface $tokenStorage,BaseResponse $response): Response
    {
        $roles = $tokenStorage->getToken()->getRoles();
        if (in_array('ROLE_ROOT', $roles)) {
            $conn = $this->defaultEntityManager->getConnection();
            $requestUserLk = 'select * from public.users';
            $stmt = $conn->prepare($requestUserLk);
            $users = $stmt->executeQuery()->fetchAllAssociative();
            foreach ($users as $user) {
                if ($user['external_id'] !== null && !is_numeric($user['external_id'])) {
                    $conn = $this->casEntityManager->getConnection();
                    $requestFosUser = "select person_id from auth.fos_user where id = '" . $user['external_id'] . "'";
                    $stmt = $conn->prepare($requestFosUser);
                    $usersFos = $stmt->executeQuery()->fetchAllAssociative();
                    if (count($usersFos) > 0) {
                        $station = $this->getStationService->getUserStationById($usersFos[0]['person_id']);
                        if ($station !== null) {
                            /** @var User $userLk */
                            $userLk = $this->defaultEntityManager->getRepository(User::class)->findOneBy(['externalId' => $user['external_id']]);
                            foreach ($this->getStationService->getChildStation($station->getId()) as $stationChild) {
                                /** @var Station*/
                                $stationChildObject = $this->defaultEntityManager->getRepository(Station::class)->findOneBy(['id' => $stationChild['id']]);
                                $userLk->addStation($stationChildObject);
                            }
                            $userLk->addStation($station);
                            $this->defaultEntityManager->flush();
                        }
                    }
                }
            }
            return  $response->setResponse(['status' => true])->send();
        }
        return  $response->statusError()
            ->addError(new ApiException('Недостаточно прав для выполнения действия, нужены права Суперадминистратор'))
            ->send();
    }
}
