<?php
class Category extends AppModel {
    public $name = 'Category';
    var $belongsTo = array(
        'Parent' => array(
            'className' => 'Category',
            'foreignKey' => 'parent_id'
        )
    );
    public function isRecordUniqueCategory($category_name = null, $parent_id = '0') {
        $resultUser = $this->find('count', array('conditions' => "Category.name = '" . addslashes($category_name) . "' AND Category.parent_id = '" . $parent_id . "'"));
        if ($resultUser) {
            return false;
        } else {
            return true;
        }
    }

    public function isRecordUniqueCategories($category_name = null, $category_id = null) {
        if (empty($category_id)) {
            $resultCategory = $this->find('count', array('conditions' => array(
                    'Category.name' => trim($category_name)
            )));
        } else {
            $resultCategory = $this->find('count', array('conditions' => array(
                    'Category.name' => trim($category_name),
                    'Category.id !=' => $category_id
            )));
        }
        if ($resultCategory) {
            return false;
        } else {
            return true;
        }
    }

    public function isRecordUniqueSubCategories($name = null, $patentId = null) {
        $resultstate = $this->find('count', array('conditions' => array('Category.name' => trim($name), 'Category.parent_id' => trim($patentId))));
        if ($resultstate) {
            return false;
        } else {
            return true;
        }
    }

    public function isRecordUniqueSubCategories1($category_name = null, $parent_id = null, $category_id = null) {
        $conditions = array();
        $conditions[] = array('Category.name' => trim($category_name));

        if (!empty($category_id)) {
            $conditions[] = array('Category.id != ' => $category_id);
        }

        if (!empty($parent_id)) {
            $conditions[] = array('Category.parent_id' => $parent_id);
        }

        $resultCategory = $this->find('count', array('conditions' => $conditions));
        if ($resultCategory) {
            return false;
        } else {
            return true;
        }
    }

    public function beforeDelete($cascade = true) {
        //Deleting Sub categories in this Category
        $subcategory_id_list = $this->find('list', array(
            'conditions' => array(
                'Category.parent_id' => $this->id
            ), 'fields' => array('Category.id', 'Category.id')));
        if (!empty($subcategory_id_list)) {
            foreach ($subcategory_id_list as $category_id) {
                $this->delete($category_id); // Deleting Categories one by one, associated Sub-Categories will be deleted
            }
        }
        return true;
    }

    public function getCategoryList() {
        $categories = $this->find('list', array('conditions' => array('Category.status' => 1, 'Category.parent_id' => 0), 'fields' => array('Category.id', 'Category.name'), 'order' => 'Category.name ASC'));
        return $categories;
    }

    public function getSubCategoryList($categoryId) {
        $categories = $this->find('list', array('conditions' => array('Category.status' => 1, 'Category.parent_id' => $categoryId), 'fields' => array('Category.id', 'Category.name'), 'order' => 'Category.name ASC'));
        return $categories;
    }

    public function getSubCatNames($category_ids = null) {

        $busTypes = explode(',', $category_ids);

        $buType = $this->find('list', array('conditions' => array('Category.id' => $busTypes), 'fields' => array('Category.id', 'Category.name')));
        if (count($buType) > 1) {
            $alast = array_pop($buType);
            $buType = implode(', ', $buType) . ' and ' . $alast;
        } else {
            $buType = implode(', ', $buType);
        }
        return $buType;
    }

    //find all details of categories
    public function getALLCategoryDetail() {
        $catDetail = $this->find('all', array('conditions' => array('Category.status' => 1, 'Category.parent_id' => 0), 'order' => 'Category.name ASC'));
        return $catDetail;
    }
    
    function getSubCategory($id) {
        return $suballcategoryList = $this->find('all', array('conditions'=>array('Category.parent_id'=>$id,'Category.status'=>'1')));
    }
    function getSearchSubCatAll($id) {
        return $categoryList = $this->find('all', array('conditions'=>array('Category.parent_id'=>$id,'Category.status'=>'1')));
    }

}

?>