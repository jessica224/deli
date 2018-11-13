<?php

namespace App\Model;

class SubAdicionaisDao {

    static public function getSubAdicional($sub_codigo) {
        try {
            $sql = "select * from sub_adicionais where sub_codigo = ?";
            $stmt = Conexao::getConnection()->prepare($sql);
            $stmt->bindValue(1, $sub_codigo);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getSubAdicionais_Adicionais($adc_codigo) {
        try {
            $sql = "select * from sub_adicionais where adc_codigo = ?";
            $stmt = Conexao::getConnection()->prepare($sql);
            $stmt->bindValue(1, $adc_codigo);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getSubAdicionais() {
        try {
            $sql = "select * from sub_adicionais";
            $stmt = Conexao::getConnection()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function salvar(\stdClass $std) {
        try {
            $sql = "insert into sub_adicionais (ID_CPF_EMPRESA, sub_descricao, sub_img, sub_preco, adc_codigo) values (?,?,?,?,?)";
            $stmt = Conexao::getConnection()->prepare($sql);
            $stmt->bindValue(1, $std->ID_CPF_EMPRESA);
            $stmt->bindValue(2, $std->sub_descricao);
            $stmt->bindValue(3, $std->sub_img);
            $stmt->bindValue(4, $std->sub_preco);
            $stmt->bindValue(5, $std->adc_codigo);
            $stmt->execute();
            return Conexao::getConnection()->lastInsertId();
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function editar(\stdClass $std) {
        try {
            $sql = "update sub_adicionais set ID_CPF_EMPRESA=?, sub_descricao=?, sub_img=?, sub_preco=?, adc_codigo=? where sub_codigo = ?";
            $stmt = Conexao::getConnection()->prepare($sql);
            $stmt->bindValue(1, $std->ID_CPF_EMPRESA);
            $stmt->bindValue(2, $std->sub_descricao);
            $stmt->bindValue(3, $std->sub_img);
            $stmt->bindValue(4, $std->sub_preco);
            $stmt->bindValue(5, $std->adc_codigo);
            $stmt->bindValue(6, $std->sub_codigo);
            return $stmt->execute();
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function delete($sub_codigo) {
        try {
            $sql = "delete from sub_adicionais where sub_codigo=?";
            $stmt = Conexao::getConnection()->prepare($sql);
            $stmt->bindValue(1, $sub_codigo);
            return $stmt->execute();
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

}
