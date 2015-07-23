<?php

  require dirname(__FILE__).'/mysql-connect.php';

  if(!isset($_POST['email']) || !isset($_POST['passwordHash'])) {
    http_response_code(400);
    echo '{"message": "Oops, there was a problem logging you in!", "info": "Parameters were not passed.","success": false}';
    return;
  }

  $email = $_POST['email'];
  $passwordHash = $_POST['passwordHash'];

  openConnection();

  $safeEmail = mysqli_escape_string($connection, $email);
  $safePasswordHash = mysqli_escape_string($connection, $passwordHash);
  $query = "SELECT email,permission FROM user WHERE email = '$safeEmail' and password = '$safePasswordHash';";

  if($result = runQuery($query)){
    if(mysqli_num_rows($result) == 0){
      echo '{"message": "Login information is not correct.", "info": "Parameters were not passed.","success": false}';
    }
    else{
      echo '{"message": "Login successful", "info": "Successful","success": true}';
      session_start();
      $row = mysqli_fetch_assoc($result);
      $permission = $row['permission'];
      $_SESSION['userEmail'] = $_POST['email'];
      $_SESSION['userPermission'] = $permission;
    }
  }
  else {
    echo "$query";
  }

  closeConnection();

 ?>
