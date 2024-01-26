<?php
if($status=='1'){
	echo $this->Ajax->link('<button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>',$action,array( 'update' => 'status'.$id,'indicator'=>'loaderIDAct'.$id,'class'=>'custom_link','confirm'=>'Are you sure want to Deactivate ?',"escape"=>false,'title' => 'Deactivate'));
}else{
	echo $this->Ajax->link('<button class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></button>',$action,array( 'update' => 'status'.$id,'indicator'=>'loaderIDAct'.$id,'class'=>'custom_link','confirm'=>'Are you sure want to Activate ?',"escape"=>false,'title' => 'Activate'));
}
?>