<?php
require_once 'app/controllers/Core/Base.php';
require_once 'app/models/Productmedia.php';

class Controller_Productmedia extends Controller_Core_Base
{
    public function listAction()
    {
        $model = new Model_Productmedia();
        $data = $model->fetchAll();

        $this->renderTemplate('productmedia/list.phtml', [
            'data' => $data
        ]);
    }

    public function editAction()
    {
        $model = new Model_Productmedia();
        $id = $this->getRequest()->get('id');

        if ($id) {
            $result = $model->load($id);

            if (!$result) {
                throw new Exception("Record not found.");
            }
        }

        $this->renderTemplate('productmedia/edit.phtml', [
            'data' => $model
        ]);
    }

    public function saveAction()
    {
        $data = $_POST['productmedia'] ?? [];

        if (empty($data)) {
            throw new Exception("No data received.");
        }

        $model = new Model_Productmedia();

        // Filter out empty primary key to prevent issues
        if (isset($data['product_media_id']) && empty($data['product_media_id'])) {
            unset($data['product_media_id']);
        }

        foreach ($data as $key => $value) {
            $model->$key = $value;
        }

        $model->save();

        $this->redirect('list', 'productmedia');
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->get('id');

        if (!$id) {
            throw new Exception("ID is required.");
        }

        $model = new Model_Productmedia();

        $result = $model->load($id);

        if (!$result) {
            throw new Exception("Record not found.");
        }

        $model->delete();

        $this->redirect('list', 'productmedia');
    }
}
