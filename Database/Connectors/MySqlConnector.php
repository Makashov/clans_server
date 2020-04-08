<?php

namespace Database\Connectors;

class MySqlConnector extends Connector
{
    /**
     * @return \PDO
     */
    public function createConnection(): \PDO
    {
        try{
            return new \PDO("mysql:dbname=$this->database;host=$this->host", $this->username, $this->password);
        }
        catch (\Exception $e){
            exit('Не удаётся подключиться к БД ');
        }
    }
}