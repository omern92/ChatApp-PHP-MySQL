<?php


  class User
  {
    public $id;
    public $user_name;
    public $password;
    public $room_name;
    public $room_id;

    private $user_mapper;

    function __construct($user_name, $room_id, $user_mapper)
    {
      $this->user_name   = $user_name;
      $this->room_id     = $room_id;
      $this->user_mapper = $user_mapper;

      $this->room_name   = $this->user_mapper->get_room_name($room_id);
    }

    public function add_message($text)
    {
      if ($this->user_mapper->add_message($this->user_name, $text, $this->room_id))
        return true;
    }

    public function add_room($room_name)
    {
      if ($this->user_mapper->add_room($this->user_name, $room_name)) {
        $this->room_name = $room_name;
        return true;
      }
    }






  }




?>
