<?php

namespace App\Model;

class BairrosDao {

    static public function getBairro($bai_codigo) {
        try {
            $sql = "select * from bairros where bai_codigo = ?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $bai_codigo);
            $statement_sql->execute();
            return $statement_sql->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getBairros() {
        try {
            $sql = "select * from bairros";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->execute();
            return $statement_sql->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function salvar(\stdClass $std) {
        try {
            $sql = "insert into bairros (ID_CPF_EMPRESA,bai_nome, bai_taxa) values (?,?,?)";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $std->ID_CPF_EMPRESA);
            $statement_sql->bindValue(2, $std->bai_nome);
            $statement_sql->bindValue(3, $std->bai_taxa);
            $statement_sql->execute();
            return Conexao::getConnection()->lastInsertId();
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function editar(\stdClass $std) {

        try {
            $sql = "update bairros set ID_CPF_EMPRESA=?,bai_nome=?, bai_taxa=? where bai_codigo = ?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $std->ID_CPF_EMPRESA);
            $statement_sql->bindValue(2, $std->bai_nome);
            $statement_sql->bindValue(3, $std->bai_taxa);
            $statement_sql->bindValue(4, $std->bai_codigo);
            return $statement_sql->execute();
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function delete($bai_codigo) {
        try {
            $sql = "delete from bairros where bai_codigo=?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $bai_codigo);
            return $statement_sql->execute();
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

}
