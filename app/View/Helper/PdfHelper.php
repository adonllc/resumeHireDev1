<?php
App::import('Vendor','TCPDF',array('file' => 'tcpdf/tcpdf.php'));   //1
class PdfHelper  extends AppHelper                                  //2
{
    var $core;
 
    function __construct() {
        $this->core = new TCPDF();                                  //3
    }
     
}
?>