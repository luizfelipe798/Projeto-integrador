<?php
    function conecta()
    {
        $servidor = 'localhost';
        $banco = 'sailus';
        $port = 3306;
        $usuario = 'root';
        $senha = '815674815';

        $conn = new mysqli($servidor, $usuario, $senha, $banco, $port);

        if($conn->connect_error)
        {
            echo 'Erro: Não foi possível conectar ao MySql.' . PHP_EOL;
            echo 'Debugging errno: ' . $conn->connect_error . PHP_EOL;
            echo 'Debugging error: ' . $conn->connect_error . PHP_EOL;
        }

        return $conn;
    }

    function desconecta($conn)
    {
        mysqli_close($conn);
    }
?>