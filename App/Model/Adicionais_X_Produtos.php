<?php

namespace App\Model;

class Adicionais_X_Produtos {

    static public function getAdicionaisProduto($prd_codigo) {
        try {
            $sql = "select  
                    a.adc_codigo,
                    a.adc_descricao,
                    a.adc_img
                    from adicionaisxprodutos x 
                    inner join adicionais a 
                    on a.adc_codigo = x.adc_codigo
                    where x.prd_codigo = ?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $prd_codigo);
            $statement_sql->execute();
            return $statement_sql->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getAdicionais_x_Produtos_id($id) {
        try {
            $sql = "select * from adicionaisxprodutos where id = ?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $id);
            $statement_sql->execute();
            return $statement_sql->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getVerificaAdicionalCadastrado($adc_codigo, $prd_codigo) {
        try {
            $sql = "select * from adicionaisxprodutos where adc_codigo = ? and prd_codigo = ?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $adc_codigo);
            $statement_sql->bindValue(2, $prd_codigo);
            $statement_sql->execute();
            return $statement_sql->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getAdicionais_x_Produtos() {
        try {
            $sql = "select
                    x.id,
                    p.prd_descricao,
                    a.adc_codigo,
                    a.adc_descricao,
                    a.adc_img
                    from adicionaisxprodutos x 
                    inner join produtos p
                    on p.prd_codigo = x.prd_codigo
                    inner join adicionais a 
                    on a.adc_codigo = x.adc_codigo order by p.prd_descricao";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->execute();
            return $statement_sql->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function salvar(\stdClass $std) {
        try {
            $sql = "insert into adicionaisxprodutos (ID_CPF_EMPRESA,adc_codigo, prd_codigo) values (?,?,?)";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $std->ID_CPF_EMPRESA);
            $statement_sql->bindValue(2, $std->adc_codigo);
            $statement_sql->bindValue(3, $std->prd_codigo);
            $statement_sql->execute();
            return Conexao::getConnection()->lastInsertId();
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function editar(\stdClass $std) {
        try {
            $sql = "update adicionaisxprodutos set ID_CPF_EMPRESA=?, adc_codigo=?, prd_codigo=? where id = ?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $std->ID_CPF_EMPRESA);
            $statement_sql->bindValue(2, $std->adc_codigo);
            $statement_sql->bindValue(3, $std->prd_codigo);
            $statement_sql->bindValue(6, $std->id);
            return $statement_sql->execute();
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function delete($id) {
        try {
            $sql = "delete from adicionaisxprodutos where id=?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $id);
            return $statement_sql->execute();
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

}
