<?php
// Caminho do arquivo JSON
$filename = 'tarefas.json';

// Carrega tarefas
$tarefas = file_exists($filename) ? json_decode(file_get_contents($filename), true) : [];

// Adiciona nova tarefa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo'])) {
    $newTask = trim($_POST['titulo']);
    if ($newTask !== '') {
        //done está por padrão false, pois se estiver como true informará que a atividade já foi concluída.
        //a tarefa aparecerá como concluída, quando for clicada em FEITO e ficará com um traço em todo o texto.
        $tarefas[] = ['titulo' => $newTask, 'done' => false];
        file_put_contents($filename, json_encode($tarefas, JSON_PRETTY_PRINT));
        header("Location: index.php");
        exit;
    }
}

// Marca como feita
if (isset($_GET['done'])) {
    $id = (int)$_GET['done'];
    if (isset($tarefas[$id])) {
        $tarefas[$id]['done'] = !$tarefas[$id]['done'];
        file_put_contents($filename, json_encode($tarefas, JSON_PRETTY_PRINT));
        header("Location: index.php");
        exit;
    }
}

// Exclui tarefa
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if (isset($tarefas[$id])) {
        array_splice($tarefas, $id, 1);
        file_put_contents($filename, json_encode($tarefas, JSON_PRETTY_PRINT));
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>To-Do List em PHP</title>
    <style>
        body { font-family: Arial; max-width: 600px; margin: 30px auto; display: flex; justify-content: center; flex-direction: column; align-items: center;}
        .done { text-decoration: line-through; color: gray; }
        form { width: 300px; height: auto; padding: 20px 5px; background: linear-gradient(130deg, rgba(2,0,36,1) 0%, rgba(9,9,121,1) 35%, rgba(0,212,255,1) 100%); text-align: center; border-radius: 5px;}
        ul { padding: 0; list-style: none; }
        li { margin-bottom: 10px; }
        a:nth-child(2) {color: green;}
        a:nth-child(3) {color: red;}
        button {width: 178px; margin-top: 10px;}
    </style>
</head>
<body>
    <h1>To-Do List com PHP e JSON</h1>

    <form method="POST">
        <input type="text" name="titulo" placeholder="Nova tarefa" required><br>
        <button type="submit">Adicionar</button>
    </form>

    <ul>
        <?php foreach ($tarefas as $index => $task): ?>
            <li>
                <span class="<?= $task['done'] ? 'done' : '' ?>"><?= htmlspecialchars($task['titulo']) ?></span>
                <a href="?done=<?= $index ?>">[<?= $task['done'] ? 'Desfazer' : 'Feito' ?>]</a>
                <a href="?delete=<?= $index ?>" onclick="return confirm('Excluir titulo?')">[Excluir]</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
