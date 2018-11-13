<?php

namespace App\Util;

class Util {

    //60 = 1min
    //150 2,5min
    //300=5min
    //600=10min
    //1200=20 min 
    //2400=40 min
    //3600=1 hora
    //2700=45min  45min*60seg=2700 
    static public function sessao_existe() {
        session_start();
        $ultimo_acesso = $_SESSION["ultimoAcesso"];
        date_default_timezone_set('America/Sao_Paulo');
        $agora = date("Y-n-j H:i:s");
        $TEMPO_TRANSCORRIDO = (strtotime($agora) - strtotime($ultimo_acesso));
        if ($TEMPO_TRANSCORRIDO >= $_SESSION["TEMPO_DA_SESSAO"]) {
            unset($_SESSION["TEMPO_DA_SESSAO"]);
            unset($_SESSION["ultimoAcesso"]);
            unset($_SESSION['usu_codigo']);
            unset($_SESSION['usu_email']);
            unset($_SESSION['usu_nivel']);
            session_destroy();
            return false;
        } else {
            return true;
            $_SESSION["ultimoAcesso"] = $agora;
        }
    }

    static public function remover_letra_acentuada($string, $maiuscula = false) {
        $tr = strtr(
                $string, array(
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A',
            'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E',
            'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ð' => 'D', 'Ñ' => 'N',
            'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O',
            'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Ŕ' => 'R',
            'Þ' => 's', 'ß' => 'B', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
            'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
            'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
            'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y',
            'þ' => 'b', 'ÿ' => 'y', 'ŕ' => 'r'
                )
        );

        if ($maiuscula) {
            return strtoupper($tr);
        } else {
            return strtolower($tr);
        }
    }

    static public function format_DD_MM_AAAA_HHMMSS($dt_USA) {
        return date('d/m/Y H:i:s', strtotime($dt_USA));
    }

    static public function format_AAAA_MM_DD($dt_BR) {
        list($dia, $mes, $ano) = explode('/', $dt_BR);
        $data_americana = $ano . "-" . $mes . "-" . $dia;
        return $data_americana;
    }

    static public function retorna_diferenca_2_datas($data_inicial, $data_final) {
        $time_inicial = strtotime(self::format_AAAA_MM_DD($data_inicial));
        $time_final = strtotime(self::format_AAAA_MM_DD($data_final));
        $diferenca = $time_final - $time_inicial;
        $dias = (int) floor($diferenca / (60 * 60 * 24));
        return $dias;
    }

}

?>
