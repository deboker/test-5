<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php';
# This logic handles connecting to the database, where we store our todo status
$pdo = new \PDO("sqlite:" . "db/sqlite.db");

# This PHP logic handles user actions
# New TODO
if (isset($_POST['submit'])) {
  $description = $_POST['description'];
  $sth = $pdo->prepare("INSERT INTO todos (description) VALUES (:description)");
  $sth->bindValue(':description', $description, PDO::PARAM_STR);
  $sth->execute();
  header("Location: index.php");
  exit;
}
# Delete TODO
elseif (isset($_POST['delete'])) {
  $id = $_POST['id'];
  $sth = $pdo->prepare("delete from todos where id = :id");
  $sth->bindValue(':id', $id, PDO::PARAM_INT);
  $sth->execute();
  header("Location: index.php");
  exit;
}
# Update completion status
elseif (isset($_POST['complete'])) {
  $id = $_POST['id'];
  $sth = $pdo->prepare("UPDATE todos SET complete = 1 where id = :id");
  $sth->bindValue(':id', $id, PDO::PARAM_INT);
  $sth->execute();
  header("Location: index.php");
  exit;
}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
  <link href="public/styles.css" rel="stylesheet">
  <title>Todo List</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body class="bg-gray-100 text-gray-800 antialiased font-sans">
  <div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-2">Todo List</h1>
    <form method="post" action="" class="mb-4">
      <input type="text" name="description" placeholder="Add a new task" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
      <button type="submit" name="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-3">Add</button>
    </form>
    <h2 class="text-2xl font-bold mb-2">Current Todos</h2>
    <table class="table-auto w-full mb-4">
      <thead>
        <tr class="bg-gray-200">
          <th class="px-4 py-2">Task</th>
          <th class="px-4 py-2">Complete</th>
          <th class="px-4 py-2">Delete</th>
        </tr>
      </thead>
      <tbody>

      <?php
      $sth = $pdo->prepare("SELECT * FROM todos ORDER BY id DESC");
      $sth->execute();

      foreach ($sth as $row) {
        echo "<tr class='bg-white'>";
        echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['description']) . "</td>";
        echo "<td class='border px-4 py-2 text-center'>";
        if (!$row['complete']) {
            echo "<form method='POST'><button type='submit' name='complete' class='bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded'>Complete</button><input type='hidden' name='id' value='" . $row['id'] . "'><input type='hidden' name='complete' value='true'></form>";
        } else {
            echo "Task Complete!";
        }
        echo "</td>";
        echo "<td class='border px-4 py-2 text-center'><form method='POST'><button type='submit' name='delete' class='bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded'>Delete</button><input type='hidden' name='id' value='" . $row['id'] . "'><input type='hidden' name='delete' value='true'></form></td>";
        echo "</tr>";
      }
      ?>

      </tbody>
    </table>
  </div>
</body>
</html>
