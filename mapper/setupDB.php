<!DOCTYPE html>
<html>
<head>
    <title>Setup- DB Initializer</title>
</head>
<body>
    <h1>Setup</h1>

<?php
    require_once './functions.php';

    createTable('users',
                'username VARCHAR(30) UNIQUE,
                 password VARCHAR(255),
                 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                 room INT UNSIGNED DEFAULT "1",
                 FOREIGN KEY (room) REFERENCES rooms(id) ON UPDATE CASCADE');
    createTable('messages',
                'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(30),
                time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                text TEXT,
                room INT UNSIGNED,
                FOREIGN KEY (room) REFERENCES rooms(id) ON UPDATE CASCADE,
                FOREIGN KEY (username) REFERENCES users(username)');
    createTable('rooms',
                'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(30)');
    createTable('files',
                'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(30),
                time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                original_name VARCHAR(100),
                gen_name VARCHAR (20),
                size INT UNSIGNED,
                ext VARCHAR(10),
                FOREIGN KEY (username) REFERENCES users(username)');

?>

</body>
</html>
