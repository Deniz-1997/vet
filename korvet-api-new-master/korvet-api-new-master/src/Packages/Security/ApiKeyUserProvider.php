<?php

namespace App\Packages\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class ApiKeyUserProvider implements UserProviderInterface
{
    public function getUsernameForApiKey($apiKey): ?string
    {
        // Look up the username based on the token in the database, via
        // an API call, or do something entirely different
        $users = [
            "YZkhSmrVMfX4z3MIE93Eq1eHcNk6k2Vyp7GKBehWMsgQCy6n4Q"=> "admin",
            "7k55sIS6PqB4iI0iZBDJ8A4WrpQYfMgmeLsLJC0TKxYfYIrlCa"=>"bitrix", // ДВ Битрикс
            "KMzoZBXnARrsijXvMx7hFQHJlG1uzP7nlEHaAvv2ReAAl8Rl8O"=>"delivery", // Доставка Битрикс
            "wLinczIWaKvSD6ZoY44ANd2Azut7opRTsxXVNGMd9JkVe9bQNG"=>"marketplace", // Маркетплейс
            "tVu0S5rMpcyZXzgBMw9tAcnSou8kqVLXkZ4VcDcY2QdMu1MCVC"=>"marketplaceFront", // Маркетплейс фронт
            "ri8EwLvGcrwnVy4teRzazcc08B0atXNvH8woxN5chpEv4a2lhZ"=>"legoDelivery", // LEGO Delivery
        ];

        $username = null;

        if (array_key_exists($apiKey, $users)) {
            $username = $users[$apiKey];
        }

        return $username;
    }

    public function loadUserByUsername($username): User
    {
        $roles = [
            "admin" => ["ROLE_ADMIN", "ROLE_ASD_ADMIN", "ROLE_ASD_REFERENCES_READ"],
            "bitrix" => ["ROLE_ASD_REFERENCES_READ"],
            "delivery" => ["ROLE_ASD_REFERENCES_READ", "ROLE_ASD_ORDER_CREATE"],
            "marketplace" => ["ROLE_ASD_REFERENCES_READ", "ROLE_ASD_ORDER_CREATE"],
            "marketplaceFront" => ["ROLE_ASD_REFERENCES_READ"],
            "legoDelivery" => ["ROLE_ASD_REFERENCES_READ", "ROLE_ASD_ORDER_CREATE", "ROLE_ORDERS_ORDER_DELIVERY_STATUS_UPDATE"],
        ];

        return new User(
            $username,
            null,
            // the roles for the user - you may choose to determine
            // these dynamically somehow based on the user
            $roles[$username]
        );
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        // this is used for storing authentication in the session
        // but in this example, the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless
        throw new UnsupportedUserException();
    }

    public function supportsClass($class): bool
    {
        return User::class === $class;
    }
}
