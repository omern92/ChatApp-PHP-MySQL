<?php
  require_once(dirname(__DIR__, 1) . "/const.php");

class UserMapper
{
    private $db;

    function __construct($connection)
    {
        $this->db = $connection;
    }

    public function register($user_name, $pass)
    {
        try {
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users(username, password, id, room) VALUES(?,?,NULL,'1')";
            if ($stmt = $this->db->connection->prepare($sql))
            {
                $stmt->bind_param('ss', $user_name, $hashed_pass);
                $stmt->execute();
                $stmt->close();
                return true;
            }
            else {
                $error = $this->db->connection->errno . ' ' . $this->db->connection->error;
                return $error;
            }
        } catch (mysqli_sql_exception $e) {
           return $e->getMessage();
        }
    }

    public function login($user_name, $pass)
    {
        try {
            $stmt = $this->db->connection->prepare("SELECT password, room FROM users WHERE username=?");
            $stmt->bind_param('s', $user_name);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($user_saved_pass, $room_id);
            $stmt->fetch();
            if ($stmt->num_rows > 0)
            {
                if (password_verify($pass, $user_saved_pass))
                {
                    $_SESSION['user_name'] = $user_name;
                    $_SESSION['room']      = $room_id;
                    return true;
                }
                else
                {
                    return INCORRECT_PASS;
                }
            } else {
                    return INCORRECT_USER;
            }
            $stmt->close();

        } catch (mysqli_sql_exception $e) {
           return   $e->getMessage();
        }
    }

    public function is_username_free($user_name)
    {
      try {
          $stmt = $this->db->connection->prepare("SELECT username FROM users WHERE username=?");
          $stmt->bind_param('s', $user_name);
          $stmt->execute();
          $stmt->store_result();
          } catch (Exception $e) {
              return $e->getMessage();
          }
          if ($stmt->num_rows) {
             return USERNAME_EXISTS;
          } else {
            return true;
          }
    }

    public function get_room_name($room_id)
    {
        try {
            $stmt = $this->db->connection->prepare("SELECT name FROM rooms WHERE id=?");
            $stmt->bind_param('i', $room_id);
            $stmt->execute();
            $stmt->bind_result($room_name);
            $stmt->fetch();
            $stmt->close();
            return $room_name;
        } catch (mysqli_sql_exception $e) {
            return false;
        }
    }

    public function add_message($user_name, $text, $room_id)
    {
        try {
            $stmt = $this->db->connection->prepare("INSERT INTO messages VALUES(NULL,?,DEFAULT,?,?)");
            $stmt->bind_param('ssi', $user_name, $text, $room_id);
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (mysqli_sql_exception $e) {
            return false;
        }
    }

    public function check_new_messages($room_id, $last_message)
    {
        try {
            $result = $this->db->connection->prepare("SELECT name FROM messages WHERE room=? AND time>$last_message");
            $result->bind_param('i', $room_id);
            $result->execute();
            $result->store_result();
            if ($result->num_rows == 0) {
              $result->close();
              return false;
            }
            $result->close();
            return true;
              
  
          } catch (Exception $e) {
            return $e->getMessage();
          }
    }

    public function display_messages($room_id)
    {
        try {
            $stmt = $this->db->connection->prepare("SELECT username, time, text FROM messages WHERE room=? LIMIT 100");
            $stmt->bind_param('i', $room_id);
            $stmt->execute();
            $stmt->bind_result($user_name, $time, $text);
            $messages = array();
            $i = 1;
            while ($stmt->fetch()) {
                $messages["message$i"] = array("time"=>$time, "user_name"=>$user_name, "text"=>$text);
                $i++;
            }
            $stmt->close();
            return $messages;
        } catch (mysqli_sql_exception $e) {
            return $e->getMessage();
        }
    }

    //// Rooms area.
    public function room_exists($room_name)
    {
      try {
          $result = $this->db->connection->prepare("SELECT name FROM rooms WHERE name=?");
          $result->bind_param('s', $room_name);
          $result->execute();
          $result->store_result();
          if ($result->num_rows == 0) {
            $result->close();
            return false;
          }
          $result->close();
          return true;
            

        } catch (Exception $e) {
          return $e->getMessage();
        }

    }

    public function add_room($user_name, $room_name)
    {
        try {
            // Add to rooms list.
            $stmt = $this->db->connection->prepare("INSERT INTO rooms VALUES(NULL, ?)");
            $stmt->bind_param('s', $room_name);
            $stmt->execute();
            $room_id = $stmt->insert_id;
            // Change the user's room.
            $this->change_room($user_name, $room_id);
            $stmt->close();
            return true;
        } catch (mysqli_sql_exception $e) {
            return false;
        }
    }

    public function change_room($user_name, $room_id)
    {
            $stmt = $this->db->connection->prepare("UPDATE users SET room=? WHERE username=?");
            $stmt->bind_param('is', $room_id, $user_name);
            $stmt->execute();
            $_SESSION['room'] = $room_id;
            $stmt->close();
            return true;
        if ($stmt === FALSE) {
            return DB_ERROR;
        }
    }

    public function get_rooms()
    {
        try {
          $result = $this->db->connection->prepare("SELECT id, name FROM rooms");
          $result->execute();
          $result->bind_result($room_id, $room_name);
          $rooms = array();
          $i = 1;
          while ($result->fetch()) {
                $rooms["room$i"] = array("room_id"=>$room_id, "room_name"=>$room_name);
                $i++;
          }
          $result->close();
          return $rooms;
        } catch (Exception $e) {
            return false;
        }
    }
}

session_start();


?>