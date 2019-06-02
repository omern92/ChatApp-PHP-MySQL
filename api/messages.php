<?php

  require_once '../mapper/dbClass.php';
  require_once '../mapper/userMapper.php';
  require_once '../mapper/fileClass.php';
  require_once 'UserClass.php';


    $user_name = $_SESSION['user_name'];
    $room_id = $_SESSION['room'];

    $connection = new DB(DB_HOST, DB_NAME, DB_USER, DB_PASS);
    $mapper     = new UserMapper($connection);
    $user       = new User($user_name, $room_id, $mapper);

    switch ($_POST['action']):
        case "addMessage":
          $text = $_POST['data'];
          if ($user->add_message($text))
            echo json_encode(array("result"=>true));
          else
            echo json_encode(array("result"=>false, "message"=>DB_ERROR));
        break;

        case "addFile":
          $original_name = $_FILES['data']['name'];
          $file = new File($connection, $original_name, $user_name);
          // Check for image file.
          if ($file->check_img()) {
            $post = $file->post_img();
            $user->add_message($post);
          } // If the file isn't an image, display a link to download:
          else {
            $post = $file->post_file();
            $user->add_message($post);
          }
        break;

        case "checkMessages":
          while (true) {
            $room_id = $_SESSION['room'];
            $last_message = $_SESSION['last_message'];
            // If last_message doesn't exists, display messages.
            if (!$last_message) {
              echo json_encode(array("result"=>true));
              break;
            }
            // If it does exist, check for new messages.
            else {
              $new_message = $mapper->check_new_messages($room_id, $last_message);
              if ($new_message) {
                echo json_encode(array("result"=>true));
                break;
              } else {
                sleep(1);
              }
            }
          }
        break;


        case "getMessages":
          $room_id = $_SESSION['room'];
          $messages = $mapper->display_messages($room_id);
          if ($messages) {
            echo json_encode(array("result"=>true, "message"=>$messages));
            $last_message = end($messages);
            $_SESSION['last_message'] = $last_message['time'];

          } else {
            echo json_encode(array("result"=>false, "message"=>$messages));
          }
        break;          


        case "getFile":
          if (isset($_GET['id']))
          {
            $id = $_GET['id'];
            $details = $file->file_details($id);
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.$details['original_name'].'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . $details['size']);
            readfile($details['file_path']);
            exit;
          } else {
            die("Not allowed");
          }
        break;
    endswitch;

?>

