document.addEventListener('DOMContentLoaded', () => {
    check_new_messages();
    get_rooms();

    // Create a new room.
    check_Input('create_room', 'create_room_btn');
    document.querySelector('#create_room_btn').onclick = () => {
        var room = document.querySelector('#create_room').value;
        // Initializes the input
        document.querySelector('#create_room').value = '';
        fetchSendData('api/rooms.php', room, 'addRoom', 'error');
        get_rooms();
      };

    // Add a new message.
    check_Input('message', 'submit');
    document.querySelector('#submit').onclick = () => {
        var text = document.querySelector('#message').value;
        // Initializes the input
        document.querySelector('#message').value = '';
        const response = fetchSendData('api/messages.php', text, 'addMessage', 'error');
        if (response) get_messages();
        scrollToBottom();
      };

    // Upload a file.
    document.querySelector('#submit_file').onclick = () => {
        var fileInput = document.querySelector('#upload_file');
        var file = fileInput.files[0];
        fetchSendData('api/messages.php', file, 'addFile', 'error');
        disappearing_title('loading_file', 'Uploading...', '');
        scrollToBottom();
      };

});
// Outside the DOM callback function.
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

// Change the user's room.
function changeRoom(room_id, room_name) {
    fetchSendData('api/rooms.php', room_id, 'changeRoom', 'error');
    document.getElementById("room_display").innerHTML = room_name;
    get_rooms();
    scrollToBottom();
    return false;
}

// Fetch functions
async function fetchRequestData(pageToSend) {
  try {
      var response = await fetch(pageToSend);
      var text = await response.text();
      return text;
  } catch(err) {
      return err;
  }
}
// POST.
async function fetchSendData(pageToSend, dataToSend, action, elementId) {
  var data = new FormData();
  data.append('action', action);
  data.append('data', dataToSend);
  try {
      var response = await fetch(pageToSend, {
      method: 'post',
      body: data
      });
      var json = await response.json();
      document.getElementById(elementId).innerHTML = json.message;
      return true;

  } catch(err) {
      document.getElementById(elementId).innerHTML = err;
  }
}

function scrollToBottom() {
    var chatDiv = document.getElementById("chat");
    chatDiv.scrollTop = chatDiv.scrollHeight;
}

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

async function disappearing_title(elementId, textBefore, textAfter) {
  document.getElementById(elementId).innerHTML = textBefore;
  await sleep(5000);
  document.getElementById(elementId).innerHTML = textAfter;
}

// Check for new messages.
async function check_new_messages() {
  var data = new FormData();
  data.append('action', "checkMessages");
  try {
      var response = await fetch('api/messages.php', {
      method: 'post',
      body: data
      });
      var json = await response.json();
      if (json.result) {
        get_messages();
        check_new_messages();
      }

  } catch(err) {
    console.log(err);
    document.getElementById('chat').innerHTML += err;
    check_new_messages();
  }
}

async function get_messages() {
    var data = new FormData();
    data.append('action', "getMessages");
    try {
        var response = await fetch('api/messages.php', {
        method: 'post',
        body: data
        });
        var json = await response.json();
        if (json.result) {
          document.getElementById("messages").innerHTML = "";
          document.getElementById("error").innerHTML = "";

          for (var mes in json.message) {
            var TR = document.createElement("tr");
            document.getElementById("messages").appendChild(TR);

            var details = {
                time      : json.message[mes]['time'],
                user_name : json.message[mes]['user_name'],
                text      : json.message[mes]['text']
                };

            for (const [key, value] of Object.entries(details)) {
              var TD = document.createElement("td");
              TD.innerHTML = value;
              TR.appendChild(TD);
              console.log(TR);
            }
          }

        } else {
          document.getElementById('chat').innerHTML += json.message;
        }
    } catch(err) {
          document.getElementById('chat').innerHTML += err;
    }
}

async function get_rooms() {
      var data = new FormData();
      data.append('action', "getRooms");
      try {
          var response = await fetch('api/rooms.php', {
          method: 'post',
          body: data
          });
          var json = await response.json();
          if (json.result) {
            document.getElementById("rooms_ul").innerHTML = "";
            for (let room in json.message) {
                let room_id      = json.message[room]['room_id'];
                let room_name    = json.message[room]['room_name'];

                let li = document.createElement("li");
                let link = document.createElement("a");
                link.href = "#";
                link.className = "list-group-item list-group-item-action";
                link.onclick = function (){ changeRoom(room_id, room_name); };
                link.id = room_id;
                link.innerHTML = room_name;

                document.getElementById("rooms_ul").appendChild(li);
                li.appendChild(link);
            }
          } else {
            document.getElementById('error').innerHTML += json.message;
          }
      } catch(err) {
          document.getElementById('error').innerHTML += err;
      }
}

async function fetchIndex() {
  let data = new FormData();
  data.append('action', 'index');
  try {
      let response = await fetch('api/auth.php', {
      method: 'post',
      body: data
      });
      let json = await response.json();
      if (json.error) {
        document.getElementById('error').innerHTML = json.message;
      } else if (!json.error) {
        document.getElementById('user_name').innerHTML = json.user_name;
        document.getElementById('room_display').innerHTML = json.room;
      }
    } catch(err) {
      document.getElementById('error').innerHTML = err;
    }
}


