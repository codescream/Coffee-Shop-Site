<?php
session_start();

include('database.php');
// include('cart_model.php');
include('item_model.php');

  $action = filter_input(INPUT_POST, "action");

  if($action == "login")
  {
    $user_name = filter_input(INPUT_POST, 'username');
    $pswd = filter_input(INPUT_POST, 'password');

    $_SESSION['session_user_name'] = login($user_name, $pswd)['username']; 
    session_write_close();
    header('Location: ../index.php');
  }
  else if($action == "logout")
  {
    session_destroy();
    header('Location: ../index.php');
  }
  else if($action == "signup")
  {
    $user_name = filter_input(INPUT_POST, 'email');
    $pswd = filter_input(INPUT_POST, 'password');
    $confpswd = filter_input(INPUT_POST, 'confpass');

    if($pswd === $confpswd)
    {
      $_SESSION['session_user_name'] = signup($user_name, $pswd)['username']; 
    }
    else
    {
      $_SESSION['session_signuperr'] = "passwords do not match!!!";
    }

    session_write_close();
    header('Location: ../index.php');
  }


  function login($username, $password)
  {
    global $db;
    $query = "SELECT * FROM tbl_user WHERE username= '$username'";
    $statement = $db->query($query);
    
    if ($statement != null)
    {
      $result = $statement->fetch();
      if($result != null)
      {
        if(password_verify($password , $result['password']))
        {
          
          $_SESSION['user_id'] = $result['user_id'];

          if(isset($_SESSION['temp_session']))
          {
            if(isset($_SESSION['temp_tbl_item']))
            {
              moveTempData($_SESSION['user_id']); // in item_model.php
            }

            unset($_SESSION['temp_session']);
          }
          
          checkcart($_SESSION['user_id']); // in cart_model.php
          return $result;
        }
        else
        {
          $_SESSION['session_loginerr'] = "Error in username or password!!!";
        }
      }
      else
      {
        $_SESSION['session_loginerr'] = "Error in username or password!!!";
      }
    }
    else
    {
      $_SESSION['session_loginerr'] = "Error in username or password!!!";
    }
  } // end login

  function signup($user_name, $password)
  {
    global $db;
    $username = $db->quote($user_name);

    $query = "SELECT * FROM tbl_user WHERE username= '$user_name'";
    $statement = $db->query($query);
    
    if ($statement != null)
    {
      $result = $statement->fetch();
      if($result == null)
      {
        //generating appropriate hash cost option
        $timeTarget = 0.05; // 50 milliseconds 
        $cost = 8;
        do {
            $cost++;
            $start = microtime(true);
            password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
            $end = microtime(true);
        } while (($end - $start) < $timeTarget);

        $options = [
          'cost' => $cost,
        ];

        //hash password
        $pswd = $db->quote(password_hash($password, PASSWORD_BCRYPT, $options));

        $query = "INSERT INTO tbl_user (username, password) VALUES ($username, $pswd)";

        $insert_count = $db->exec($query);

        if($insert_count)
        {
          // echo "User successfully created";
          $user_id = $db->lastInsertId();
          $id = $db->quote($user_id);

          $query = "SELECT * FROM tbl_user WHERE user_id = :id";
          $statement = $db->prepare($query);
          $statement->bindValue(':id', $user_id);
          $statement->execute();
          $result = $statement->fetch();	
          
          $statement->closeCursor();

          if($result != null)
          {
            $_SESSION['user_id'] = $user_id; // set logged in session
            $_SESSION['session_signupsuccess'] = "new user created successfully!";

            checkcart($user_id); // in cart_model.php - gets here!

            if(isset($_SESSION['temp_session']))
            {
              if(isset($_SESSION['temp_tbl_item']))
              {
                moveTempData($user_id); // in item_model.php
              }

              checkcart($user_id); 
              unset($_SESSION['temp_session']);
            }

            return $result;
          }
        }
      }  
      else
      {
        $_SESSION['session_signuperr'] = "username already exist!!!";
      }
    }
  } // end signup
?>