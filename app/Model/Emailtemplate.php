<?php

class Emailtemplate extends AppModel {

    var $name = 'Emailtemplate';
    
    public function getSubjectLang() {
        $static_page_title = 'subject';
        $static_page_description = 'template';
        if($_SESSION['Config']['language'] !='en'){
            $static_page_title = 'subject_'.$_SESSION['Config']['language'];
            $static_page_description = 'template_'.$_SESSION['Config']['language'];
        }
        
        $data = array('subject'=>$static_page_title, 'template'=>$static_page_description);
        return $data;
    }
}

?>