<?php

namespace Database\Connectors;

/**
 * Class Connector соедение с базой данных
 * @package Database
 */
abstract class Connector
{
    /** @var string $host Сервер БД */
    protected $host;

    /** @var string $username Имя пользователя БД */
    protected $username;

    /** @var string $password Пароль пользователя БД */
    protected $password;

    /** @var string $database Название БД */
    protected $database;

    public function __construct($host, $user, $password, $database)
    {
        $this->host = $host;
        $this->username = $user;
        $this->password = $password;
        $this->database = $database;
    }

    /**
     * Создание соединения с базой данных
     * @return \PDO
     */
    abstract public function createConnection(): \PDO;
}