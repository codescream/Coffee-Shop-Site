<?php
  $dsn = 'mysql:host=localhost;dbname=coffeeshop_db';
  $username = 'markogilo';
  $password = 'password';

  try
  {
    $db = new PDO($dsn, $username, $password);
  }
  catch(PDOException $e)
  {
    $error_message = $e->getMessage();
    include('views/database_error.php');
    exit();
  }
?>