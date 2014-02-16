<?php
class Bans extends CI_Controller {


	private function player($id,$name){
		$this->load->helper('url');
		return anchor_popup("player/detail_popup/$id",$name,array());
	}
	
	private function showbool($val){
		if($val=="yes" || $val==true || $val=="true")
			return "<div id='true'></div>";
		else
			return "<div id='false'></div>";
	}
	
	private function actions($ban){
		$acts="";
		if($ban->banActive==1)
			$acts.=anchor_popup("bans/lift/".$ban->pID,"Levantar",array());
		else
			$acts.="Reactivar";
		
		$acts.=" Detalles";
		return $acts;
	}
	
	public function lift($playerid){
		require_level(ACCLEVEL_ADMIN);
		$this->load->model('ban_model');
		$result=$this->ban_model->lift_ban($playerid);
		
		if($result==true)
			echo "Ban levantado exitosamente";
		else	
			echo "Error levantando ban";
		
	}

	public function index(){
		require_level(ACCLEVEL_MODERATOR);
		$this->load->model('Ban_model');
		$this->load->library('table');
		
		$bans=$this->Ban_model->get_bans();
		
		$tmpl = array ( 'table_open'  => '<table id="gradient-style">' );
		$this->table->set_template($tmpl); 
		
		$this->table->set_heading('Nombre', 'IP', 'Fecha inicio', 'Fecha fin', 'Raz&oacute;n', 'Admin', 'Activo?','Panel?','Acciones');
		foreach($bans as $ban)
		{
			$this->table->add_row($this->player($ban->pID,$ban->pName),$ban->pIP,$ban->banDate,$ban->banEnd,$ban->banReason,$this->player($ban->banIssuerID,$ban->banIssuerName),$this->showbool($ban->banActive),$ban->banPanel,$this->actions($ban));
		}
		
		$data = array( 'table' => $this->table->generate() );

		$this->load->view("bans/list.php",$data);
	}
	
	public function new_ban_popup(){
		require_level(ACCLEVEL_ADMIN);
		$this->load->view("bans/new_form.php");
	}
	
}