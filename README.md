# Chat App PHP MySQL JavaScript
Chat application with long polling using PHP, MySQL and JavaScript.<br>
Includes: Channels, authentication, file uploads and security issues that were taken into consideration, RESTful API, OOP, use of ES7.<br><br>
// <b>functions.php</b><br>

I used <b>UserMapper</b> to distinguish between database operations and the User class itself.<br>
With a Mapper, the in-memory objects needn't know even that there's a database present;<br>
they need no SQL interface code, and certainly no knowledge of the database schema.<br>

Another design pattern that was taken into consideration was the Dependency injection. It is important to keep the classes independent from each other.<br>
For example, by passing the <b>instance</b> of the DB class to the UserMapper class, I was able to make the UserMapper totally independent from changes in the DB class.<br>


