<?php
class Database {
    /**
     * @var PDO
     */
    public $connection;

    /**
     * Database constructor.
     */
    public function __construct()
    {
        $this->connection = new PDO('sqlite:../database/main.sqlite3');
        $this->connection->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @return mixed
     */
    public function listLinks()
    {
        $sql = 'SELECT url, title, description FROM links ORDER BY id DESC';

        return $this
            ->connection
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param $title
     * @param $url
     * @param $description
     */
    public function save($title, $url, $description)
    {
        $sql = 'INSERT INTO links (title, url, description) VALUES (:title, :url, :description)';
        $q = $this->connection->prepare($sql);
        $q->execute(array(
            ':title' => $title,
            ':url' => $url,
            ':description' => $description
        ));
    }


    /**
     * @param $username
     * @return mixed
     */
    public function getUserByUsername($username)
    {
        $sql = 'SELECT id, name, username, password FROM user WHERE username = ? LIMIT 1';
        $statement = $this->connection->prepare($sql);
        $statement->execute(array($username));

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param $username
     * @param $passwordHash
     * @param string $name
     * @return bool
     */
    public function registerUser($username, $passwordHash, $name = 'unknown')
    {
        $sql = 'INSERT INTO user (username, password, name) VALUES (:username, :password, :name)';
        try {
            $q = $this->connection->prepare($sql);
            $q->execute(array(':username' => $username, ':password' => $passwordHash, ':name' => $name));
        } catch (Exception $e) {
            App::log($e->getMessage());

            return false;
        }

        return true;

    }
}