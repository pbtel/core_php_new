<?php
require_once 'app/controllers/Core/Base.php';
require_once 'app/models/Customergroup.php';

class Controller_Customergroup extends Controller_Core_Base
{
    public function listAction()
    {
        $model = new Model_Customergroup();
        $data = $model->fetchAll();

        $this->renderTemplate('customergroup/list.phtml', [
            'data' => $data
        ]);
    }

    public function editAction()
    {
        $model = new Model_Customergroup();
        $id = $this->getRequest()->get('id');

        if ($id) {
            $result = $model->load($id);
            if (!$result) {
                throw new Exception("Customer group not found.");
            }
        }

        $this->renderTemplate('customergroup/edit.phtml', [
            'data' => $model
        ]);
    }

    public function saveAction()
    {
        if (!isset($_POST['customergroup'])) {
            throw new Exception("No customer group data received.");
        }

        $model = new Model_Customergroup();
        $postData = $_POST['customergroup'];

        if (isset($postData['customer_group_id']) && empty($postData['customer_group_id'])) {
            unset($postData['customer_group_id']);
        }

        foreach ($postData as $key => $value) {
            $model->$key = $value;
        }

        $model->save();

        $this->redirect('list', 'customergroup');
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->get('id');

        if ($id) {
            $model = new Model_Customergroup();
            $model->load($id);
            $model->delete();
        }

        $this->redirect('list', 'customergroup');
    }
}
