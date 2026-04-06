<?php
require_once 'app/models/Core/Table.php';

class Model_Product extends Model_Core_Table
{
    protected $_tablename = 'product';
    protected $_primarykey = 'product_id';
}