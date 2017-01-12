<?php

class Mantenimiento {

    public function validacion($string, $config, $field) {
        $data = explode('|', $config);
        $message = '';
        foreach ($data as $items) {
            $value = trim($items);
            $new_value = '';
            if (strpos($value, 'minlenght') !== FALSE) {
                $pre_value = str_replace('minlenght', '', $value);
                $pre_value = str_replace('[', '', $pre_value);
                $pre_value = str_replace(']', '', $pre_value);
                $new_value = (int) $pre_value;
            }
            if (strpos($value, 'maxlenght') !== FALSE) {
                $pre_value = str_replace('maxlenght', '', $value);
                $pre_value = str_replace('[', '', $pre_value);
                $pre_value = str_replace(']', '', $pre_value);
                $new_value = (int) $pre_value;
            }
            if (strpos($value, 'size') !== FALSE) {
                $pre_value = str_replace('size', '', $value);
                $pre_value = str_replace('[', '', $pre_value);
                $pre_value = str_replace(']', '', $pre_value);
                $new_value = (int) $pre_value;
            }
            if (strpos($value, 'matched') !== FALSE) {
                $pre_value = str_replace('matched', '', $value);
                $pre_value = str_replace('[', '', $pre_value);
                $pre_value = str_replace(']', '', $pre_value);
                $data = explode('%', $pre_value);
                $data_field = (string) $data[0];
                $data_value = (string) $data[1];
            }
            if (($value == 'trim') && ($string != trim($string))) {
                $message .= "<li style='margin-left: 12px;''>No se permiten espacios en los extremos. Campo: " . $field . "</li>";
            } elseif (($value == 'required') && ($string == '')) {
                $message .= "<li style='list-style: none;'>Es necesario llenar este campo: <b>" . $field . "</b></li>";
            } elseif (($value == 'alpha') && ($string != '') && (!preg_match("/^([[:alpha:]])*$/", $string))) {
                $message .= "<li style='margin-left: 12px;'>Se permiten unicamente carácteres alfabéticos. Campo: " . $field . "</li>";
            } elseif (($value == 'alphanumeric') && ($string != '') && (!preg_match("/^([[:alnum:]])*$/", $string))) {
                $message .= "<li style='margin-left: 12px;'>Se permiten unicamente carácteres alfanuméricos. Campo: " . $field . "</li>";
            } elseif (($value == 'numeric') && ($string != '') && (!preg_match("/^([[:digit:]])*$/", $string))) {
                $message .= "<li style='margin-left: 12px;'>Se permiten unicamente carácteres numéricios. Campo: " . $field . "</li>";
            } elseif (($value == 'alphaspace') && ($string != '') && (!ctype_alpha(str_replace(' ', '', $string)))) {
                $message .= "<li style='margin-left: 12px;list-style: none;'>Se permiten únicamente carácteres alfabéticos y espacios. Campo: <b>" . $field . "</b></li>";
            } elseif (($value == 'decimal') && ($string != '') && (!preg_match("/^[0-9]+(\.[0-9]+)?$/", $string))) {
                $message .= "<li style='margin-left: 12px;'>Se permiten únicamente números enteros y decimales. Campo: " . $field . "</li>";
            } elseif (($value == 'date') && ($string != '') && (!preg_match('/^(\d\d\-\d\d\-\d\d\d\d){1,1}$/', $string))) {
                $message .= "<li style='margin-left: 12px;'>Se permiten únicamente fechas con formato dd-mm-yyyy. Campo: " . $field . "</li>";
            } elseif (($value == 'datetime') && ($string != '') && (!preg_match('/^(\d\d\-\d\d\-\d\d\d\d)+(\d\d\:\d\d){1,1}$/', $string))) {
                $message .= "<li style='margin-left: 12px;'>Se permiten únicamente fechas con formato dd-mm-yyyy. Campo: " . $field . "</li>";
            } elseif (($value == 'alphaspecial') && ($string != '') && (!preg_match('/^[a-zñÑáéíóúÁÉÍÓÚ\d_\s():,&-\/]+$/i', $string))) {
                $message .= "<li style='margin-left: 12px;'>Se permiten únicamente carácteres alfabéticos especiales. Campo: <b>" . $field . "</b></li>";
            } elseif (($value == 'url') && ($string != '') && (!preg_match('/^[http:\/\/|www.|https:\/\/]/i', $string))) {
                $message .= "<li style='margin-left: 12px;'>Se permiten únicamente direcciones url. Campo: " . $field . "</li>";
            } elseif ((strpos($value, 'minlenght') !== FALSE) && ($string != '') && (strlen($string) < $new_value)) {
                $message .= "<li style='margin-left: 12px;'>El texto ingresado es menor a " . $new_value . " carácteres. Campo: " . $field . "</li>";
            } elseif ((strpos($value, 'maxlenght') !== FALSE) && ($string != '') && (strlen($string) > $new_value)) {
                $message .= "<li style='margin-left: 12px;list-style: none;'>El texto ingresado es mayor a <b>" . $new_value . "</b> carácteres. Campo: <b>" . $field . "</b></li>";
            } elseif (($value == 'email') && ($string != '') && (!preg_match('/^[^0-9][.a-zA-Z0-9_-]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*[.][a-zA-Z]{2,4}$/', $string))) {
                $message .= "<li style='list-style: none;'>Se permiten únicamente direcciones de correo. Campo: <b>" . $field;
            } elseif ((strpos($value, 'size') !== FALSE) && ($string != '') && (strlen($string) != $new_value)) {
                $message .= "<li style='margin-left: 12px;'>El texto ingresado debe tener " . $new_value . " carácteres. Campo: ". $field ."</b>";
            } elseif ((strpos($value, 'matched') !== FALSE) && ($string != '' || $data_value != '') && ($string != $data_value)) {
                $message .= "<li style='margin-left: 12px;'>El campo "
                        . "<span class='label text-white bg-red text-uppercase'>" . $field . "</span> no se relaciona con el campo "
                        . "<span class='label text-white bg-red text-uppercase'>" . $data_field . "</span>.</li>";
            }
        }
        if ($message != '') {
            return $message;
        } else {
            return '';
        }
    }

    public function aleatorio($length = 40, $lowercase = TRUE, $uppercase = FALSE, $number = TRUE, $specialchar = FALSE) {
        $source = '';
        if ($lowercase === TRUE) {
            $source .= 'abcdefghijklmnopqrstuvwxyz';
        }
        if ($uppercase === TRUE) {
            $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        if ($number === TRUE) {
            $source .= '1234567890';
        }
        if ($specialchar === TRUE) {
            $source .= '|@#~$%()=^*+[]{}-_';
        }
        if ($length > 0) {
            $rstr = "";
            $source = str_split($source, 1);
            for ($i = 1; $i <= $length; $i++) {
                mt_srand((double) microtime() * 1000000);
                $num = mt_rand(1, count($source));
                $rstr .= $source[$num - 1];
            }
        }
        return $rstr;
    }

}
