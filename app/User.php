<?php

/**
 * Class User
 */
class User
{
    private $db;
    private $id;
    private $username;
    private $passwordHash;
    private $isActive;

    /**
     * User constructor.
     * @param Database $db
     */
    public function __construct(
        Database $db
    )
    {
        $this->db = $db;
    }

    /**
     * @param $post
     * @return bool
     */
    public function registerUser($post)
    {
        if (empty($post['username'])) {
            return false;
        }
        if (empty($post['password'])) {
            return false;
        }
        if (empty($post['confirm_password'])) {
            return false;
        }

        $username = (string)filter_input(INPUT_POST, 'username');
        $password = $post['password'];
        $passwordConfirm = $post['confirm_password'];

        if ($password === $passwordConfirm) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            return $this->db->registerUser($username, $passwordHash);
        } else {
            return false;
        }
    }

    /**
     * @param $userData
     */
    public function loadUser($userData)
    {
        $this->setId($userData['id']);
        $this->setUsername($userData['username']);
        $this->setPasswordHash($userData['password']);
        $this->setIsActive($userData['is_active']);
    }

    /**
     * @param $username
     * @return $this
     */
    public function getUserByUsername($username)
    {
        $userData = $this->db->getUserByUsername($username);
        // TODO: Validate user data, if problems, return false
        $this->loadUser($userData);

        return $this;
    }

    /**
     * @param $post
     * @return bool
     */
    public function loginUser($post)
    {
        if (empty($post['username'])) {
            return false;
        }
        if (empty($post['password'])) {
            return false;
        }

        $username = $post['username'];
        $password = $post['password'];
        $this->getUserByUsername($username);
        if (!$this->id) {
            return false;
        }
        App::log($this->isActive);
        if ((int)$this->isActive !== 1) {
            App::log($username . ' is inactive user');
            return false;
        }

        if (password_verify($password, $this->passwordHash)) {
            $_SESSION['id'] = $this->id;
            $_SESSION['username'] = $this->username;
            return true;
        } else {
            return false;
        }
    }

    public static function logoutUser()
    {
        session_destroy();
    }

    /**
     * @param $id
     */
    private function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param $username
     */
    private function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @param $passwordHash
     */
    private function setPasswordHash($passwordHash)
    {
        $this->passwordHash = $passwordHash;
    }

    /**
     * @param $isActive
     */
    private function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }
}