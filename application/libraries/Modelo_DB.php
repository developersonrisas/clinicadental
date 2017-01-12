<?php

class Modelo_DB {

    var $CI;
    var $tabla;
    var $alias;
    var $tabla_id;
    var $tabla_estado = "estado";
    var $buscar = 0;

    function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->database();
    }

    public function setTabla($tabla) {
        $this->tabla = $tabla;
    }

    public function setAlias($alias) {
        $this->alias = $alias;
    }

    public function setTabla_id($tabla_id) {
        $this->tabla_id = $tabla_id;
    }

    public function setTabla_estado($tabla_estado) {
        $this->tabla_estado = $tabla_estado;
    }

    public function insertar($data, $id = FALSE) {
        $insertar = $this->CI->db->insert($this->tabla, $data);
        if ($insertar) {
            if($id !== FALSE){
                $lastId = $this->CI->db->insert_id();
                return $lastId;
            }else{
                return TRUE;
            }            
        } else {
            return false;
        }
    }


    public function actualizar($data, $where, $valor = FALSE) {
        $this->get_where($where, $valor);
        $update = $this->CI->db->update($this->tabla, $data);
        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    public function ocultar($where, $valor = FALSE) {
        $d_ocultar[$this->tabla_estado] = 0;
        $this->get_where($where, $valor);
        if ($this->CI->db->update($this->tabla, $d_ocultar)) {
            return true;
        } else {
            return false;
        }
    }

    public function permitir($where, $valor = FALSE) {
        $d_ocultar[$this->tabla_estado] = 1;
        $this->get_where($where, $valor);
        if ($this->CI->db->update($this->tabla, $d_ocultar)) {
            return true;
        } else {
            return false;
        }
    }

    public function eliminar($where, $valor = FALSE) {
        $this->get_where($where, $valor);
        $delete = $this->CI->db->delete($this->tabla);
        if ($delete) {
            return true;
        } else {
            return false;
        }
    }

    public function mostrar($where, $valor = FALSE) {
        $this->get_query();
        $this->get_where($where, $valor);
        return $this->CI->db->get()->row_array();
    }

    public function mostrar_todo($limite = FALSE, $offset = FALSE,$order = FALSE) {
        $this->get_query();
        if($order !== FALSE){
            $this->CI->db->order_by($order['0'], $order['1']);
        }else{
            $this->CI->db->order_by($this->tabla_id, "desc");
        }

        if ($limite) {
            return $this->CI->db->limit($limite, $offset)->get()->result_array();
        } else {
            return $this->CI->db->get()->result_array();
        }
    }

    public function mostrar_activos($limite = FALSE, $offset = FALSE,$order = FALSE) {
        $where = $this->alias . '.' . $this->tabla_estado;
        $valor = 1;
        $this->get_query();
        if($order !== FALSE){
            foreach ($order as $key => $value) {
                $this->CI->db->order_by($key, $value);    
            }
            
        }else{
            $this->CI->db->order_by($this->tabla_id, "desc");
        }
        $this->get_where($where, $valor);
        if ($limite) {
            return $this->CI->db->limit($limite, $offset)->get()->result_array();
        } else {
            return $this->CI->db->get()->result_array();
        }
    }

    public function mostrar_cuando($where, $limite = FALSE, $offset = FALSE,$order = FALSE) {
        $this->get_query();
        if($order !== FALSE){
            foreach ($order as $key => $value) {
                $this->CI->db->order_by($key, $value);    
            }
            
        }else{
            $this->CI->db->order_by($this->tabla_id, "desc");
        }
        $this->get_where($where);
        if ($limite) {
            return $this->CI->db->limit($limite, $offset)->get()->result_array();
        } else {
            return $this->CI->db->get()->result_array();
        }
    }

    public function mostrar_inactivos($limite = FALSE, $offset = FALSE) {
        $where = $this->alias . '.' . $this->tabla_estado;
        $valor = 1;
        $this->get_query();
        $this->get_where($where, $valor);
        if ($limite) {
            return $this->CI->db->limit($limite, $offset)->get()->result_array();
        } else {
            return $this->CI->db->get()->result_array();
        }
    }

    public function buscar($like) {
        $this->get_query();
        $this->get_like($like);
        $this->CI->db->where($this->alias . '.' . $this->tabla_estado, '0');
        return $this->CI->db->get()->result_array();
    }


    public function get_where($where, $value = FALSE) {
        if (is_array($where)) {
            foreach ($where as $k => $v) {
                $this->CI->db->where($k, $v);
            }
        } else if ($where !== FALSE) {
            if ($value === FALSE) {
                $value = $where;
                $where = $this->tabla_id;
            }
            $this->CI->db->where($where, $value);
        }
    }

    public function get_like($like) {
        if (is_array($like)) {
            foreach ($like as $k => $v) {
                $this->CI->db->or_like($k, $v);
            }
        }
    }

    public function existe_campo($campo, $value = '', $id = '') {
        if ($id == '') {
            $resultSet = $this->CI->db->select()
                    ->from($this->tabla)
                    ->where($this->tabla.'.'.$campo, $value)
                    ->get()
                    ->result();
        } else {
            $stm = $this->CI->db->select(''.$campo.' AS c_campo')
                    ->from($this->tabla)
                    ->where($this->tabla_id, $id)
                    ->get()
                    ->result();
            $resultSet = $this->CI->db->select()
                    ->from($this->tabla)
                    ->where(''.$campo.' !=', $stm[0]->c_campo)
                    ->where($campo, $value)
                    ->get()
                    ->result();
        }
        if (count($resultSet) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
