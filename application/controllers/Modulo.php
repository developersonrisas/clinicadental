<?php

@session_cache_limiter('private, must-revalidate');
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", FALSE);
@header("Pragma: no-cache");

class Modulo extends CI_Controller {

    private $_process;
    private $_result;

    public function __construct() {
        parent::__construct();
        /*
        * DECLARACION DE LIBRERIAS, HELPERS Y MODELOS
        */
        $library = array();
        $helper = array();
        $model = array('m_modulo');
        $this->load->library($library);
        $this->load->helper($helper);
        $this->load->model($model);
        /*
        * CONFIGURACION PERSONAL
        */

    }

    public function index() {
        $data = array();

        /*
        * PARAMETROS RECIBIDOS PARA PAGINACION Y ORDENAMIENTO
        */
        $page = $this->input->get('page');
        $pageSize = $this->input->get('pageSize');
        $firstRow = $this->input->get('firstRow');
        $orderName = $this->input->get('orderName');
        $orderName = $orderName == null ? 'nombre_modulo' : $orderName;
        $orderSort = $this->input->get('orderSort');
        $orderSort = $orderSort == null ? 'asc' : $orderSort;

        $lista = $this->m_modulo->mostrar_activos($pageSize, $firstRow, ['m.'.$orderName => $orderSort]); 
        if (!empty($lista)) {
            $data['total'] = count( $this->m_modulo->mostrar_activos());
            $i = 1;
            foreach ($lista AS $items) {
                    $data['lista'][] = array(
                        'idmodulo' => $items['idmodulo'],
                        'numero' => $i,
                        'nombre_modulo' => $items['nombre_modulo'],
                    );
                $i++;
            }
        }

        /*
        * ENVIO DE RESPUESTA
        */
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));

    }



    public function accion($modulo = null){
        $data = array();
        $allInputs = json_decode(trim($this->input->raw_input_stream), TRUE);
        
        /*
        * VALIDACIONES
        */
        $error = '';
        $error .= $this->mantenimiento->validacion($allInputs['modulo']['nombre_modulo'], 'required|alphaspecial', 'Nombre');

        if($error != ''){
            $data['mensaje'] = $error;
            $data['type'] = '0';
        
        }else{

            if(!isset($allInputs['modulo']['idmodulo'])){ // se hace un insert
                /*
                * VALIDACION EN LA BASE DE DATOS
                */
                
                if($this->m_modulo->existe_campo('nombre_modulo',$allInputs['modulo']['nombre_modulo'])){
                    $data['mensaje'] = 'Este módulo ya se encuentra registrado...';
                    $data['type'] = '0';
                    
                }else{
                    $allInputs['modulo']['fecha_modif'] = date('Y-m-d H:i:s');
                    $allInputs['modulo']['idusuario'] = '1';
                    
                    $this->db->trans_start();
                    $result = $this->m_modulo->insertar($allInputs['modulo']);
                    if($result){
                        $data['mensaje'] = 'El módulo se registro correctamente';
                        $data['type'] = '1';
                    }
                    $this->db->trans_complete();

                }

            }else{ // se edita el registro
                /*
                * VALIDACION EN LA BASE DE DATOS
                */
                
                if($this->m_modulo->existe_campo('nombre_modulo',$allInputs['modulo']['nombre_modulo'],$allInputs['modulo']['idmodulo'])){
                    $data['mensaje'] = 'Este módulo ya se encuentra registrado...';
                    $data['type'] = '0';
                    
                }else{
                    $allInputs['modulo']['fecha_modif'] = date('Y-m-d H:i:s');
                    $allInputs['modulo']['idusuario'] = '1';
                    
                    $this->db->trans_start();
                    $result = $this->m_modulo->actualizar($allInputs['modulo'], 'idmodulo', $allInputs['modulo']['idmodulo']);
                    if($result){
                        $data['mensaje'] = 'El módulo se actualizo correctamente';
                        $data['type'] = '1';
                    }
                    $this->db->trans_complete();

                }   
            }
            
        }

        /*
        * ENVIO DE RESPUESTA
        */
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));

    }

    public function eliminar($id = '') {
        if ($id == '') {
            $data['mensaje'] = 'Error Interno';
            $data['type'] = '0';
        
        }else{
            /*
            * SE VALIDA QUE EXITA EL MÓDULO
            */    
            $where = array('m.idmodulo' => $id, 'm.estado' => '1');
            $result = $this->m_modulo->mostrar($where);
            if(!empty($result)){
                /*
                * SE HACE ELIMINACION LOGICA
                */
                $this->m_modulo->ocultar($id);
                $data['mensaje'] = 'Se eliminó el módulo seleccionado';
                $data['type'] = '1';
            
            }else{
                $data['mensaje'] = 'No se puede eliminar el módulo';
                $data['type'] = '0';
            }
        }

        /*
        * ENVIO DE RESPUESTA
        */
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));

    }


}

