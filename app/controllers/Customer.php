<?php
require_once 'app/controllers/Core/Base.php';
require_once 'app/models/Customer.php';

class Controller_Customer extends Controller_Core_Base
{
    public function listAction()
    {
        $model = new Model_Customer();
        $data = $model->fetchAll();

        $this->renderTemplate('customer/list.phtml', [
            'data' => $data
        ]);
    }

    public function editAction()
    {
        $model = new Model_Customer();
        $id = $this->getRequest()->get('id');

        if ($id) {
            $result = $model->load($id);
            if (!$result) {
                throw new Exception("Customer not found.");
            }
        }

        $this->renderTemplate('customer/edit.phtml', [
            'data' => $model
        ]);
    }

    public function saveAction()
    {
        if (!isset($_POST['customer'])) {
            throw new Exception("No customer data received.");
        }

        $model = new Model_Customer();
        $postData = $_POST['customer'];

        if (isset($postData['customer_id']) && empty($postData['customer_id'])) {
            unset($postData['customer_id']);
        }

        foreach ($postData as $key => $value) {
            $model->$key = $value;
        }

        $model->save();

        $this->redirect('list', 'customer');
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->get('id');

        if ($id) {
            $model = new Model_Customer();
            $model->load($id);
            $model->delete();    
        }

        $this->redirect('list', 'customer');
    }
}
