import { check_Input, fetchSendData } from "./handler.js";

// LOGIN:
  check_Input('username_login', 'login_btn');
  check_Input('password_login', 'login_btn');

async function fetchRequestLogin() {
var data = new FormData();
data.append('action', 'login_check');
try {
    var response = await fetch('/api/auth.php', {
    method: 'post',
    body: data
    });
    var json = await response.json();
    if (json.logged_in) {
      alert("You are logged in already.");
      window.location.replace("index.php");
    }
  } catch(err) {
      alert(err);
      window.location.replace("index.php");
  }
}

document.getElementById('login_btn').onclick = () => {
  fetchSendData('username_login', 'password_login', 'login', 'index.php');
};

export { fetchRequestLogin };