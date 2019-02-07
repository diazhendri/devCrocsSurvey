<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function to_excel($query, $filename='exceloutput')
{
	ini_set('memory_limit',-1);
	$headers = ''; 
	$data = ''; 
	$CI = & get_instance();
	$CI->load->library('Excel');
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
	$objPHPExcel->setActiveSheetIndex(0);
	$fields = $query->list_fields();
	$col = 0;
	foreach ($fields as $field)
	{
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
		$col++;
	}
	$row = 2;
	foreach($query->result() as $data)
	{
		$col = 0;
		foreach ($fields as $field)
		{
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->$field);
			$col++;
		}
		$row++;
	}
	$objPHPExcel->setActiveSheetIndex(0);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
	header('Cache-Control: max-age=0');
	$objWriter->save('php://output');  		 
}
function to_csv($query, $filename='exceloutput')
{
	ini_set('memory_limit', '-1');
	set_time_limit(0);
	$CI = & get_instance();
	$CI->load->helper('csv');
	$i = 1;		
	$arr_header = array();
	$fields = $query->list_fields();
	foreach ($fields as $field)
	{
		array_push($arr_header,$field);
	}
	$csv_array[] = $arr_header;
	$arr_content = array_push($arr_header,$field);
	foreach($query->result() as $data)
	{
		$arr_content = array();
		foreach ($fields as $field)
		{
			array_push($arr_content,$data->$field);
		}
		$csv_array[] = $arr_content;
	}
	
	echo array_to_csv($csv_array, $filename . ".csv", ";");
}