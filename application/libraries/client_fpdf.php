<?php
require_once APPPATH."third_party/fpdf/fpdf.php";
require_once APPPATH."third_party/fpdf/fpdi.php";
class Client_fpdf
{
    function watermark_pdf($filename)
	{
		$CI = & get_instance();
		$pdf = new FPDI();
		$pageCount = $pdf->setSourceFile(FCPATH.'uploads/'.$filename);
		for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
			$templateId = $pdf->importPage($pageNo);
			$size = $pdf->getTemplateSize($templateId);
			if ($size['w'] > $size['h']) {
				$pdf->AddPage('L', array($size['w'], $size['h']));
			} else {
				$pdf->AddPage('P', array($size['w'], $size['h']));
			}
			$pdf->useTemplate($templateId);
			$pdf->SetFont('Helvetica', '', '8');
			$pdf->SetFillColor(128, 128, 128);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetXY(10,-40);
			$pdf->Cell(50,5,'['.$_SESSION[$CI->config->item('session_prefix')]['pernr'].']'.$_SESSION[$CI->config->item('session_prefix')]['nama'], 0, 1, 'L', true);
			$pdf->Cell(50,8,date('d M Y H:i:s'), 0, 1, 'L', true);
		}
		$pdf->Output(strtotime(date('Y-m-d H:i:s')).'_'.$filename, 'D');
	}

	function preread_pdf($filename)
	{
		$CI = & get_instance();
		$pdf = new FPDI();
		$dir = '/var/www/edu/uploads/';
		$pageCount = $pdf->setSourceFile($dir.$filename);
		// $pageCount = $pdf->setSourceFile(FCPATH.'uploads/'.$filename); file source diganti menjadi dari edu
		for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
			$templateId = $pdf->importPage($pageNo);
			$size = $pdf->getTemplateSize($templateId);
			if ($size['w'] > $size['h']) {
				$pdf->AddPage('L', array($size['w'], $size['h']));
			} else {
				$pdf->AddPage('P', array($size['w'], $size['h']));
			}
			$pdf->useTemplate($templateId);
			$pdf->SetFont('Helvetica', '', '8');
			$pdf->SetFillColor(128, 128, 128);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetXY(10,-40);
			$pdf->Cell(50,5,'['.$_SESSION[$CI->config->item('session_prefix')]['pernr'].']'.$_SESSION[$CI->config->item('session_prefix')]['nama'], 0, 1, 'L', true);
			$pdf->Cell(50,8,date('d M Y H:i:s'), 0, 1, 'L', true);

		}
		$newname = strtotime(date('Y-m-d H:i:s')).'.pdf';
		// $dir = '/var/www/edu/uploads/elearning/read/';
		// $pdf->Output($dir.$newname, 'F');
		$pdf->Output(FCPATH.'uploads/elearning/read/'.$newname, 'F');

		//remove old files
		$current_dir = @opendir(FCPATH.'uploads/elearning/read/');
		while ($filename = @readdir($current_dir))
		{
			if ($filename != "." and $filename != ".." and $filename != "index.html")
			{
				$name = str_replace(".pdf", "", $filename);

				if (($name + 300) < strtotime(date('Y-m-d H:i:s')))
				{
					@unlink(FCPATH.'uploads/elearning/read/'.$filename);
				}
			}
		}
		return $newname;
	}
}
?>