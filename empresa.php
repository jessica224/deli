<?php

error_reporting(E_ALL ^ E_NOTICE);

$rand =  time() . rand(0, getrandmax());
$nome_nova_pasta = md5($rand);
$nome_pasta_velha = file_get_contents('renamelinks.txt');
rename('./views/cursoappvendas/' . $nome_pasta_velha, './views/cursoappvendas/' . $nome_nova_pasta);

//atualizando o nome da pasta no arquivo txt
$arquivo = fopen('renamelinks.txt', 'w+');
fwrite($arquivo, $nome_nova_pasta);
fclose($arquivo);


