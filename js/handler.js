// Validate the inputs fields.
function check_Input(input_id, submit_btn_id) {
    var input_element = document.getElementById(input_id);
    var submit_element = document.getElementById(submit_btn_id);
    if (input_element.value.length == 0)
        submit_element.disabled = true;
    input_element.onkeyup = () => {
        if (input_element.value.length > 0)
             submit_element.disabled = false;
        else
            submit_element.disabled = true;
    };
}


async function fetchSendData(username_input, password_input, action, pageToRedirect) {
    var user_name = document.getElementById(username_input).value;
    var password = document.getElementById(password_input).value;
    var data = new FormData();
    data.append('action', action);
    data.append('user_name', user_name);
    data.append('password', password);
    try {
        var response = await fetch('/api/auth.php', {
        method: 'post',
        body: data
        });
        var json = await response.json();
        if (json.result) {
          alert(json.message);
          window.location.replace(pageToRedirect);
        } else if (!json.result) {
          document.getElementById('error').innerHTML = json.message;
        }
      } catch(err) {
        document.getElementById('error').innerHTML = err;
      }
}

export { check_Input, fetchSendData };

