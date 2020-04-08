<?php

namespace Database\Builders;

use Database\Adapters\MySqlAdapter;
use Database\Connectors\MySqlConnector;

class MySqlBuilder
{
    protected $connection;

    /**
     * @var MySqlAdapter класс для генераций SQL запросов
     */
    protected $adapter;

    protected $database;
    protected $table;
    protected $limit = null;
    protected $wheres = [];
    protected $selects = [];
    protected $order_by = '';

    public function __construct()
    {
        $host = CONFIGS['db_host'];
        $user = CONFIGS['db_user'];
        $password = CONFIGS['db_password'];
        $this->database = CONFIGS['db_name'];
        $this->connection = (new MySqlConnector($host, $user, $password, $this->database))->createConnection();

        $this->adapter = new MySqlAdapter();
    }

    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    public function createTable($name, array $columns)
    {
        $query = $this->adapter->createTable($name, $columns);
        $this->connection->exec($query);
    }

    public function isTableExists($table)
    {
        $query = $this->adapter->hasTable($this->database, $table);

        return $this->connection->query($query)->fetch();
    }

    /**
     * Множественная вставка в таблицу
     *
     * @param $columns
     * @param array $data
     * @return int
     */
    public function bulkInsert($columns, array $data)
    {
        $query = $this->adapter->bulkInsert($this->table, $columns, $data);

        return $this->connection->exec($query);
    }

    /**
     * @param $columns
     * @return $this
     */
    public function select($columns)
    {
        $this->selects = $columns;
        $this->adapter->select($columns);

        return $this;
    }

    /**
     * @param array $wheres условия в запроса
     * @return $this
     */
    public function where(array $wheres)
    {
        $this->wheres = $wheres;
        $this->adapter->where($this->wheres);

        return $this;
    }

    public function inRandomOrder()
    {
        $this->order_by = $this->adapter->random();
        $this->adapter->orderBy($this->order_by);

        return $this;
    }

    /**
     * @param int $limit максимальное значения запроса
     * @return $this
     */
    public function limit(int $limit)
    {
        $this->limit = $limit;
        $this->adapter->limit($limit);

        return $this;
    }

    /**
     * Выполнение SQL запроса
     */
    public function first()
    {
        $this->adapter->setTable($this->table);
        $query = $this->adapter->compileSelect();

        return $this->connection->query($query)->fetchObject('Lib\DataRow');
    }

    /**
     * Выполнение SQL запроса
     */
    public function get()
    {
        $this->adapter->setTable($this->table);
        $query = $this->adapter->compileSelect();

        return $this->connection->query($query)->fetchAll(\PDO::FETCH_CLASS, 'Lib\DataRow');
    }

    public function update(array $data)
    {
        $this->adapter->setTable($this->table);
        $query = $this->adapter->compileUpdate($data);

        return $this->connection->exec($query);
    }
}