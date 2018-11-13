<?php

namespace App\Model;

class PedidosDao extends Dao {

    static public function getPedidosPorPeriodo($data1, $data2) {
        try {
            $sql = "SELECT * FROM cpedido where ped_status != 'PEDIDO RECUSADO' and ped_dataHora between ? and ?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $data1);
            $statement_sql->bindValue(2, $data2);
            $statement_sql->execute();
            return $statement_sql->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getPedidosPorCliente($cli_codigo) {
        try {
            $sql = "SELECT * FROM cpedido where ped_status != 'PEDIDO RECUSADO' and cli_codigo = ?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $cli_codigo);
            $statement_sql->execute();
            return $statement_sql->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getStatusPedido($ped_chave) {
        try {
            $sql = "select ped_status from cpedido where ped_chave = ?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $ped_chave);
            $statement_sql->execute();
            return $statement_sql->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getCPedido($ped_chave) {
        try {
            $sql = "select * from cpedido where ped_chave = ?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $ped_chave);
            $statement_sql->execute();
            return $statement_sql->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getAllPedidos($status) {
        try {
            $sql = "select * from cpedido where ped_status = ? AND DATE_FORMAT(ped_dataHora, '%Y-%m-%d') = CURDATE() ";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $status);
            $statement_sql->execute();
            return $statement_sql->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getItemPedido($pedchave, $prdcodigo) {
        try {
            $sql = "select * from dpedido where pedchave = ? and prdcodigo = ?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $pedchave);
            $statement_sql->bindValue(2, $prdcodigo);
            $statement_sql->execute();
            return $statement_sql->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getProdutoPedido($pedchave) {
        try {
            $sql = "select * from dpedido where pedchave = ? and tipo ='PRODUTO' order by prdcodigo asc";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $pedchave);
            $statement_sql->execute();
            return $statement_sql->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getAdicionalPedido($pedchave) {
        try {
            $sql = "select * from dpedido where pedchave = ? and tipo ='ADICIONAL'";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $pedchave);
            $statement_sql->execute();
            return $statement_sql->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getItemsPedido($pedchave) {
        try {
            $sql = "select * from dpedido where pedchave = ?";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $pedchave);
            $statement_sql->execute();
            return $statement_sql->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    static public function getProdutosMaisVendidos($data1, $data2) {
        try {
            $sql = "select sum(i.quantidade) as qtd, 
                    i.prdcodigo as cod, 
                    pr.prd_descricao as descr
                    from cpedido p 
                    inner join dpedido i on p.ped_chave = i.pedchave
                    inner join produtos pr on pr.prd_codigo = i.prdcodigo
                    where p.ped_status !=  'PEDIDO RECUSADO' and p.ped_dataHora between ? and ? group by i.prdcodigo order by qtd desc";
            $statement_sql = Conexao::getConnection()->prepare($sql);
            $statement_sql->bindValue(1, $data1);
            $statement_sql->bindValue(2, $data2);
            $statement_sql->execute();
            return $statement_sql->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

}
