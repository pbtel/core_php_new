<?php

class Model_Core_Database
{
    protected $conn = null;

    public function connection()
    {
        if ($this->conn === null) {
            $this->conn = mysqli_connect("localhost", "root", "", "product_curd");

            if (!$this->conn) {
                die("Connection Failed: " . mysqli_connect_error());
            }
        }
        return $this->conn;
    }

    public function escape($value)
    {
        return mysqli_real_escape_string($this->connection(), $value);
    }

    public function insert($query)
    {
        $result = mysqli_query($this->connection(), $query);

        if ($result) {
            return mysqli_insert_id($this->connection());
        }

        return false;
    }

    // UPDATE
    public function update($query)
    {
        return mysqli_query($this->connection(), $query);
    }

    // DELETE
    public function delete($query)
    {
        return mysqli_query($this->connection(), $query);
    }

    public function fetchRow($query)
    {
        $result = mysqli_query($this->connection(), $query);

        if ($result && mysqli_num_rows($result)) {
            return mysqli_fetch_assoc($result);
        }

        return false;
    }

    public function fetchAll($query)
    {
        $result = mysqli_query($this->connection(), $query);

        if (!$result) {
            return false;
        }

        $rows = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }

        return $rows ?: false;
    }

    public function fetchOne($query)
    {
        $row = $this->fetchRow($query);

        if ($row) {
            return array_values($row)[0]; // first column
        }

        return false;
    }

    public function fetchPairs($query)
    {
        $result = mysqli_query($this->connection(), $query);

        if (!$result) {
            return false;
        }

        $pairs = [];

        while ($row = mysqli_fetch_row($result)) {
            $pairs[$row[0]] = $row[1];
        }

        return $pairs ?: false;
    }
}