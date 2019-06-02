<?php
  require_once '../mapper/dbClass.php';
  require_once '../mapper/userMapper.php';
  require_once './UserClass.php';
  require_once '../mapper/functions.php';

  $connection = new DB(DB_HOST, DB_NAME, DB_USER, DB_PASS);
  $mapper = new UserMapper($connection);



  switch ($_POST['action']):
    case "register":
      if (isset($_POST['user_name']))
      {
        $user_name = $_POST['user_name'];
        $password  = $_POST['password'];
        if ((validate_username($user_name) === true) && (validate_pass($password) === true))
        {

            if (($result = $mapper->is_username_free($user_name)) !== true) {
              echo json_encode(array("result"=>false, "message"=>$result));
            } else {
              if (($res = $mapper->register($user_name, $password)) === true)
                  echo json_encode(array("result"=>true, "message"=>REG_SUCCESSFUL));
              else
                  echo json_encode(array("result"=>false, "message"=>$res));
            }
        } elseif (validate_username($user_name) === false)
                  echo json_encode(array("result"=>false, "message"=>USERNAME_INVALID_REG));

          elseif (validate_pass($password) === false)
                  echo json_encode(array("result"=>false, "message"=>PASS_INVALID_REG));
      }
    break;
    case "login":
      if (isset($_POST['user_name']))
      {
        $user_name = $_POST['user_name'];
        $pass      = $_POST['password'];


        if (($result = $mapper->login($user_name, $pass)) === true)
        {
              echo json_encode(array("result"=>true, "message"=>LOGIN_SUCCESSFUL));
        }
        else  echo json_encode(array("result"=>false, "message"=>$result));
      }
      else
      {
        // Fields weren't filled.
        echo json_encode(array("result"=>false, "message"=>FIELDS_REQUIRED));
      }
    break;
    case "index":
      $user_name = $_SESSION['user_name'];
      if ($room_name = $mapper->get_room_name($_SESSION['room']))
        echo json_encode(array("user_name"=>$user_name, "room"=>$room_name, "error"=>false));

      else echo json_encode(array("error"=>true, "message"=>$room_name));
    break;
    case "login_check":
      if (isset($_SESSION['user_name'])) {
          echo json_encode(array("logged_in"=>true));
          die();
      } else {
          echo json_encode(array("logged_in"=>false));
      }
    break;

  endswitch;

?>
