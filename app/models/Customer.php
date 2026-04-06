<?php
require_once 'app/models/Core/Table.php';

class Model_Customer extends Model_Core_Table
{
    protected $_tablename = 'customer';
    protected $_primarykey = 'customer_id';
}
