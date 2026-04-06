<?php
require_once 'app/models/Core/Table.php';

class Model_Category extends Model_Core_Table
{
    protected $_tablename = 'category';
    protected $_primarykey = 'category_id';
}
