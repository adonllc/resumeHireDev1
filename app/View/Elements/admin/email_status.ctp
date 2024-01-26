
<?php 
if($status=='1'){ 
	echo $this->Ajax->link('<button class="btn btn-success btn-xs"><i class="fa  fa-mail-forward"></i></button>','javascript:void(0);',array( 'title'=>'Already Sent','class'=>'custom_link',"escape"=>false,'onclick'=>"alert('Email Already Sent');"));
}else{ 
	echo $this->Ajax->link('<button class="btn btn-danger btn-xs"><i class="fa  fa-mail-reply"></i></button>',$action,array('title'=>'Send Email', 'update' => 'email_status'.$id,'indicator' =>'loaderIDEmail'.$id,'class'=>'custom_link','confirm'=>'Are you sure want to send email ?',"escape"=>false));
}

?>