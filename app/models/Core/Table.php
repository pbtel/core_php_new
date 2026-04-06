<?php

require_once 'app/models/Core/Database.php';

class Model_Core_Table
{
    protected $_tablename = null;
    protected $_primarykey = null;

    protected $data = [];
    protected $adapter = null;

    public function __construct() {}

    public function setTableName($tablename)
    {
        $this->_tablename = $tablename;
        return $this;
    }

    public function getTableName()
    {
        return $this->_tablename;
    }

    public function setPrimaryKey($primarykey)
    {
        $this->_primarykey = $primarykey;
        return $this;
    }

    public function getPrimaryKey()
    {
        return $this->_primarykey;
    }

    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
        return $this;
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
        return $this;
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
        return null;
    }

    // Load a single record by primary key
    public function load($id)
    {
        $table = $this->getTableName();
        $primaryKey = $this->getPrimaryKey();
        $id = $this->getAdapter()->escape($id);
        $sql = "SELECT * FROM `$table` WHERE `$primaryKey` = '$id'";
        $row = $this->getAdapter()->fetchRow($sql);

        if ($row) {
            $this->data = $row;
            return $this;
        }

        return false;
    }

    // Fetch all records
    public function fetchAll()
    {
        $table = $this->getTableName();
        $sql = "SELECT * FROM `$table`";
        $rows = $this->getAdapter()->fetchAll($sql);
        return $rows ? $rows : [];
    }

    // Insert a new record
    public function insert()
    {
        $data = $this->data;

        if (empty($data)) {
            throw new Exception("No data to insert");
        }

        $primaryKey = $this->getPrimaryKey();
        if (array_key_exists($primaryKey, $data) && empty($data[$primaryKey])) {
            unset($data[$primaryKey]);
        }

        $keys = array_keys($data);
        $columns = implode("`, `", $keys);

        $values = [];
        foreach ($data as $value) {
            $values[] = $this->getAdapter()->escape($value);
        }
        $valuesStr = implode("', '", $values);

        $table = $this->getTableName();
        $sql = "INSERT INTO `$table` (`$columns`) VALUES ('$valuesStr')";

        return $this->getAdapter()->insert($sql);
    }

    // Update an existing record
    public function update()
    {
        $data = $this->data;
        $primaryKey = $this->getPrimaryKey();

        if (empty($data[$primaryKey])) {
            throw new Exception("Primary key missing for update");
        }

        if (empty($data)) {
            throw new Exception("No data to update");
        }

        $id = $this->getAdapter()->escape($data[$primaryKey]);
        unset($data[$primaryKey]);

        $setParts = [];
        foreach ($data as $key => $value) {
            $escaped = $this->getAdapter()->escape($value);
            $setParts[] = "`$key` = '$escaped'";
        }
        $setStr = implode(", ", $setParts);

        $table = $this->getTableName();
        $sql = "UPDATE `$table` SET $setStr WHERE `$primaryKey` = '$id'";

        return $this->getAdapter()->update($sql);
    }


    public function save()
    {
        $primaryKey = $this->getPrimaryKey();
        $id = $this->$primaryKey;

        if ($id !== null || $id !== '') {

            $escapedId = $this->getAdapter()->escape($id);
            $table = $this->getTableName();

            $sql = "SELECT * FROM `$table` WHERE `$primaryKey` = '$escapedId'";
            $row = $this->getAdapter()->fetchRow($sql);

            if (!$row) {
                throw new Exception("Invalid ID: Record does not exist.");
            }
            
            $this->data['updated_date'] = date('Y-m-d H:i:s');
            return $this->update();
        }
        $this->data['created_date'] = date('Y-m-d H:i:s');
        return $this->insert();
    }

    // Delete a record by primary key
    public function delete()
    {
        $table = $this->getTableName();
        $primaryKey = $this->getPrimaryKey();

        $id = $this->$primaryKey;

        if ($id === null || $id === '') {
            throw new Exception("Primary key is missing", 1);
        }


        if (!$this->load($id)) {
            throw new Exception("Record is not there for this id", 1);
        }

        $id = $this->getAdapter()->escape($id);

        $sql = "DELETE FROM `$table` WHERE `$primaryKey` = '$id'";
        $result = $this->getAdapter()->delete($sql);

        if (!$result) {
            throw new Exception("Failed to delete record", 1);
        }
        return $result;
    }
}
