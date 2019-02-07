<?php
	class Helper_model extends CI_Model {
		function __construct()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		function get_bentuk_usaha(){
			$this->db->select("*");
			$query = $this->db->get('tb_bentuk_usaha');
			//echo $this->db->last_query();
			return $query->result();
		}
		
		function get_cat_usaha(){
			$this->db->distinct();
			$this->db->select("KATEGORI");
			$query = $this->db->get('tb_jenis_usaha');
			//echo $this->db->last_query();
			return $query->result();
		}
		
		function get_jenis_usaha($cat,$term){
			$this->db->distinct();
			$this->db->select("KODE_SID,SEKTOR_EKONOMI");
			$this->db->from("tb_jenis_usaha");
			$this->db->where('KATEGORI',$cat);
			$this->db->like('SEKTOR_EKONOMI',$term);
			$query = $this->db->get();
			// echo $this->db->last_query();
			return $query->result();
		}
		
		function get_prov() {
			$this->db->distinct();
			$this->db->select('provinsi');
			$this->db->from('mst_wilayah');
			$this->db->order_by('provinsi','asc');
			$query = $this->db->get();
			// echo $this->db->last_query();
			return $query->result();
		}
		
		function get_branch($term) {
			$this->db->distinct();
			$this->db->select('branch,brdesc');
			$this->db->from('DWH_BRANCH');
			if(!empty($term)) {
			$this->db->like('brname',$term);
			}
			$this->db->where_in('brunit',array('B','S'));
			$this->db->where("brdesc not like 'KANWIL%'");
			$this->db->where("brdesc not like 'KANINS%'");
			$this->db->where("brdesc not like '%VENDOR%'");
			$this->db->where("brdesc not like '%CRO%'");
			$this->db->order_by('branch','asc');
			$this->db->limit('10');
			$query = $this->db->get();
			// echo "<pre>".$this->db->last_query()."</pre>";
			return $query->result();
		}
		
		function get_unit($term) {
			$this->db->distinct();
			$this->db->select('branch,brdesc');
			$this->db->from('DWH_BRANCH');
			if(!empty($term)) {
			$this->db->like('brname',$term);
			}
			$this->db->where_in('brunit',array('U'));
			$this->db->where("brdesc not like 'KANWIL%'");
			$this->db->where("brdesc not like 'KANINS%'");
			$this->db->where("brdesc not like '%VENDOR%'");
			$this->db->where("brdesc not like '%CRO%'");
			$this->db->order_by('branch','asc');
			$this->db->limit('10');
			$query = $this->db->get();
			// echo "<pre>".$this->db->last_query()."</pre>";
			return $query->result();
		}
		
		function get_mst_wilayah($term)
		{
			$this->db->select('provinsi,kabupaten,kecamatan,kelurahan,kodepos');
			$this->db->from('mst_wilayah');
			$this->db->like("provinsi",$term);
			$this->db->or_like("kabupaten",$term);
			$this->db->or_like("kecamatan",$term);
			$this->db->or_like("kelurahan",$term);
			$this->db->or_like("kodepos",$term);
			$this->db->order_by('provinsi','asc');
			$this->db->order_by('kabupaten','asc');
			$this->db->order_by('kecamatan','asc');
			$this->db->order_by('kelurahan','asc');
			$this->db->order_by('kodepos','asc');
			$this->db->limit(10);
			$query = $this->db->get();
			return $query->result();
		}
		
		function get_all_wilayah($term='',$prov='',$kab='',$kec='') {
			$this->db->distinct();
			$col = "provinsi";
			if(!empty($prov) && empty($kab) && empty($kec)) {
				$col = "kabupaten";
			}
			if(!empty($kab) && !empty($prov) && empty($kec)) {
				$col = "kecamatan";
			}
			if(!empty($kab) && !empty($prov) && !empty($kec)) {
				$col = "kelurahan";
			}
			$this->db->select($col);
			$this->db->from('mst_wilayah');
			if(!empty($term)) {
			$this->db->where($col." like '%".$term."%'");
			}
			if(!empty($prov) && empty($kab) && empty($kec)) {
			$this->db->where("provinsi",$prov);
			}
			if(!empty($kab) && !empty($prov) && empty($kec)) {
			$this->db->where("provinsi",$prov);
			$this->db->where("kabupaten",$kab);
			}
			if(!empty($kab) && !empty($prov) && !empty($kec)) {
			$this->db->where("provinsi",$prov);
			$this->db->where("kabupaten",$kab);
			$this->db->where("kecamatan",$kec);
			}
			$this->db->order_by('provinsi','asc');
			$this->db->order_by('kabupaten','asc');
			$this->db->order_by('kecamatan','asc');
			$this->db->order_by('kelurahan','asc');
			$query = $this->db->get();
			// echo $this->db->last_query();
			return $query->result();
		}
		
		function get_exp_kredit($jenis)
		{
			$this->db->select('expired,expired_pimpinan,desc,id_jenis_pinjaman,jenis_pinjaman');
			$this->db->from('jenis_pinjaman');
			$this->db->where('jenis_pinjaman',$jenis);
			$this->db->order_by('id_jenis_pinjaman','asc');
			$query = $this->db->get();
			// echo $this->db->last_query();
			return $query->row();
		}
		
		function get_last_package_question(){
			$this->db->select("*");
			$this->db->where('nama_counter', 'counter_soal');
			$query = $this->db->get('counter_pq');
			//echo $this->db->last_query();
			return $query->row();
		}
		
		function update_package_question($id, $counter, $tanggal){
			$data = array(
						   'tanggal' => $tanggal,
						   'counter' => $counter
						);

			$this->db->where('id_counter', $id);
			$query = $this->db->update('counter_pq', $data);
			//echo $this->db->last_query();
			return $query;
		}
		
		function get_kode_uker($kostl){
			$this->db->select("*");
			$this->db->where('kostl', $kostl);
			$query = $this->db->get('mst_mapping_uker');
			//echo $this->db->last_query();
			return $query->row()->branch;
		}
		
		function cek_kode_boa($kode_uker){
			$this->db->where('kode_uker', $kode_uker);
			$this->db->from('pengelola_anggaran');
			return $this->db->count_all_results();
		}
		
		function get_last_question($prefix){
			$this->db->select("*");
			$this->db->where('prefix', $prefix);
			$query = $this->db->get('counter_qc');
			//echo $this->db->last_query();
			return $query->row();
		}
		
		function cek_question($prefix){
			$this->db->where('prefix', $prefix);
			$this->db->from('counter_qc');
			return $this->db->get()->num_rows();
		}
		
		function get_last_question_new($prefix){
			$this->db->select("*");
			$this->db->where('prefix', $prefix);
			$query = $this->db->get('counter_qcn');
			//echo $this->db->last_query();
			return $query->row();
		}
		
		function cek_question_new($prefix){
			$this->db->where('prefix', $prefix);
			$this->db->from('counter_qcn');
			return $this->db->get()->num_rows();
		}
		
		function update_question($id, $postfix){
			$data = array(
						   'postfix' => $postfix
						);

			$this->db->where('id', $id);
			$query = $this->db->update('counter_qc', $data);
			//echo $this->db->last_query();
			return $query;
		}
		
		function update_question_new($id, $postfix){
			$data = array(
						   'postfix' => $postfix
						);

			$this->db->where('id', $id);
			$query = $this->db->update('counter_qcn', $data);
			//echo $this->db->last_query();
			return $query;
		}
		
		function insert_question($prefix, $postfix){
			$data = array(
						   'prefix' => $prefix,
						   'postfix' => $postfix
						);
			$this->db->insert('counter_qc',$data);
			return $this->db->affected_rows();
		}
		
		function insert_question_new($prefix, $postfix){
			$data = array(
						   'prefix' => $prefix,
						   'postfix' => $postfix
						);
			$this->db->insert('counter_qcn',$data);
			return $this->db->affected_rows();
		}
		
		function get_text_materi($kode_materi){
			$this->db->select("nama_materi");
			$this->db->where('kode_materi', $kode_materi);
			$query = $this->db->get('materi');
			return $query->row()->nama_materi;
		}
		
		function get_text_submateri($kode_submateri){
			$this->db->select("nama_submateri");
			$this->db->where('kode_submateri', $kode_submateri);
			$query = $this->db->get('sub_materi');
			return $query->row()->nama_submateri;
		}
		
		function get_text_tik($kode_tik){
			$this->db->select("nama_tik");
			$this->db->where('kode_tik', $kode_tik);
			$query = $this->db->get('tik');
			return $query->row()->nama_tik;
		}
		
		function get_last_assesment($prefix){
			$this->db->select("*");
			$this->db->where('prefix', $prefix);
			$query = $this->db->get('counter_ass');
			//echo $this->db->last_query();
			return $query->row();
		}
		
		function cek_assesment($prefix){
			$this->db->where('prefix', $prefix);
			$this->db->from('counter_ass');
			return $this->db->get()->num_rows();
		}
		
		function update_assesment($id, $data){
			$this->db->where('id', $id);
			$query = $this->db->update('counter_ass', $data);
			return $query;
		}
		
		function insert_assesment($data){
			$this->db->insert('counter_ass',$data);
			return $this->db->affected_rows();
		}
		
		function inquiry_refno_penyetoran($code)
		{
			$this->db->where('code', $code);
			$this->db->from('mst_setor');
			return $this->db->get()->num_rows();
		}
		
		function insert_refno_penyetoran($code,$id)
		{
			$dataUpd = array(
				'code'	=>	$code
			);
			$this->db->where('id_setor', $id);
			$this->db->update('mst_setor',$dataUpd);
			return $this->db->affected_rows();
		}

		function inquiry_refno_penarikan($code)
		{
			$this->db->where('code', $code);
			$this->db->from('mst_tarik');
			return $this->db->get()->num_rows();
		}
		
		function insert_refno_penarikan($code,$id)
		{
			$dataUpd = array(
				'code'	=>	$code
			);
			$this->db->where('id_tarik', $id);
			$this->db->update('mst_tarik',$dataUpd);
			return $this->db->affected_rows();
		}
		
		function inquiry_refno($code)
		{
			$this->db->where('id_pay', $code);
			$this->db->from('trx_panen');
			return $this->db->get()->num_rows();
		}
	}
?>