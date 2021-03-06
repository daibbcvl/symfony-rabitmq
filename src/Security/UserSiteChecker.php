<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserSiteChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        /** @var User $user */
        if (!$user->isEnabled() || !$user->hasRole(User::ROLE_USER)) {
            throw new DisabledException();
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
    }
}
