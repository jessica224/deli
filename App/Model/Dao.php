<?php

namespace App\Model;

class Dao {
    /*
      Inseri os dados
      $arrayUser = array('nome' => 'João', 'email' => 'joao@gmail.com', 'senha' => base64_encode('123456'), 'privilegio' => 'A');
      insert($arrayUser);
     */

    static public function insert($data, $tabela) {
        try {
            $sql = "";
            $campos = "";
            $valores = "";
            foreach ($data as $chave => $valor):
                $campos .= $chave . ', ';
                $valores .= '?, ';
            endforeach;
            $campos = (substr($campos, -2) == ', ') ? trim(substr($campos, 0, (strlen($campos) - 2))) : $campos;
            $valores = (substr($valores, -2) == ', ') ? trim(substr($valores, 0, (strlen($valores) - 2))) : $valores;
            $sql .= "INSERT INTO $tabela (" . $campos . ")VALUES(" . $valores . ")";
            $stm = Conexao::getConnection()->prepare($sql);
            $cont = 1;
            foreach ($data as $valor):
                $stm->bindValue($cont, $valor);
                $cont++;
            endforeach;
            $stm->execute();
            return Conexao::getConnection()->lastInsertId();
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }

    /*
      Editar os dados
      $arrayUser = array('nome' => 'João da Silva', 'email' => 'joao@gmail.com.br', 'senha' => base64_encode('654321'), 'privilegio' => 'A');
      $arrayCond = array('id=' => 1);
      update($arrayUser, $arrayCond);
     */

    static public function update($data, $arrayCondicao, $tabela) {
        try {
            $sql = "";
            $valCampos = "";
            $valCondicao = "";
            foreach ($data as $chave => $valor):
                $valCampos .= $chave . '=?, ';
            endforeach;
            foreach ($arrayCondicao as $chave => $valor):
                $valCondicao .= $chave . '? AND ';
            endforeach;
            $valCampos = (substr($valCampos, -2) == ', ') ? trim(substr($valCampos, 0, (strlen($valCampos) - 2))) : $valCampos;
            $valCondicao = (substr($valCondicao, -4) == 'AND ') ? trim(substr($valCondicao, 0, (strlen($valCondicao) - 4))) : $valCondicao;
            $sql .= "UPDATE $tabela SET " . $valCampos . " WHERE " . $valCondicao;

            $stm = Conexao::getConnection()->prepare($sql);
            $cont = 1;
            foreach ($data as $valor):
                $stm->bindValue($cont, $valor);
                $cont++;
            endforeach;
            foreach ($arrayCondicao as $valor):
                $stm->bindValue($cont, $valor);
                $cont++;
            endforeach;
            $retorno = $stm->execute();
            return $retorno;
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }

    /*
      Exclui o registro 
      $arrayCond = array('id=' => 1);
      delete($arrayCond);
     */

    static public function delete($condicao, $tabela) {
        try {

            $sql = "";
            $valCampos = "";
            foreach ($condicao as $chave => $valor):
                $valCampos .= $chave . '? AND ';
            endforeach;
            $valCampos = (substr($valCampos, -4) == 'AND ') ? trim(substr($valCampos, 0, (strlen($valCampos) - 4))) : $valCampos;
            $sql .= "DELETE FROM $tabela WHERE " . $valCampos;
            $stm = Conexao::getConnection()->prepare($sql);
            $cont = 1;
            foreach ($condicao as $valor):
                $stm->bindValue($cont, $valor);
                $cont++;
            endforeach;
            $retorno = $stm->execute();
            return $retorno;
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }

}
