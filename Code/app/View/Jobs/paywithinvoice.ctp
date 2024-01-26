<?php

$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','title'));
//pr($data); die;
 


// set document information
$this->Pdf->core->SetCreator(PDF_CREATOR);
$this->Pdf->core->SetAuthor('Job Site');
$this->Pdf->core->SetTitle('Ownership');

// remove default header/footer
$this->Pdf->core->setPrintHeader(false);
$this->Pdf->core->setPrintFooter(false);


$this->Pdf->core->addPage('', 'USLETTER');
$this->Pdf->core->setFont('dejavusans', '', 12);


//pr($userInfo);exit;

$companyName = $userInfo['User']['company_name'];
$name = ucwords($userInfo['User']['first_name'].' '.$userInfo['User']['last_name']);
$position = $userInfo['User']['position'];
$userAddress = $userInfo['User']['address'];
$userPostalCode = $userInfo['User']['postal_code'];
$userState = $userInfo['State']['state_name'];
$userCity = $userInfo['City']['city_name'];

$transactionDate = date('M d, Y');
$invoiceNumber = $invoiceInumber;

//$abn = $adminInfo['Admin']['abn'];
$abn ='';
$accountNumber = $adminInfo['Admin']['ac_nu'];
$bsb = $adminInfo['Admin']['bsb'];
$bank = $adminInfo['Admin']['bank'];
$accountName = $adminInfo['Admin']['account_name'];
$paymentTerms = $adminInfo['Admin']['payment_terms'];


$adminCompany = $adminInfo['Admin']['company_name'];
$adminAddress = $adminInfo['Admin']['invoice_address'];
$adminSuburb = $adminInfo['Admin']['invoice_suburb'];
$adminState = $adminInfo['Admin']['invoice_state'];
$adminPostCode = $adminInfo['Admin']['invoice_postcode'];

$jobType = ucfirst($_SESSION['data']['type']);

$amount = number_format($_SESSION['amount'],2);
if (isset($_SESSION['dis_amount']) && $_SESSION['dis_amount'] != '') {
    $amount = $_SESSION['amount'] - $_SESSION['dis_amount'];
}
$gstAmount = number_format($amount*10/100,2);
$totalAmount = number_format($amount + $gstAmount,2);

$duedate = date('M d, Y',strtotime($transactionDate)+$paymentTerms*3600*24);

$jobTitle = $_SESSION['data']['title'];

$logopath = DISPLAY_FULL_WEBSITE_LOGO_PATH.$adminInfo['Admin']['logo'];

$html = '<table border="0" style="width:550px;">
    <tbody><tr style="width:100%;">
            <td style="width:100%;">
                <table>
                    <tbody>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="text-align: right"><img height="60" src="'.$logopath.'" alt="'.$site_title.'"></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                       
                        <tr>
                            <td cellpadding="0">
                                <table>
                                    <tr>
                                        <td style="font-size:15px; font-weight: bold" >TAX INVOICE</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px;">'.$companyName.'</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px;">'.$name.'</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px;">'.$position.'</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px;">'.$userAddress.'</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px;">'.$userCity.', '.$userState.'</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px;">'.$userPostalCode.'</td>
                                    </tr>
                                </table> 
                            </td>
                            <td cellpadding="0">
                                <table>
                                    <tr>
                                        <td style="font-size:15px;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px; font-weight: bold">Invoice Date</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px;">'.$transactionDate.'</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px; font-weight: bold">Invoice Number</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px;">'.$invoiceNumber.'</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px; font-weight: bold">ABN</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px;">'.$abn.'</td>
                                    </tr>
                                </table> 
                            </td>
                            <td cellpadding="0">
                                <table>
                                    <tr>
                                        <td style="font-size:15px; " >&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px;">'.$adminCompany.'</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px;">Attentions: Accounts Receivable</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px;">'.$adminAddress.'</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px;">'.$adminSuburb.', '.$adminState.'</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px;">'.$adminPostCode.'</td>
                                    </tr>
                                </table> 
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        
                        <tr>
                            <td cellpadding="0" colspan="4">
                                <table style="width:85%;" cellspacing="0" >
                                    <tr>
                                        <td style="width:40%; border-bottom:1px #ddd solid; font-size:10px; font-weight: bold">Description</td>
                                        <td style="border-bottom:1px #ddd solid; text-align: center; font-size:10px; font-weight: bold">Quantity</td>
                                        <td style="border-bottom:1px #ddd solid; text-align: center; font-size:10px; font-weight: bold">Unit Price</td>
                                        <td style="border-bottom:1px #ddd solid; text-align: center; font-size:10px; font-weight: bold">Amount </td>
                                    </tr> 
                                    <tr>
                                        <td style="height:10px;">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="width:40%; font-size:10px;  border-bottom:1px #ddd solid;">'.$site_title.'- '.$jobType.' Job Posting <br> '.$jobTitle.'</td>
                                        <td style="font-size:10px; margin-top: 10px;  border-bottom:1px #ddd solid; text-align: center;">1</td>
                                        <td style="font-size:10px; margin-top: 10px;   border-bottom:1px #ddd solid; text-align: center;">'.CURRENCY.$amount.'</td>
                                        <td style="font-size:10px; margin-top: 10px;   border-bottom:1px #ddd solid; text-align: center;">'.CURRENCY.$amount.'</td>
                                    </tr> 
                                    <tr  >
                                        <td style="height:10px;">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="width:40%; font-size:10px;"></td>
                                        <td style="font-size:10px; margin-top: 10px;  text-align: center;"></td>
                                        <td style="font-size:10px; margin-top: 10px;   border-bottom:1px #ddd solid; text-align: center;">Subtotal<br>Total GST 10%<br><b>Total </b></td>
                                        <td style="font-size:10px; margin-top: 10px;   border-bottom:1px #ddd solid; text-align: center;">'.CURRENCY.$amount.' <br>'.CURRENCY.$gstAmount.'<br><b>'.CURRENCY.$totalAmount.'</b></td>
                                    </tr> 
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        
                        <tr>
                            <td cellpadding="0" colspan="4">
                                <table style="90%" >
                                    <tr>
                                        <td style="font-size:10px; font-weight: bold" >Payment Terms: '.$paymentTerms.' Days</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px; font-weight: bold" >Due Date: '.$duedate.'</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px;">Please pay by direct credit</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px;">Bank: '.$bank.' </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px;">Account Name: '.$accountName.' </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px;">BSB: '.$bsb.'</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px;">Account: '.$accountNumber.'</td>
                                    </tr>
                                </table> 
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td cellpadding="0" colspan="4">
                              <table style="90%" >
                                    <tr>
                                      <td><div style="border-top:0px dashed #000;"></div></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        
                        <tr>
                            <td cellpadding="0" colspan="4">
                                <table style="90%" >
                                    <tr>
                                        <td style="width:40%">
                                            <table style="width:90%" >
                                                <tr>
                                                    <td style="font-size:15px; font-weight: bold">PAYMENT ADVICE</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:9px;">
                                                        To: &nbsp;'.$adminCompany.' <br>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Attentions: Accounts Receivable <br>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  '.$adminAddress.' <br>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  '.$adminSuburb.', '.$adminState.' <br>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  '.$adminPostCode.' <br>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td style="width:20%">
                                            <table style="width:90%" >
                                                <tr>
                                                    <td style="font-size:10px; font-weight: bold"> Customer <br></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:10px; border-bottom:1px #ddd solid; font-weight: bold"> Invoice Number</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:10px; font-weight: bold">  <br> <br> Amount Due <br></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:10px; font-weight: bold"> Amount Enclosed <br></td>
                                                </tr>
                                                
                                            </table>
                                        </td>
                                        <td style="width:35%">
                                            <table style="width:90%" >
                                                <tr>
                                                    <td style="font-size:10px;"> '.$name.' <br></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:10px; border-bottom:1px #ddd solid;"> '.$invoiceNumber.'</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:10px;">  <br> <br> '.CURRENCY.$totalAmount.' <br></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:10px; border-bottom:1px #ddd solid;"> </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:8px;"> <br><br> Enter the amount you are paying above </td>
                                                </tr>
                                                
                                            </table>
                                        </td>
                                        
                                    </tr>
                                </table>
                            </td>
                        </tr>
                      

                       
                    </tbody></table>
            </td>
        </tr>
    </tbody></table>';

// set default header data
$this->Pdf->core->SetHeaderData();

// output the HTML content
$this->Pdf->core->writeHTML($html, true, false, true, false, '');
$ffname = str_replace(' ', '_', $invoiceInumber);

$this->Pdf->core->Output(UPLOAD_FULL_INVOICE_IMAGE_PATH.$ffname.'.pdf', 'F');
header("Location:" . HTTP_PATH . "/jobs/saveJobData/");
exit;
//$this->Pdf->core->Output('example_001.pdf', 'I');exit;
?>