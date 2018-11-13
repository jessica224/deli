<?php

namespace App\Model;

class UsuarioDao extends Dao {

    public function login($usu_email, $usu_senha) {

        try {
            $sql = "SELECT * from usuarios where usu_email = ? and usu_senha = ? and usu_nivel != 'CLI'";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $usu_email);
            $statement_sql->bindValue(2, $usu_senha);
            $statement_sql->execute();
            return $statement_sql->fetch(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            print "Erro em login " . $e->getMessage();
        }
    }

    static public function getUsuarioBy_ID($ID) {
        try {
            $sql = "select * from usuarios where ID = ?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $ID);
            $statement_sql->execute();
            return $statement_sql->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getUsuarioBy_usu_codigo($usu_codigo) {
        try {
            $sql = "select * from usuarios where usu_codigo = ?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $usu_codigo);
            $statement_sql->execute();
            return $statement_sql->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getUsuario($ID_CPF_EMPRESA) {
        try {
            $sql = "select * from usuarios where ID_CPF_EMPRESA = ?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $ID_CPF_EMPRESA);
            $statement_sql->execute();
            return $statement_sql->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getAllClientes() {
        try {
            $sql = "SELECT 
                    user.ID,
                    user.ID_CPF_EMPRESA, 
                    user.usu_codigo, 
                    user.usu_nome, 
                    user.usu_email, 
                    user.usu_senha, 
                    user.usu_contato, 
                    user.usu_endereco, 
                    user.usu_numeroEnd, 
                    user.usu_pontoRef, 
                    user.bai_codigo, 
                    user.usu_key,
                    ifnull(bai.bai_nome,'bairro excluido') as bairro,                     
                    ifnull(bai.bai_taxa,'taxa excluido') as taxa
                    from usuarios user
                    left outer join bairros bai
                    on bai.bai_codigo = user.bai_codigo where usu_nivel = 'CLI'";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->execute();
            return $statement_sql->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getAllUsuarios() {
        try {
            $sql = "SELECT 
                    user.ID,
                    user.ID_CPF_EMPRESA, 
                    user.usu_codigo, 
                    user.usu_nome, 
                    user.usu_email, 
                    user.usu_senha, 
                    user.usu_contato, 
                    user.usu_endereco, 
                    user.usu_numeroEnd, 
                    user.usu_pontoRef, 
                    user.bai_codigo, 
                    user.usu_key,
                    user.usu_nivel,
                    ifnull(bai.bai_nome,'bairro excluido') as bairro,                     
                    ifnull(bai.bai_taxa,'taxa excluido') as taxa
                    from usuarios user
                    left outer join bairros bai
                    on bai.bai_codigo = user.bai_codigo where  usu_nivel  != 'CLI'";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->execute();
            return $statement_sql->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getLoginValid($usu_email, $usu_senha) {

        try {
            $sql = "SELECT 
                    user.ID_CPF_EMPRESA, 
                    user.usu_codigo, 
                    user.usu_nome, 
                    user.usu_email, 
                    user.usu_senha, 
                    user.usu_contato, 
                    user.usu_endereco, 
                    user.usu_numeroEnd, 
                    user.usu_pontoRef, 
                    user.bai_codigo, 
                    user.usu_key,
                    bai.bai_nome, 
                    bai.bai_taxa
                    from usuarios user
                    inner join bairros bai
                    on bai.bai_codigo = user.bai_codigo
                    where usu_email = ? and usu_senha = ?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $usu_email);
            $statement_sql->bindValue(2, $usu_senha);
            $statement_sql->execute();
            return $statement_sql->fetch(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            print "Erro em login " . $e->getMessage();
        }
    }

    static public function getTentativas() {
        try {
            $sql = "SELECT count(*) AS tentativas, MINUTE(TIMEDIFF(NOW(), MAX(data_hora))) AS minutos ";
            $sql .= "FROM usuarios_login_tentativas WHERE ip = ? and DATE_FORMAT(data_hora,'%Y-%m-%d') = ? AND bloqueado = ?";

            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $_SERVER['SERVER_ADDR']);
            $statement_sql->bindValue(2, date('Y-m-d'));
            $statement_sql->bindValue(3, 'SIM');
            $statement_sql->execute();
            return $statement_sql->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function insertTentativa($usu_email, $usu_senha, $bloqueado) {
        try {
            $sql = 'INSERT INTO usuarios_login_tentativas (ip, email, senha, origem, bloqueado) VALUES (?, ?, ?, ?, ?)';
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $_SERVER['SERVER_ADDR']);
            $statement_sql->bindValue(2, $usu_email);
            $statement_sql->bindValue(3, $usu_senha);
            $statement_sql->bindValue(4, $_SERVER['HTTP_REFERER']);
            $statement_sql->bindValue(5, $bloqueado);
            $statement_sql->execute();
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

}
