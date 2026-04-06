<?php
require_once 'app/controllers/Core/Base.php';
require_once 'app/models/Product.php';

class Controller_Product extends Controller_Core_Base
{
    public function indexAction()
    {
        echo __CLASS__ . '::' . __FUNCTION__;
    }

    public function listAction()
    {
        $model = new Model_Product();
        $data = $model->fetchAll();

        $this->renderTemplate('product/list.phtml', [
            'data' => $data
        ]);    }

    public function saveAction()
    {
        if (!isset($_POST['product'])) {
            throw new Exception("No product data received.");
        }

        $model = new Model_Product();
        $postData = $_POST['product'];

        // Filter out empty primary key to prevent issues
        if (isset($postData['product_id']) && empty($postData['product_id'])) {
            unset($postData['product_id']);
        }

        foreach ($postData as $key => $value) {
            $model->$key = $value;
        }

        $model->save();

        $this->redirect('list', 'product');
    }
    
    public function editAction()
    {
        $model = new Model_Product();
        $id = $this->getRequest()->get('id');

        if ($id) {
            $result = $model->load($id);
            if (!$result) {
                throw new Exception("Product not found.");
            }
        }

        $this->renderTemplate('product/edit.phtml', [
            'data' => $model
        ]);
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->get('id');

        if ($id) {
            $model = new Model_Product();
            $model->load($id);
            $model->delete();
        }

        $this->redirect('list', 'product');    }

    
}