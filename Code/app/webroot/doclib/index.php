<?php 
require_once('vendor/autoload.php'); 


/* Start to develop here. Best regards https://php-download.com/ */


// Creating the new document...
$phpWord = new \PhpOffice\PhpWord\PhpWord();

/* Note: any element you append to a document must reside inside of a Section. */

// Adding an empty Section to the document...
$section = $phpWord->addSection();
// Adding Text element to the Section having font styled by default...
$section->addText(
    '"Learn from yesterday, live for today, hope for tomorrow. '
        . 'The important thing is not to stop questioning." '
        . '(Albert Einstein)'
);

/*
 * Note: it's possible to customize font style of the Text element you add in three ways:
 * - inline;
 * - using named font style (new font style object will be implicitly created);
 * - using explicitly created font style object.
 */

// Adding Text element with font customized inline...
$section->addText('dinesh dhaker',
    array('name' => 'Tahoma', 'size' => 10)
);

$section->addText("Dhaker \n dhaker",
    array('name' => 'Tahoma', 'size' => 15)
);

//// Adding Text element with font customized using named font style...
//$fontStyleName = 'oneUserDefinedStyle';
//$phpWord->addFontStyle(
//    $fontStyleName,
//    array('name' => 'Tahoma', 'size' => 10, 'color' => '1B2232', 'bold' => true)
//);
//$section->addText(
//    '"The greatest accomplishment is not in never falling, '
//        . 'but in rising again after you fall." '
//        . '(Vince Lombardi)',
//    $fontStyleName
//);


//$section->addImage(
//    'image.png',
//    array(
//        'width'         => 100,
//        'height'        => 100,
//        'marginTop'     => -1,
//        'marginLeft'    => -1,
//        'wrappingStyle' => 'behind'
//    )
//);



$table = $section->addTable();
for ($r = 1; $r <= 8; $r++) {
    $table->addRow();
    for ($c = 1; $c <= 5; $c++) {
        $table->addCell(1750)->addText("Row {$r}, Cell {$c}");
    }
}

$section->addText('Table with colspan and rowspan', $header);

$fancyTableStyle = array('borderSize' => 3, 'borderColor' => '999999', 'width'=>'90%');
$spanTableStyleName = 'Colspan Rowspan';
$phpWord->addTableStyle($spanTableStyleName, $fancyTableStyle);
$table = $section->addTable($spanTableStyleName);
//$cellVCentered = array('bgColor' => 'FFFF00');
$cellVCentered = array('bgColor' => 'd7e4be');
$cellVCenteredRow = array('height'=>'5000');
$table->addRow();
$cellHCentered = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT);
$table->addCell(8750, $cellVCentered)->addText('PITTA',  array( 'size' => 15), $cellHCentered);
$table->addRow();
$table->addCell(8750, array())->addText('Huneysuckle', null, $cellHCentered);
$table->addRow();
$table->addCell(8750, array())->addText('Lavender', null, $cellHCentered);
$table->addRow();
$table->addCell(8750, array())->addText('Sandalwood', null, $cellHCentered);
$table->addRow();
$table->addCell(8750, array())->addText('Dill', null, $cellHCentered);


////$section->addPageBreak();
$section->addText('Table with colspan and rowspan', $header);

$fancyTableStyle = array('borderSize' => 6, 'borderColor' => '999999', 'width'=>'90%');


$spanTableStyleName = 'Colspan Rowspan';
$phpWord->addTableStyle($spanTableStyleName, $fancyTableStyle);
$table = $section->addTable($spanTableStyleName);
//$cellVCentered = array('bgColor' => 'FFFF00');
$cellVCentered = array('valign' => 'bottom', 'exactHeight'=>'200');
$cellVCenteredRow = array('height'=>'5000');
$table->addRow();
$cellHCentered = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER);
$table->addCell(2002, $cellVCentered)->addText('C', null, $cellHCentered);
$table->addCell(2500, $cellVCenteredRow)->addImage('image.png', array('width'=>'100','height'=>75,'marginTop'=> 10));
$table->addCell(2500, $cellVCenteredRow)->addImage('image.png', array('width'=>'100','height'=>75,'marginTop'=> 10));

$table->addRow();
$table->addCell(200, $cellVCentered)->addText('Adding Text element with font customized using named font style', null, $cellHCentered);
$table->addCell(2000, $cellVCentered)->addText('D', null, $cellHCentered);
$table->addCell(2000, $cellVCentered)->addText('dinesh', null, $cellHCentered);
$table->addCell(2000, $cellVCentered)->addText('Adding Text element with font customized using named font style', null, $cellHCentered);
$table->addCell(null, $cellRowContinue);

$table->addRow();

$table->addCell(200, $cellVCentered)->addText('C', null, $cellHCentered);
$table->addCell(2000, $cellVCentered)->addText('D', null, $cellHCentered);
$table->addCell(2000, $cellVCentered)->addText('dinesh', null, $cellHCentered);
$table->addCell(2000, $cellVCentered)->addText('dhaker', null, $cellHCentered);
$table->addCell(null, $cellRowContinue);

$table->addRow();
$cellHCentered = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER);
$table->addCell(2002, $cellVCentered)->addText('C', null, $cellHCentered);
$table->addCell(2500, $cellVCenteredRow)->addImage('image.png', array('width'=>'100','height'=>75,'marginTop'=> 10));
$table->addCell(2500, $cellVCenteredRow)->addImage('image.png', array('width'=>'100','height'=>75,'marginTop'=> 10));

$table->addRow();
$cellHCentered = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER);
$table->addCell(2002, $cellVCentered)->addText('C', null, $cellHCentered);
$table->addCell(2500, $cellVCenteredRow)->addImage('image.png', array('width'=>'100','height'=>75,'marginTop'=> 10));
$table->addCell(2500, $cellVCenteredRow)->addImage('image.png', array('width'=>'100','height'=>75,'marginTop'=> 10));
// Adding Text element with font customized using explicitly created font style object...
$fontStyle = new \PhpOffice\PhpWord\Style\Font();
$fontStyle->setBold(true);
$fontStyle->setName('Tahoma');
$fontStyle->setSize(13);
$myTextElement = $section->addText('"Believe you can and you\'re halfway there." (Theodor Roosevelt)');
$myTextElement->setFontStyle($fontStyle);

// Saving the document as OOXML file...
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('helloWorld.docx');
exit;

// Saving the document as ODF file...
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');
$objWriter->save('helloWorld.odt');

// Saving the document as HTML file...
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
$objWriter->save('helloWorld.html');