<?php
  require_once '../mapper/dbClass.php';
  require_once '../mapper/userMapper.php';
  require_once 'UserClass.php';



    $user_name = $_SESSION['user_name'];
    $room_id = $_SESSION['room'];


    $connection = new DB(DB_HOST, DB_NAME, DB_USER, DB_PASS);
    $mapper     = new UserMapper($connection);
    $user       = new User($user_name, $room_id, $mapper);


    switch ($_POST['action']):
      // Add a room.
      case "addRoom":
        $room_name = $_POST['data'];

        if ($mapper->room_exists($room_name) === true)
          echo json_encode(array("result"=>false, "message"=>ROOM_EXISTS));
        else
        {
          if ($user->add_room($room_name))
            echo json_encode(array("result"=>true, "message"=>ROOM_ADDED));
          else {
            echo json_encode(array("result"=>false, "message"=>DB_ERROR));
          }
        }
      break;

      // Change a room.
      case "changeRoom":
        $room_id = $_POST['data'];
        if ($mapper->change_room($user_name, $room_id))
          echo json_encode(array("result"=>true, "message"=>null));
        else
          echo json_encode(array("result"=>false, "message"=>DB_ERROR));
      break;

      // Display the rooms.
      case "getRooms":
          $rooms = $mapper->get_rooms();
          if ($rooms) {
            echo json_encode(array("result"=>true, "message"=>$rooms));
          }
          else
          {
            echo json_encode(array("result"=>false, "message"=>DB_ERROR));
          }
        break;
    endswitch;


?>
