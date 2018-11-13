<?php

namespace App\Model;

class AdicionaisDao extends Dao {

    static public function getAdicional($adc_codigo) {
        try {
            $sql = "select * from adicionais where adc_codigo = ?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $adc_codigo);
            $statement_sql->execute();
            return $statement_sql->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getAdicionaisProduto($prd_codigo) {
        try {
            $sql = "select * from adicionais where prd_codigo = ?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $prd_codigo);
            $statement_sql->execute();
            return $statement_sql->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getAdicionais() {
        try {
            $sql = "select * from adicionais";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->execute();
            return $statement_sql->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

}
