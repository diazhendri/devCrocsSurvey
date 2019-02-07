<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu {
	var $CI;
	
	function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->model('main_model');
		$this->CI->load->model('config_model');
	}
	public function set_menu()
	{
		$menu = $this->CI->main_model->get_access();
		$arr_menu = array();
		foreach($menu as $row)
		{
			if((isset($_SESSION['all_parent'])?$_SESSION['all_parent']:0) == 1)
			{
				if($row->url != '#')
				$arr_menu[$row->posisi][0][$row->id_menu] = (array)$row;
			}
			else
				$arr_menu[$row->posisi][$row->id_parent][$row->id_menu] = (array)$row;
			// $arr_menu[$row->posisi][$row->id_parent][$row->id_menu] = array('title'=>$row->title,'url'=>$row->url,'atribut'=>$row->atribut,'target'=>$row->target,'posisi'=>$row->posisi,'icon'=>$row->icon);
		}
		// if($this->CI->session->has_userdata('is_login')){
			// if($this->CI->session->userdata('is_login')==TRUE){
				// $arr_menu[1][0][9999999] = array('title'=>$this->CI->session->userdata('nama'),'url'=>'#','atribut'=>'','target'=>'','posisi'=>1,'icon'=>'fa fa-user');
				// // $arr_menu[1][9999999][9999001] = array('title'=>'Profile','url'=>'user/profile','atribut'=>'','target'=>'','posisi'=>1,'icon'=>'fa fa-male');
				// $arr_menu[1][9999999][9999002] = array('title'=>$this->CI->config->item('text_logout'),'url'=>'user/logout','atribut'=>' onClick="return confirm(\'Apakah Anda yakin akan keluar dari aplikasi?\')"','target'=>'','posisi'=>1,'icon'=>'fa fa-power-off');
			// }
		// }
		if(!empty($_SESSION['is_login'])) {
			$arr_menu[2][0][9999999] = array(
				'id_menu'		=> '9999999',
				'title'			=> 'LOGOUT',
				'url'			=> 'home/logout',
				'deskripsi'		=> 'Menu Logout',
				'icon'			=> 'fa fa-sign-out',
				'atribut'		=> '',
				'id_parent'		=> '0',
				'posisi'		=> '2',
				'jenis'			=> '1',
				'target'		=> NULL,
				'auth'			=> '1',
				'status'		=> '1',
				'urutan'		=> '9999'
			);
		} else {
			$arr_menu[2][0][9999999] = array(
				'id_menu'		=> '9999999',
				'title'			=> 'LOGIN',
				'url'			=> 'home/login',
				'deskripsi'		=> 'Menu Login',
				'icon'			=> '',
				'atribut'		=> '',
				'id_parent'		=> '0',
				'posisi'		=> '2',
				'jenis'			=> '1',
				'target'		=> NULL,
				'auth'			=> '0',
				'status'		=> '1',
				'urutan'		=> '9999'
			);
		}
		// $this->CI->session->set_userdata('menu',$arr_menu);
		$_SESSION[$this->CI->config->item('session_prefix')]['menu'] = $arr_menu;
		
		// echo'<pre>';print_r($arr_menu);echo'</pre>';
	}
	public function generate_menu_new($posisi,$parent,$active,$parent_txt='')
    {
		// if(!isset($_SESSION[$this->CI->config->item('session_prefix')]['menu'])){
			$this->set_menu();
		// }
		$menu = $_SESSION[$this->CI->config->item('session_prefix')]['menu'];
		if($posisi == 1) {
			$menu_pos = 2;
		} else {
			$menu_pos = $posisi;
		}
		if(isset($menu[$menu_pos])){
			if(isset($menu[$menu_pos][$parent])){
				if(is_array($menu[$menu_pos][$parent])){
					if($parent=='' || $parent=='0'){
						switch($posisi){
							case 0 : echo '<ul class="nav navbar-nav">';break;
							case 1 : echo '<ul class="nav navbar-nav navbar-right">';break;
							case 2 : echo '<ul class="nav sidebar-nav" id="nav-accordion"><li class="sidebar-brand"><a href="#">Sidebar</a></li>';break;
						}
					}else{
						switch($posisi){
							case 0 : 
							case 1 : echo '<ul class="dropdown-menu">';break;
							case 2 : echo '<ul class="dropdown-menu" role="menu"><li class="dropdown-header">Dropdown '.$parent_txt.'</li>';break;
						}
					}
					foreach($menu[$menu_pos][$parent] as $id=>$val){
						$class_active = $active==$id?'active':''; 
						if(isset($menu[$menu_pos][$id])){
							switch($posisi){
								case 0 : 
								case 1 : echo '<li class="dropdown'.($class_active!=''?' '.$class_active:'').'"><a href="#" class="dropdown-toggle side-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.(isset($val['icon'])?($val['icon']!=''?'<i class="'.$val['icon'].'"></i> ':''):'').(isset($val['title'])?$val['title']:'').' </a>';break;
								case 2 : echo '<li class="dropdown"><a href="javascript:;"  data-toggle="dropdown" '.($class_active!=''?'class="dropdown-toggle  side-dropdown '.$class_active.'"':'class="dropdown-toggle side-dropdown"').'>'.(isset($val['icon'])?($val['icon']!=''?'<i class="'.$val['icon'].'"></i> ':''):'').'<span>'.(isset($val['title'])?$val['title']:'').'</span> <span class="caret"></span></a>';$parent_txt = (isset($val['title'])?$val['title']:'');break;
							}
							$this->generate_menu_new($posisi,$id,$active,$parent_txt);
							echo '</li>';
						}else{
							$jenis = isset($val['jenis'])?$val['jenis']:'';
							$ajax = $jenis==1?'ajax':'';
							$url = isset($val['url'])?(strpos($val['url'],'http://')===FALSE && strpos($val['url'],'https://')===FALSE?site_url($val['url']):$val['url']):'';
							switch($posisi){
								case 0 : 
								case 1 : 
									$class = $ajax;
									$class = trim($class);
									echo '<li'.($class_active!=''?' class="'.$class_active.'"':'').'><a '.($class!=''?'class="'.$class.'"':'').' href="'.$url.'" target="'.(isset($val['target'])?$val['target']:'').'"'.(isset($val['atribut'])?' '.$val['atribut']:'').' title="'.(isset($val['title'])?$val['title']:'').'">'.(isset($val['icon'])?($val['icon']!=''?'<i class="'.$val['icon'].'"></i> ':''):'').(isset($val['title'])?$val['title']:'').'</a></li>';break;
								case 2 : 
									$class = $class_active.' '.$ajax.' burger_close';
									$class = trim($class);
									echo '<li><a '.($class!=''?'class="'.$class.'"':'').' href="'.$url.'" target="'.(isset($val['target'])?$val['target']:'').'"'.(isset($val['atribut'])?' '.$val['atribut']:'').' title="'.(isset($val['title'])?$val['title']:'').'">'.(isset($val['icon'])?($val['icon']!=''?'<i class="'.$val['icon'].'"></i> ':''):'').(isset($val['title'])?$val['title']:'').'</a></li>';break;
							}
						}
					}
					if($parent=='' || $parent=='0') {
						echo '</ul>';
					} else {
						switch($posisi){
							case 0 : 
							case 1 : echo '</ul>';break;
							case 2 : echo '<li class="dropdown-header">Dropdown End</li></ul>';break;
						}
					}
					
				}
			}
		}
    }
	
	public function generate_menu($parent)
    {
		if(!isset($_SESSION[$this->CI->config->item('session_prefix')]['menu'])){
			$menu = $this->CI->main_model->get_menu();
			foreach($menu as $row){
				$_SESSION[$this->CI->config->item('session_prefix')]['menu'][$row->id_parent][$row->id_menu] = array('title'=>$row->title, 'linkaddr'=>$row->link, 'addon'=>$row->addon);
			}
			
		}
		print_r($_SESSION[$this->CI->config->item('session_prefix')]['menu']);
		if(isset($_SESSION[$this->CI->config->item('session_prefix')]['menu'])){
			if(isset($_SESSION[$this->CI->config->item('session_prefix')]['menu'][$parent])){
				if(is_array($_SESSION[$this->CI->config->item('session_prefix')]['menu'][$parent])){
					if($parent=='' || $parent=='0'){
						echo '<ul class="nav navbar-nav">';
					}else{
						echo '<ul class="dropdown-menu">';
					}
					foreach($_SESSION[$this->CI->config->item('session_prefix')]['menu'][$parent] as $id=>$val){
						if(isset($_SESSION[$this->CI->config->item('session_prefix')]['menu'][$id])){
							echo '<li><a href="'.base_url().$val['linkaddr'].'" onclick="return false;" style=""><span class="'.$val['addon'].'"></span> '.$val['title'].'</a>';
							$this->generate_menu($id);
							echo '</li>';
						}else{
							echo '<li><a href="'.base_url().$val['linkaddr'].'" style=""><span class="'.$val['addon'].'"></span> '.$val['title'].' </a></li>';
						}
					}
					echo '</ul>';
				}
			}
		}else{
			echo '<ul class="nav navbar-nav"></ul>';
		}
    }
	
	public function generate_menu_settings($parent, $parent_title='', $level_menu=0)
    {
		if(!isset($_SESSION[$this->CI->config->item('session_prefix')]['c']['menu'])){
			$menu = $this->CI->config_model->get_menu_settings();		
			foreach($menu as $row){
				if($_SESSION[$this->CI->config->item('session_prefix')]['level_id'] == $row->level_id){
					$_SESSION[$this->CI->config->item('session_prefix')]['c']['menu'][$row->id_parent][$row->id_menu] = array('title'=>$row->title, 'linkaddr'=>$row->link);
				}
			}
		}
		if(isset($_SESSION[$this->CI->config->item('session_prefix')]['c']['menu'])){
			if(isset($_SESSION[$this->CI->config->item('session_prefix')]['c']['menu'][$parent])){
				if(is_array($_SESSION[$this->CI->config->item('session_prefix')]['c']['menu'][$parent])){
					if($parent=='' || $parent=='0'){
						echo '<div id="sidebar" class="list-group">';
					}else{
						echo '<div id="'.$parent_title.'" class="list-group subitem collapse">';
					}
					foreach($_SESSION[$this->CI->config->item('session_prefix')]['c']['menu'][$parent] as $id=>$val){
						if(isset($_SESSION[$this->CI->config->item('session_prefix')]['c']['menu'][$id])){
							echo '<a href="#'.$val['linkaddr'].'" class="list-group-item">'.$val['title'].' </a>';
							$this->generate_menu_settings($id,$val['linkaddr']);
						}else{
							echo '<a href="'.base_url().'settings/'.$val['linkaddr'].'" class="list-group-item">'.$val['title'].' </a>';
						}
					}
					echo '</div>';
				}
			}
		}else{
			echo '<div id="sidebar" class="list-group"></div>';
		}
    }
}

/* End of file Menu.php */
