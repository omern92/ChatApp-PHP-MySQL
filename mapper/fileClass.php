<?php
  require_once(dirname(__DIR__, 1) . "/const.php");


class File
{
    private $db;
    public $file_name;
    public $gen_name;
    public $ext;
    public $file_size;
    public $date_uploaded;
    public $user_name;
    public $new_path;
    const UPLOADS_DIR = 'uploaded_files/';

    public function __construct($connection, $original_name, $user_name)
    {
        $this->db = $connection;
        $this->file_name = $original_name;
        $this->gen_name = generateRandomString();
        $this->file_size = $_FILES['data']['size'];
        $this->user_name = $user_name;
        $this->date_uploaded = time();
        $this->ext = pathinfo($original_name, PATHINFO_EXTENSION);
        $this->new_path = self::UPLOADS_DIR + $this->gen_name;

        move_uploaded_file($_FILES['data']['tmp_name'], $this->new_path);
        $this->upload_file();
    }

    public function check_img()
    {
        $img_array = array('jpg', 'jpeg', 'gif', 'png', 'tiff');
        if (in_array($this->ext, $img_array))
            return true;
    }

    private function upload_file()
    {
        try {
            $stmt = $this->db->connection->prepare("INSERT INTO files VALUES(NULL,?,DEFAULT,?,?,?,?)");
            $stmt->bind_param('sssis', $this->user_name, $this->file_name, $this->gen_name, $this->file_size,
                               $this->ext);
            $stmt->execute();
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            echo "$e->getMessage()";
        }
    }

    public function post_img()
    {
        $mime = $_FILES['data']['type'];
        $contents = file_get_contents($this->new_path);
        $base64 = base64_encode($contents);
        return "<img src='data:$mime;base64,$base64' alt='$this->file_name' height='150' width='150'>";
    }

    public function post_file()
    {
        try {
            $stmt = $this->db->connection->prepare("SELECT id FROM files WHERE username=? AND gen_name=?");
            $stmt->bind_param('ss', $this->user_name, $this->gen_name);
            $stmt->execute();
            $stmt->bind_result($id);
            $stmt->fetch();
            $file_name = "$this->file_name";
            // file size in MB.
            $file_size = round(($this->file_size/1048576), 4);
            return "<a href='messages.php?action=getFile&id=$id'>$file_name</a>, size: $file_size MB.";


        } catch (mysqli_sql_exception $e) {
            echo "$e->getMessage()";
        }
    }

    public function file_details($id)
    {
    try {
        $stmt = $this->db->connection->prepare("SELECT original_name, gen_name, size, ext  FROM files WHERE id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($original_name, $gen_name, $size, $ext);
        $stmt->fetch();
        $details = array('file_path'     => $this->new_path,
                         'original_name' => "$original_name",
                         'size'          => "$size");

        return $details;
        } catch (mysqli_sql_exception $e) {
            echo "$e->getMessage()";
        }
    }
}


function generateRandomString($length = 20)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


?>