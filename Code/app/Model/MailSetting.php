<?php
class MailSetting extends AppModel{
	//var $name	= 'Admin';

 
       function isRecordUniqueTitle($title = null) {
    
            $resultTitle = $this->find('count', array('conditions' => "MailSetting.mail_type = '" . $title . "'"));
    
            if ($resultTitle) {
                return false;
            } else {
                return true;
            }
        } 

	

}
?>