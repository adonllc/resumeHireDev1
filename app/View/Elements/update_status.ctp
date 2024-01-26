
<?php
if($status=='1'){
	echo $this->Ajax->link('<span class="icon-block col1"></span>',$action,array( 'title'=>'Deactivate','update' => 'status'.$id,'indicator' =>'loaderIDAct'.$id,'class'=>'custom_link','confirm'=>'Are you sure want to Deactivate ?',"escape"=>false));
}else{
	echo $this->Ajax->link('<span class="icon-ok-1 col2"></span>',$action,array('title'=>'Activate', 'update' => 'status'.$id,'indicator' =>'loaderIDAct'.$id,'class'=>'custom_link','confirm'=>'Are you sure want to Activate ?',"escape"=>false));
}
?>