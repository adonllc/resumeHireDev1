<?php


$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','title'));
//pr($data); die;

App::import('Vendor', 'PHPExcel/PHPExcel');
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator($site_title)
        ->setLastModifiedBy($site_title)
        ->setTitle("Office 2007 XLSX Document")
        ->setSubject("Office 2007 XLSX Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("User Lists");

// Add some data
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Full Name')
        ->setCellValue('B1', 'Email Address')
        ->setCellValue('C1', 'Username')
        ->setCellValue('D1', 'IP Address')
        ->setCellValue('E1', 'Status')
        ->setCellValue('F1', 'Joining date');

$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getAlignment()->setWrapText(true)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('fff49ace');
$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);


$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);


$i = 1;
//foreach ($data as $record) {
//    $row = $objPHPExcel->getActiveSheet()->getHighestRow() + 1;
    $row =  1;
    $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
    $objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, $record['User']['first_name'] . ' ' . $record['User']['last_name']);
    $objPHPExcel->getActiveSheet()->getStyle('A' . $row)->getAlignment()->setWrapText(true)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

    $objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, $record['User']['email_address']);
    $objPHPExcel->getActiveSheet()->getStyle('B' . $row)->getAlignment()->setWrapText(true)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

    $objPHPExcel->getActiveSheet()->SetCellValue('C' . $row, $record['User']['username']);
    $objPHPExcel->getActiveSheet()->getStyle('C' . $row)->getAlignment()->setWrapText(true)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    
    $objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, $record['User']['ip_address']?$record['User']['ip_address']:'N/A');
    $objPHPExcel->getActiveSheet()->getStyle('D' . $row)->getAlignment()->setWrapText(true)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    
    if ($record['User']['status'] == '1') {
        $status = "Active";
    } else {
        $status = "Inactive";
    }
    
    $objPHPExcel->getActiveSheet()->SetCellValue('E' . $row, $status);
    $objPHPExcel->getActiveSheet()->getStyle('E' . $row)->getAlignment()->setWrapText(true)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

    if ($record['User']['created'] != '') {
        $dob = date('d-F-Y', strtotime($record['User']['created']));
    } else {
        $dob = "N/A";
    }
    $objPHPExcel->getActiveSheet()->SetCellValue('F' . $row, $dob);
    $objPHPExcel->getActiveSheet()->getStyle('F' . $row)->getAlignment()->setWrapText(true)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

//}
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('users');

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="User_list' . date('Ymdhis') . '.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed

header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
