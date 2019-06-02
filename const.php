<?php
  define("ROOT", $_SERVER['DOCUMENT_ROOT']);
  define("THIS_FOLDER", __DIR__);
  define("LOGIN_PAGE", "login.php");
  define("DB_ERROR", "Error connecting to Database.");

  // Database credentials
  define("DB_HOST", "localhost");
  define("DB_NAME", "new");
  define("DB_USER", "omer");
  define("DB_PASS", "password");

  // Login
  define("USER_INVALID_LOGIN", "Invalid login");
  define("INCORRECT_PASS", "Incorrect password.");
  define("INCORRECT_USER", "Incorrect username.");
  define("FIELDS_REQUIRED", "Please fill all the fields");
  define("LOGIN_SUCCESSFUL", "Logged in successfully! You are redirected...");

  // Registration
  define("USERNAME_INVALID_REG",
          "Username must be at least 3 characters and must contain only letters, numbers, - and _\n");
  define("USERNAME_EXISTS", "Username already exists.");
  define("PASS_INVALID_REG", "Password must be at least 3 characters.\n");
  define("REG_SUCCESSFUL", "Account created succssesfully. You are redirected...");

  // Rooms
  define("ROOM_ADDED", "Room created successfully.");
  define("ROOM_EXISTS", "Room already exists.");


?>

