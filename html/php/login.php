<?php
  session_start();
  require dirname(__FILE__).'/mysql-connect.php';

  if(!isset($_POST['email']) || !isset($_POST['password'])) {
    http_response_code(400);
    echo '{"message": "Oops, there was a problem logging you in!", "info": "Parameters were not passed.","success": false}';
    return;
  }

  $email = $_POST['email'];
  $password = $_POST['password'];

  openConnection();

  $safeEmail = mysqli_escape_string($connection, $email);
  $query = "SELECT * FROM ssusers WHERE UserName = '$safeEmail'";

  if($result = runQuery($query)) { // Query executed successfully
    if(mysqli_num_rows($result) == 0) { // Row with email not found
      echo '{"message": "Login information is not correct.", "info": "Login information is not correct.","success": false}';
    }
    else {
      $row = mysqli_fetch_assoc($result);
      if(password_verify($password, $row['Password'])) { // Password does not match
        echo '{"message": "Login information is not correct.", "info": "Login information is not correct.","success": false}';
      }
      else {
        echo '{"message": "Login successful", "info": "Successful","success": true}';
        $permission = 1;
        $_SESSION['user'] = $row['UserName'];
        $_SESSION['userPermission'] = $permission;
      }

    }
  }
  else {
    http_response_code(500);
    echo '{"message": "There was an internal server problem", "info": "MySQL query failed.","success": false}';
  }

  closeConnection();

 ?>
