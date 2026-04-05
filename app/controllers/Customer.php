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
            $model->load($id);
        }

        $this->renderTemplate('customer/edit.phtml', [
            'data' => $model
        ]);
    }

    public function saveAction()
    {
        $model = new Model_Customer();

        foreach ($_POST['customer'] as $key => $value) {
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
