<?php
class MY_Loader extends CI_Loader {
    public function Page($content, $vars = array(),$return = FALSE) {
		$data['main_content'] = $this->view($content,$vars, TRUE);
		$content_view  = $this->view('theme/new_template',$data);
        if ($return)
        {
            return $content_view;
        }
	}
}
?>