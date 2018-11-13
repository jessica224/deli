<?php

namespace App\Model;

class CategoriaDao {
   
     static public function getCategoria($cat_codigo) {
        try {
            $sql = "select * from categorias where cat_codigo = ?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $cat_codigo);
            $statement_sql->execute();
            return $statement_sql->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getCategorias() {
        try {
            $sql = "select * from categorias";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->execute();
            return $statement_sql->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function salvar(\stdClass $std) {
        try {
            $sql = "insert into categorias (ID_CPF_EMPRESA, cat_descricao,cat_imagem) values (?,?,?)";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $std->ID_CPF_EMPRESA);
            $statement_sql->bindValue(2, $std->cat_descricao); 
            $statement_sql->bindValue(3, $std->cat_imagem);             
            $statement_sql->execute();
            return Conexao::getConnection()->lastInsertId();
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function editar(\stdClass $std) {

        try {
            $sql = "update categorias set ID_CPF_EMPRESA=?,cat_descricao=?,cat_imagem=? where cat_codigo = ?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $std->ID_CPF_EMPRESA);
            $statement_sql->bindValue(2, $std->cat_descricao);   
            $statement_sql->bindValue(3, $std->cat_imagem);             
            $statement_sql->bindValue(4, $std->cat_codigo);
            return $statement_sql->execute();
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function delete($cat_codigo) {
        try {
            $sql = "delete from categorias where cat_codigo=?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $cat_codigo);
            return $statement_sql->execute();
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }
    
    
    
}
