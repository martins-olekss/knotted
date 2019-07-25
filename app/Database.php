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
}