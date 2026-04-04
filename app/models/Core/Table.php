<?php

require_once "Database.php";

class Model_Core_Table
{
    protected $_tablename = null;
    protected $_primarykey = null;

    protected $data = [];
    protected $adapter = null;

    public function  __construct() {}

    public function setTableName($tablename)
    {
        $this->_tablename = $tablename;
    }

    public function getTableName()
    {
        return $this->_tablename;
    }

    public function setPrimaryKey($primarykey)
    {
        $this->_primarykey = $primarykey;
    }

    public function getPrimaryKey()
    {
        return $this->_primarykey;
    }
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }
    public function getAdapter()
    {
        if (!$this->adapter) {
            $this->adapter = new Model_Core_Database();
            $this->adapter->connection();
        }
        return $this->adapter;
    }


    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function __get($key)
    {
        return $this->data[$key];
    }


    public function insert()
    {
        $keys = array_keys($this->data);
        $columns = implode(", ", $keys);
        $values = implode("', '", array_values($this->data));

        $sql = "INSERT INTO $this->_tablename ($columns) VALUES ('$values')";

        return $this->getAdapter()->insert($sql);
    }
}
