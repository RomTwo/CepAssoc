<?php

namespace App\Services;

use \Symfony\Component\Security\Core\User\UserProviderInterface;
use \Symfony\Component\Security\Core\User\UserInterface;
use \App\Entity\Account;

class UserProvider implements UserProviderInterface
{

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     *
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
        $user = new Account();
        $user->setEmail($username);

        return $user;
    }

    /**
     * Refreshes the user.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     *
     * @throws \Symfony\Component\Security\Core\Exception\UnsupportedUserException  if the user is not supported
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException if the user is not found
     */
    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class == 'App\Entity\Account';
    }
}