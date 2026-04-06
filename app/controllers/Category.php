<?php
require_once 'app/controllers/Core/Base.php';
require_once 'app/models/Category.php';

class Controller_Category extends Controller_Core_Base
{
    public function listAction()
    {
        $model = new Model_Category();
        $data = $model->fetchAll();

        $this->renderTemplate('category/list.phtml', [
            'data' => $data
        ]);
    }

    public function editAction()
    {
        $model = new Model_Category();
        $id = $this->getRequest()->get('id');

        if ($id) {
            $result = $model->load($id);
            if (!$result) {
                throw new Exception("Category not found.");
            }
        }

        $this->renderTemplate('category/edit.phtml', [
            'data' => $model
        ]);
    }

    public function saveAction()
    {
        if (!isset($_POST['category'])) {
            throw new Exception("No category data received.");
        }

        $model = new Model_Category();
        $postData = $_POST['category'];

        // Filter out empty primary key to prevent issues
        if (isset($postData['category_id']) && empty($postData['category_id'])) {
            unset($postData['category_id']);
        }

        foreach ($postData as $key => $value) {
            $model->$key = $value;
        }

        $model->save();

        $this->redirect('list', 'category');
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->get('id');

        if ($id) {
            $model = new Model_Category();
            $model->load($id);
            $model->delete();
        }

        $this->redirect('list', 'category');
    }
}
