<?php 
//echo $this->element('view_cv');die;
// set document information
$this->Pdf->core->SetCreator(PDF_CREATOR);
$this->Pdf->core->SetAuthor('Job Site');
$this->Pdf->core->SetTitle('Ownership');

// remove default header/footer
$this->Pdf->core->setPrintHeader(false);
$this->Pdf->core->setPrintFooter(true);

$this->Pdf->core->addPage('', 'A4');
$this->Pdf->core->setFont('dejavusans', '', 11);

// set default header data
$this->Pdf->core->SetHeaderData();

// output the HTML content
$this->Pdf->core->writeHTML($this->element('view_cv'), true, false, true, false, '');
$ffname = $name;
$this->Pdf->core->Output($ffname.'.pdf', 'D');

exit;
?>
