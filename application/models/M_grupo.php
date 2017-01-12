<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_grupo extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('grupo');
        parent::setAlias('g');
        parent::setTabla_id('idgrupo');
    }

    public function get_query() {
        $this->CI->db->select("g.idgrupo, g.nombre_grupo, g.descripcion, g.fecha_modif, g.key_grupo, g.idusuario, g.estado");
        $this->CI->db->from($this->tabla . " g");
    }

}
