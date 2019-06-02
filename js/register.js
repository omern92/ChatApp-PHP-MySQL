import { check_Input, fetchSendData } from "./handler.js";

check_Input('username_register', 'register_btn');
check_Input('password_register', 'register_btn');

document.getElementById('register_btn').onclick = () => {
  fetchSendData('username_register', 'password_register', 'register', 'login.php');
};