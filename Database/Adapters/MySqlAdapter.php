<?php

namespace Database\Adapters;


class MySqlAdapter
{
    protected $columns = [];

    public $engine = 'InnoDB';

    protected $table;

    protected $limit = null;

    protected $where = '';

    protected $selects = null;

    protected $order_by = null;

    protected $skip = null;

    const TYPES = [
        'primary' => 'int(11) not null auto_increment primary key',
        'text' => 'varchar(125)',
        'integer' => 'int(11)',
        'boolean' => 'tinyint',
        'date' => 'date'
    ];

    protected function wrapColumns(array $columns)
    {
        return '`' . implode('`, `', $columns).'`';
    }

    protected function wrapValues(array $values)
    {
        return '"' . implode('", "', $values).'"';
    }

    public function setCreatingColumns($columns)
    {
        foreach ($columns as $name => $type) {
            $this->columns[$name] = $name . ' ' . static::TYPES[$type];
        }

        return $this->columns;
    }

    public function createTable($table, array $columns): string
    {
        $this->setCreatingColumns($columns);

        return sprintf("create table `%s` (%s) engine=%s",
            $table,
            implode(', ', $this->columns),
            $this->engine
        );
    }

    public function bulkInsert($table, $columns, $values)
    {
        $columns = $this->wrapColumns($columns);
        foreach ($values as &$value){
            $value = $this->wrapValues($value);
        }
        $values = '('.implode('),(',$values).')';

        return "insert into $table ($columns) values $values";
    }

    public function hasTable($database, $table)
    {
        return "select * from information_schema.tables where table_schema = '$database' and table_name = '$table'";
    }

    public function where(array $wheres)
    {
        $first = true;
        foreach ($wheres as $column => $value) {
            if($first){
                $this->where .= "where `$column` = '$value'";
            }
        }

        return $this;
    }

    public function random($item = null)
    {
        return "RAND($item)";
    }

    public function orderBy($order_by, $direction = 'ASC')
    {
        $this->order_by = "order by $order_by $direction";
        return $this;
    }

    public function limit(int $limit)
    {
        $this->limit = "limit $limit";
        return $this;
    }

    public function select(array $columns)
    {
        $this->selects = '`' . implode("`, `", $columns) . '`';
        return $this;
    }

    public function skip(int $skip)
    {
        $this->skip = $skip;
        $this->limit = "limit $this->skip,$this->limit";
        return $this;
    }

    public function setTable(string $table)
    {
        $this->table = "`$table`";
        return $this;
    }

    /**
     * Возращяет Selec запрос
     * @return string
     */
    public function compileSelect()
    {
        if($this->selects == null) {
            $this->selects = '*';
        }

        $query =  "select $this->selects from $this->table $this->where $this->order_by $this->limit";

        return $query;
    }

    public function compileUpdate($data)
    {
        $values = [];
        foreach ($data as $column => $value){
            $values []= "`$column` = '$value'";
        }
        $values = implode(', ', $values);

        return "update $this->table set $values $this->where";
    }
}