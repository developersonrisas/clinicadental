<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_modulo extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('modulo');
        parent::setAlias('m');
        parent::setTabla_id('idmodulo');
    }

    public function get_query() {
        $this->CI->db->select("m.idmodulo, m.nombre_modulo, m.fecha_modif, m.idusuario, m.estado");
        $this->CI->db->from($this->tabla . " m");
    }

}
