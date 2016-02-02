<?php
/**
 * Auth.php 27/10/2015
 * ----------------------------------------------
 *
 * @author      Phan Nguyen <phannguyen2020@gmail.com>
 * @copyright   Copyright (c) 2015, framework
 *
 * ----------------------------------------------
 * All Rights Reserved.
 * ----------------------------------------------
 */

namespace Library;

use Models\Logs;
use Models\Token;
use Models\User;
use Phalcon\Mvc\User\Component;

class Auth extends Component
{
    /**
     * Return the current identity
     *
     * @return array
     */
    public function getIdentity()
    {
        return $this->session->get('Auth');
    }

    /**
     * Returns the current id identity
     *
     * @return string
     */
    public function getId()
    {
        return $this->getIdentity()->id;
    }

    /**
     * Returns the current name identity
     *
     * @return string
     */
    public function getName()
    {
        return $this->getIdentity()->name;
    }

    /**
     * Return the current role identity
     *
     * @return string
     */
    public function getRole()
    {
        return $this->getIdentity()->role;
    }

    /**
     * Return the current email identity
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getIdentity()->email;
    }

    /**
     * Return the current avatar identity
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->getIdentity()->avatar;
    }

    /**
     * Authentication for login
     *
     * @param $credentials
     * @throws \Exception
     */
    public function authentication($credentials)
    {
        $user = User::findFirstByEmail($credentials['email']);
        if (!$user) {
            throw new \Exception('Email not exists');
        }

        // Check the password
        if (!$this->security->checkHash($credentials['password'], $user->password)) {
            throw new \Exception('Incorrect password');
        }

        // Check status user
        $this->checkUserStatus($user);

        // Check if the remember me was selected
        if (isset($credentials['remember'])) {
            $this->createRemember($user);
        }

        // create session for user
        $this->session->set('Auth', $user->getAuthData());

        // Handel write log
        $infoLog = [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'user_agent' => $this->request->getUserAgent(),
            'ip_address' => $this->request->getClientAddress()
        ];
        Logs::log('Login ' . $user->role, serialize($infoLog), Logs::INFO);
    }

    /**
     * Checks if the user is banned/inactive
     *
     * @param User $user
     * @throws \Exception
     */
    public function checkUserStatus(User $user)
    {
        if ($user->status == User::STATUS_INACTIVE) {
            throw new \Exception('The user is inactive');
        }

        if ($user->status == User::STATUS_BANNED) {
            throw new \Exception('The user is banned');
        }
    }

    /**
     * Create remember user and token
     * @param User $user
     */
    public function createRemember(User $user)
    {
        $userAgent = $this->request->getUserAgent();
        $token = md5($user->email . $user->password . $userAgent);

        $tokenModel = new Token();
        $tokenModel->userId = $user->id;
        $tokenModel->token = $token;
        $tokenModel->userAgent = $userAgent;
        if ($tokenModel->create()) {
            $expire = time() + 86400 * 8;
            $this->cookies->set('user', $user->id, $expire);
            $this->cookies->set('token', $token, $expire);
        }
    }

    /**
     * Check if the session has a remember me cookie
     * @return boolean
     */
    public function hasRememberMe()
    {
        return $this->cookies->has('user');
    }

    /**
     * Removes the user identity information from session
     */
    public function remove()
    {
        if ($this->cookies->has('user')) {
            $this->cookies->get('user')->delete();
        }
        if ($this->cookies->has('token')) {
            $this->cookies->get('token')->delete();
        }

        $this->session->remove('Auth');
    }

    /**
     * Get the entity related to user in the active identity
     *
     * @return bool
     * @throws \Exception
     */
    public function getUser()
    {
        $identity = $this->session->get('Auth');
        if (isset($identity['id'])) {
            $user = User::getUserById($identity['id']);
            if (!$user) {
                throw new \Exception('The user does not exist');
            }
            return $user;
        }

        return false;
    }

    /**
     * Auth the user
     *
     * @param $id
     * @throws \Exception
     */
    public function authUserById($id)
    {
        $user = User::getUserById($id);
        if (!$user) {
            throw new \Exception('The user does not exist');
        }

        // Check status user
        $this->checkUserStatus($user);

        $this->session->set('Auth', $user->getAuthData());
    }

    /**
     * Login on using the information in the cookies
     *
     * @return Phalcon\Http\Response
     */
    public function loginWithRememberMe()
    {
        $userId = $this->cookies->get('user')->getValue();
        $cookieToken = $this->cookies->get('token')->getValue();

        $user = User::getUserById($userId);
        if ($user) {
            $userAgent = $this->request->getUserAgent();
            $token = md5($user->email . $user->password . $userAgent);

            if ($cookieToken == $token) {
                $remember = Token::findFirst([
                    'userId = ?0 AND token = ?1',
                    'bind' => [
                        $user->id,
                        $token
                    ]
                ]);
                if ($remember) {
                    // Check if the cookie has not expired
                    if ((time() - (86400 * 8)) < $remember->dateCreated) {
                        $this->checkUserStatus($user);
                        $this->session->set('Auth', $user->getAuthData());

                        // Handel write log
                        $infoLog = [
                            'user_id' => $user->id,
                            'email' => $user->email,
                            'role' => $user->role,
                            'user_agent' => $this->request->getUserAgent(),
                            'ip_address' => $this->request->getClientAddress()
                        ];
                        Logs::log('Login with remember me' . $user->role, serialize($infoLog), Logs::INFO);

                        return $user->getAuthData();
                    }
                }
            }
        }
        $this->remove();

        return false;
    }
}
