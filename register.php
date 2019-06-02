<?php
  require_once 'api/userClass.php';
  require_once 'header.php';

  if (isset($_SESSION['user_name'])) destroy_session();
?>
<body>
    <h1>Registration</h1>
    <p style="color:red;" id="error"></p>
        <div class="form-group">
            <label for="username_register">Username</label>
            <input type="text" class="form-control" id="username_register" name="user_name" placeholder="Enter Username">
        </div>
        <div class="form-group">
            <label for="password_register">Password</label>
            <input type="password" class="form-control" id="password_register" name="pass" placeholder="Password">
        </div>
        <button id="register_btn" class="btn btn-primary">Register</button>
        <div class="form-group">
            <a href="login.html">Have a user? Login here.</a>
        </div>

<script type="module" src="/js/handler.js"></script>
<script type="module" src="/js/register.js"></script>
</body>
</html>
