<!-- Home Page -->
<?php
  require_once 'mapper/functions.php';
  require_once 'api/userClass.php';
  require_once 'header.php';
  session_start();
  if (!isset($_SESSION['user_name']))
  {
    header("Location: ".LOGIN_PAGE);
    die();
  }
?>

<body>
  <h1>Hello <span id="user_name"></span>, welcome to our Chat. You're in room <span id="room_display"></span>.</h1>
<p id="error"></p>
  <div id="chat">
    <table id="messages">
    </table>
  </div>
  <div class="form-group">
    <input type="text" autocomplete="off" class="form-control" id="message" name="message"
      placeholder="Write a Message...">
    <button class="btn btn-primary" id="submit">Post</button>
  </div>
  <div class="form-group">
    <input type="file" id="upload_file" name="pic">
    <button class="btn btn-secondary btn-sm" id="submit_file">Upload</button>
    <p id="loading_file"></p>
  </div>
  <div id="rooms_list" class="list-group">
    <ul id="rooms_ul" class="list-group">
    </ul>
  </div>
  <br>
  <div id="create_room_buttons">
      <input type="text" class="form-control-sm" id="create_room" placeholder="Enter room name">
      <p>&nbsp;&nbsp;&nbsp;&nbsp;</p>
      <button class="btn btn-info btn-sm" id="create_room_btn">Create</button>
  </div>


<script type="module" src="/js/handler.js"></script>
<script src='/js/index.js'>
    window.onload = fetchIndex();
</script>

</body>
</html>
