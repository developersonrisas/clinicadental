<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_rol extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('rol');
        parent::setAlias('r');
        parent::setTabla_id('idrol');
    }

    public function get_query() {
        $this->CI->db->select("r.idrol, r.nombre_rol, r.url_rol, r.icono, r.fecha_modif, r.idparent, r.orden_rol, r.idusuario, r.idmodulo, r.estado");
        $this->CI->db->from($this->tabla . " r");
    }

}
