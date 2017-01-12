<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_grupo_rol extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('grupo_rol');
        parent::setAlias('gr');
        parent::setTabla_id('idgruporol');
    }

    public function get_query() {
        $this->CI->db->select("gr.idgruporol, gr.idgrupo, gr.idrol, gr.estado");
        $this->CI->db->from($this->tabla . " gr");
    }

}
