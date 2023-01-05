<?php

namespace App\Service\CAS;

use App\Entity\Reference\Station;
use App\Entity\Security\Group;
use App\Entity\User\User;
use App\Interfaces\CAS\CasAuthInterface;
use App\Entity\Security\Role;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

class CasAuthService extends CasBaseConnector implements CasAuthInterface
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManagerInterface;

    private GetStationService $getStationService;
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(EntityManagerInterface       $entityManagerInterface,
                                GetStationService $getStationService,
                                UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct();
        $this->entityManagerInterface = $entityManagerInterface;
        $this->getStationService = $getStationService;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function loadCasUser($username, $password): ?User
    {
        $body = json_encode(["login" => $username, "password" => $password]);

        if (!$this->casAuthUrl || !$this->casLogin || !$this->casPassword)
            return null;
        $response = $this->httpClientInterface->request(
            'POST',
            $this->casAuthUrl,
            [
                'auth_basic' => [$this->casLogin, $this->casPassword],
                'body' => $body
            ],
        );
        if ($response) {
            $content = json_decode($response->getContent(), true);
            if (isset($content['status']) && $content["status"] == 'success') {
                $userData = $content['user'];
                $user = new User();
                $user->setUsername($userData['username']);
                $user->setEmail($userData['email']);
                $user->setName($userData['name']);
                $user->setSurname($userData['surname']);
                $user->setPatronymic($userData['patronymic']);
                $user->setPhoneNumber($userData['phone']);
                $user->setExternalId($userData['id']);
                $user->setRoles((array)$userData['roles']);
                $user->setPlainPassword($password);
                $user->setSalt(sha1(time()));
                $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPlainPassword()));
                $station = $this->getStationService->getUserStationById($userData['person_id']);
                if ($station !== null) {
                    foreach ($this->getStationService->getChildStation($station->getId()) as $stationChild) {
                        /** @var Station*/
                        $stationChildObject = $this->entityManagerInterface->getRepository(Station::class)->findOneBy(['id' => $stationChild['id']]);
                        $user->addStation($stationChildObject);
                    }
                    $user->addStation($station);
                }
                /** @var Group $group */
                $group = $this->entityManagerInterface->getRepository(Group::class)->findOneBy(['code' => 'ROLE_GOVERNMENT']);
                foreach ((array)$userData['roles'] as $extRole) {
                    $role = $this->entityManagerInterface->getRepository(Role::class)->findOneBy(['code' => $extRole]);
                    if ($role == null) {
                        $role = new Role();
                        $role->setCode($extRole);
                        $role->setName($extRole);
                        $role->setSort(0);
                        if ($group != null) {
                            $role->addGroup($group);
                        }
                        $this->entityManagerInterface->persist($role);
                        $this->entityManagerInterface->flush();
                    }
                }
                if ($group != null) {
                    $user->addGroup($group);
                }
                $this->entityManagerInterface->persist($user);
                $this->entityManagerInterface->flush();

                return $user;
            }
        }
        return null;
    }
}
