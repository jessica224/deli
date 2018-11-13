<?php

namespace App\Model;

class ProdutoDao {

    static public function getProduto($prd_codigo) {
        try {
            $sql = "select * from produtos where prd_codigo = ?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $prd_codigo);
            $statement_sql->execute();
            return $statement_sql->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getProdutosCategoria($cat_codigo) {
        try {
            $sql = "SELECT * FROM produtos where cat_codigo = ? and prd_ativo='S'";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $cat_codigo);
            $statement_sql->execute();
            return $statement_sql->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getProdutos() {
        try {
            $sql = "select * from produtos";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->execute();
            return $statement_sql->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function salvar(\stdClass $std) {
        try {
            $sql = "insert into produtos (ID_CPF_EMPRESA, cat_codigo, prd_descricao, prd_preco, prd_img, prd_prom, prd_det_1,prd_det_2,prd_ativo) values (?,?,?,?,?,?,?,?,?)";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $std->ID_CPF_EMPRESA);
            $statement_sql->bindValue(2, $std->cat_codigo);
            $statement_sql->bindValue(3, $std->prd_descricao);
            $statement_sql->bindValue(4, $std->prd_preco);
            $statement_sql->bindValue(5, $std->prd_img);
            $statement_sql->bindValue(6, $std->prd_prom);
            $statement_sql->bindValue(7, $std->prd_det_1);
            $statement_sql->bindValue(8, $std->prd_det_2);
            $statement_sql->bindValue(9, $std->prd_ativo);
            $statement_sql->execute();
            return Conexao::getConnection()->lastInsertId();
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function editar(\stdClass $std) {

        try {
            $sql = "update produtos set ID_CPF_EMPRESA=?, cat_codigo=?, prd_descricao=?, prd_preco=?, prd_img=?, prd_prom=?, prd_det_1=?,prd_det_2=?, prd_ativo=? where prd_codigo = ?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $std->ID_CPF_EMPRESA);
            $statement_sql->bindValue(2, $std->cat_codigo);
            $statement_sql->bindValue(3, $std->prd_descricao);
            $statement_sql->bindValue(4, $std->prd_preco);
            $statement_sql->bindValue(5, $std->prd_img);
            $statement_sql->bindValue(6, $std->prd_prom);
            $statement_sql->bindValue(7, $std->prd_det_1);
            $statement_sql->bindValue(8, $std->prd_det_2);
            $statement_sql->bindValue(9, $std->prd_ativo);
            $statement_sql->bindValue(10, $std->prd_codigo);
            return $statement_sql->execute();
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function delete($prd_codigo) {
        try {
            $sql = "delete from produtos where prd_codigo=?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $prd_codigo);
            return $statement_sql->execute();
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

}
