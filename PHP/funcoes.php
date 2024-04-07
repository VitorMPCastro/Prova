<?php
session_start();
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $functionName = $_POST['functionName'];

    switch ($functionName) {
        case 'createNewUser':
            createNewUser($_POST['name'], $_POST['email'], $_POST['password']);
        break;

        case 'createNewBook':
            createNewBook($_POST['titulo'], $_POST['ano_pub'], $_POST['autor']);
        break;

        case 'createNewAuthor':
            createNewAuthor($_POST['name'], $_POST['nationality']);
        break;

        case 'listUsers':
            listUsers();
        break;

        case 'listBooks':
            listBooks();
        break;
        
        case'listAuthors':
            listAuthors();
        break;

        case 'updateUser':
            updateUser($_POST['nameOld'], $_POST['emailOld'], $_POST['passwordOld'], $_POST['nameNew'], $_POST['emailNew'], $_POST['passwordNew']);
        break;

        case 'updateLivro':
            updateLivro($_POST['id'], $_POST['tituloNew'], $_POST['ano_pubNew'], $_POST['autorNew']);
        break;

        case 'updateAutor':
            updateAutor($_POST['id'], $_POST['nomeNew'], $_POST['nacionalidadeNew']);
        break;

        case 'deleteUsuario':
            deleteUsuario($_POST['id']);
        break;

        case 'deleteLivro':
            deleteLivro($_POST['id']);
        break;
        
        case 'deleteAutor':
            deleteAutor($_POST['id']);
        break;
    }
}

function createNewUser($username, $email, $password) {
    global $conn;
    $sql = "INSERT INTO usuario (nome, email, senha) VALUES ('$username', '$email', '$password')";
    if ($conn->query($sql)) {
        echo "Usuario registrado: $username\n Email: $email\n";
    } else {
        echo "Falha no processo de registro.\n";
    }
}

function createNewBook($titulo, $ano_publicacao, $autor_id){
    global $conn;
    $sql = "INSERT INTO livros (titulo, ano_publicacao, autor_id) VALUES ('$titulo', $ano_publicacao, '$autor_id')";
    if ($conn->query($sql)) {
        echo "Novo livro registrado: $titulo\n Autor: $autor_id\n";
    } else {
        echo "Falha no processo de registro.\n";
    }
}

function createNewAuthor($nome, $nacionalidade) {
    global $conn;
    $sql = "INSERT INTO autor (nome, nacionalidade) VALUES ('$nome', '$nacionalidade')";
    if ($conn->query($sql)) {
        echo "Autor registrado: $nome; Nacionalidade: $nacionalidade";
    }
}

function listUsers() {
    global $conn;
    $result = $conn->query("SELECT * FROM usuario");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<br>" . $row['id'] . "<br>" . $row['nome'] . "<br>" . $row['email'];
        }
    } else {
        echo "No users found.";
    }
}


function listBooks() {
    global $conn;
    $result = $conn->query("SELECT * FROM livros");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<br>". $row['id'] ."<br>".$row['titulo']."<br>".$row['ano_publicacao']."<br>".$row['autor_id'];
        }
    } else {
        echo "No books found.";
    }
}

function listAuthors() {
    global $conn;
    $result = $conn->query("SELECT * FROM autor");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<br>". $row['id'] ."<br>".$row['nome']. "<br>".$row['nacionalidade'];
        }
    } else {
        echo "No authors found.";
    }
}


function listSingularUser($nome, $email, $senha) {
    global $conn;
    $sql = "SELECT * FROM usuario WHERE nome = '$nome' AND email = '$email' AND senha = '$senha'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

function updateUser($nomeToSearch, $emailToSearch, $senhaToSearch, $nomeNew, $emailNew, $senhaNew) {
    global $conn;
    $usuario = listSingularUser($nomeToSearch, $emailToSearch, $senhaToSearch);
    if ($usuario) {
        $sql = "UPDATE usuario SET nome = '$nomeNew', email = '$emailNew', senha = '$senhaNew' WHERE nome = '$nomeToSearch' AND email = '$emailToSearch' AND senha = '$senhaToSearch'";
        if ($conn->query($sql) === TRUE) {
            echo "Usuario modificado: $nomeToSearch, $emailToSearch -> $nomeNew, $emailNew";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "User not found.";
    }
}


function updateLivro($id, $tituloNew, $ano_publicacaoNew, $autor_idNew) {
    global $conn;
    $tituloNew = $conn->real_escape_string($tituloNew);
    $ano_publicacaoNew = $conn->real_escape_string($ano_publicacaoNew);
    $autor_idNew = $conn->real_escape_string($autor_idNew);
    
    $sql = "UPDATE livros SET titulo = '$tituloNew', ano_publicacao = '$ano_publicacaoNew', autor_id = '$autor_idNew' WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        echo "Livro modificado: $id, $tituloNew, $ano_publicacaoNew, $autor_idNew";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

function updateAutor($id, $nomeNew, $nacionalidadeNew) {
    global $conn;
    $nomeNew = $conn->real_escape_string($nomeNew);
    $nacionalidadeNew = $conn->real_escape_string($nacionalidadeNew);
    
    $sql = "UPDATE autor SET nome = '$nomeNew', nacionalidade = '$nacionalidadeNew' WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        echo "Autor alterado: $id, $nomeNew, $nacionalidadeNew";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}


function deleteUsuario($id) {
    global $conn;
    $sql = "DELETE FROM usuario WHERE id = $id";
    if ($conn->query($sql)) {
        echo "Usuario deletado.";
    }
}

function deleteLivro($id) {
    global $conn;
    $sql = "DELETE FROM livros WHERE id = $id";
    if ($conn->query($sql)) {
        echo "Livro deletado.";
    }
}

function deleteAutor($id) {
    global $conn;
    $sql = "DELETE FROM autor WHERE id = $id";
    if ($conn->query($sql)) {
        echo "Autor deletado.";
    } else {
        echo "Erro ao deletar autor. Verifique se os livros relacionados foram deletados.";
    }
}