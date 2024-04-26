if (!(my_uid = sessionStorage.getItem("uid"))) {
  window.location.replace("http://localhost:4000/logIn");
}


let session_room_id = sessionStorage.getItem("room_id");
let session_min_message_id;
let conn;

$(document).ready(function () {
//#region main code
    
  online(my_uid);


  class Idle_timer {
    /*
      duration = -1 means user is active,
      duration >=0 which is idle duration.
  
      when duration reaching the maximum, the timer will report user is offline and stop counting.
      */
  
    constructor(my_uid) {
      this.duration = 0;
      this.max_duration = 12;
      this.my_uid = my_uid;
      this.timer;
    }
  
    static timer_algorithm(Idle_timer) {
      if (Idle_timer.duration == -1) {
        Idle_timer.duration = 0;
      } else if (Idle_timer.duration == Idle_timer.max_duration) {
        offline();
        clearInterval(Idle_timer.timer);
      } else {
        Idle_timer.duration++;
      }
    }
  
    keep_active() {
      if (this.duration == 12 ) {
        online(my_uid);
        this.start();
      }
      this.duration = -1;
      
    }
  
    start() {
      this.timer = setInterval(Idle_timer.timer_algorithm, 1000, this);
    }
  
    close() {
      clearInterval(this.timer);
    }
  
    act_listener_open() {
      var time = this;
      $(window).on("keydown mousedown mouseover scroll", function () {
        time.keep_active();
      });
    }
  
    act_listener_close() {
      var time = this;
      $(window).off("keydown mousedown mouseover scroll");
    }
  }
  let time = new Idle_timer(my_uid); 
  time.start(); 
  time.act_listener_open();


  if (session_room_id) {
    load_room(session_room_id);
  }

  //#endregion

  //#region listener

  $("#sideBar_contact").on("click", function(){
    $(".contact_list").attr("class", "contact_list users");
    load_contact_list();
  })

  $("#sideBar_message").on("click", function(){
    $(".contact_list").attr("class", "contact_list last_message_list")
    load_last_message_list();
  })

  $(".my_photo").attr("src", "file/" + my_uid + ".jpg");

  $(".user_img").on("error", function(e){
    this.src = "asset/include/default_user.jpg";
})

  $("#sideBar_contact").click();

  $("#logout_btn").on("click", function (e) {
    log_out();
  });

  //#endregion
});

//#region functions



// add "sent" class if it was sent by me
function isSentByMe(element) {
  var result = "";
  if (element[0] == my_uid) {
    result = "sent";
  }

  return result;
}

function create_text_DOM(element) {
  return `<div class="${isSentByMe(element)} message">
    <img class="user_img" src="../file/${element[0]}.jpg" alt="photo">
    <div class="message_text">${element[1]}</div>
    </div>`;
}

function load_contact_list() {
  $.get(
    "chatroom/fetch_contacts_from_DB.php?my_uid=" + my_uid,
    function (data) {
      var contacts = JSON.parse(data);
      display_contacts_list(contacts);
    }
  );
}

function load_last_message_list() {
  $.get(
    "chatroom/get_last_messages.php?my_uid=" + my_uid,
    function (data) {
      var last_messages = JSON.parse(data);
      display_last_messange_list(last_messages);
    }
  );
}


function display_contacts_list(data) {
  // clear_html(".contact_list");

  $(".contact_list").html("");

  data.forEach((contact_data) => {

    var contact_id = contact_data["id"];
    var name = contact_data["name"];
    var status =
      contact_data["status"] == "offline" ? "": `<div class="onlie_green_dot"></div>`;



    var contact = document.createElement("div");
    contact.innerHTML = `
          <div class="contact">
              <div class="position-relative mx-2">
              <img src="file/${contact_id}.jpg" alt="X" class="user_img">
              ${status}
              </div>
              <div>
                  <span class="chat_name">${contact_id}</span>
                  <span class="chat_last_message"></span>
              </div>
          </div>
        `;

    contact.addEventListener("click", () => {
      get_roomID(contact_id);
      sessionStorage.setItem("current_room_photo_id", contact_id);
      sessionStorage.setItem("current_room_photo_name", contact_id);
    });

    $(".contact_list").append(contact);

  });

  
}

function display_last_messange_list(data){

  $(".contact_list").html("");
  data.forEach((last_messages) => {
    var uid = last_messages["uid"];
    var room_id = last_messages["room_id"];
    var text = last_messages["text"];
    var datetime = last_messages["datetime"];
    var photo = last_messages["photo"];

    var last_message_DOM = document.createElement("div");
    last_message_DOM.innerHTML = `
        <div class="last_message">
                
                <img src="file/${photo}.jpg" alt="X" class="user_img">
               
                <div>
                    <span class="last_message_name">${uid}</span>
                    <span class="last_message_text">${text}</span>
                    <span class="last_message_time">${datetime}</span>
                </div>
            </div>
        `;

      last_message_DOM.addEventListener("click", () => {
        sessionStorage.setItem("current_room_photo_id", photo);
        sessionStorage.setItem("current_room_photo_name", uid);
        sessionStorage.setItem("room_id", room_id);
        load_room(room_id);
    });
    $(".contact_list img").on("error", function(e){
        this.src = "asset/include/default_user.jpg";
    })
    $(".contact_list").append(last_message_DOM);
  });
}

function load_room(session_room_id) {
  var opposite_uid = sessionStorage.getItem("current_room_photo_id");
  var name = sessionStorage.getItem("current_room_photo_name");

  var chat_html = `<div class="chat_header">
                      <div class="chat_header_chat_name">
                          <div  class="position-relative">
                              <img src="file/${opposite_uid}.jpg" alt="X" class="room_photo user_img">
                          </div>
                          <span>${name}</span>
                      </div>
                    </div>
                    <div class="message_area">
                      message_area
                    </div>
                    <div class="message_input">
                      <input type="text" class="message_input_text px-3" placeholder="say something...">
                      <button id="message_input_sendBtn" class="message_input_sendBtn">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send-fill" viewBox="0 0 16 16">
                              <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471z"/>
                            </svg>
                      </button>
                    </div>`;

  if (!$(".chat").is(":empty")) {
    $(".chat").html(chat_html);
  }

  if (!session_room_id) {
    return false;
  }

  $(".room_photo").on("error", function(e){
      this.src = "asset/include/default_user.jpg";
  })

  clear_html(".message_area");
  load_Message_into_chat(session_room_id);

  $(".message_area").on("scroll", function () {
    if (
      $(this).prop("scrollHeight") + $(this).scrollTop() - $(this).height() <
      65
    ) {
      if (session_min_message_id != "end") {
        load_history(session_room_id, session_min_message_id);
      }
    }
  });

  $(".message_input_sendBtn").click(function () {
    send_message();
    
    $(".message_input_text").val("");
    $(".message_area").scrollTop($(".message_area").prop("scrollHeight"));

    if($(".last_message").length>0){
      load_last_message_list();
    }
  });

  $(".message_input_text").on("keydown", function (e) {
    if (e.keyCode == "13") {
      $("#message_input_sendBtn").trigger("click");
    }
  });
}

function get_roomID(opposite_uid) {
  $.get(
    "chatroom/room.php?uid1=" + my_uid + "&uid2=" + opposite_uid,
    function (room_id) {
      //TODO: get回來的room_id後面會有額外的\n\n\n，暫用trim解決

      room_id = room_id.trim();
      session_room_id = room_id;
      sessionStorage.setItem("room_id", session_room_id);

      load_room(room_id);
    }
  );
}

function load_Message_into_chat(session_room_id) {
  $.get("chatroom/fetch_message_from_DB.php", {
    room_id: session_room_id,
  }).done(function (data) {
    data = JSON.parse(data);
    session_min_message_id = data["min_message_id"];

    if (session_min_message_id == null) {
      return false;
    }

    masseges = data["messages"];
    masseges.reverse();
    masseges.forEach((DB_msg) => {
      $(".message_area").append(create_text_DOM(DB_msg));
    });

    $(".message_area .user_img").on("error", function(e){
        this.src = "asset/include/default_user.jpg";
    })
  });
}

function load_history(session_room_id, min_message_id) {
  $.get("chatroom/fetch_message_from_DB.php", {
    room_id: session_room_id,
    min_message_id: min_message_id,
  }).done(function (data) {
    data = JSON.parse(data);
    if (!data) {
      session_min_message_id = "end";
    } else {
      session_min_message_id = data["min_message_id"];
      masseges = data["messages"];
      masseges.reverse();
      masseges.forEach((element) => {
        $(".message_area").append(create_text_DOM(element));
      });
    }
  });
}

function send_message() {
 
  var text = $(".message_input_text").val().trim();
  if(text.length==0)
    {return false;}
  

  var tzoffset = new Date().getTimezoneOffset() * 60000;
  var datetime = new Date(Date.now() - tzoffset)
    .toISOString()
    .slice(0, 19)
    .replace("T", " ");
  var session_room_id = sessionStorage.getItem("room_id");

  // DOM
  $(".message_area").prepend(
    `<div class="sent message">
    <img class="user_img" src="../file/` +
      my_uid +
      `.jpg" alt="photo">
    <div class="message_text">${text}</div>
    </div>`
  );

  $(".message_input_text").val("");
  $(".message_area").scrollTop($(".message_area").prop("scrollHeight"));

  //Database

  $.post("chatroom/sendMessage.php", { text: text, sentbyuid: my_uid, room_id: session_room_id});
  
  conn.send(
    `{"action":"send_message", "uid":"` +
      my_uid +
      `" , "room_id":"` +
      session_room_id +
      `"}`
  );
}

function online(my_uid) {

  conn = new WebSocket("ws://localhost:8080");
  
  conn.onopen = function (e) {
    conn.send(`{"action":"connect", "uid":"` + my_uid + `"}`);
  };

  conn.onmessage = function (e) {
    var data = JSON.parse(e.data);
    switch (data["action"]) {
      case "status":
        //聯絡人清單搜尋id
        if($(".users").length>0){
          $(".contact_list").html("");
          load_contact_list();
        }
        break;

      case "receive_message":
        var room_id = data["room_id"];

        if ((room_id = sessionStorage.getItem("room_id"))) {
          load_room(room_id);
        }

        if($(".last_message_list").length>0){
          load_last_message_list();
        }
        
        break;
    }
  };

  conn.onclose = function(e){

  }


  
}

function offline() {
  conn.close();
}

function log_out() {
  sessionStorage.clear();
  offline();

  //TODO:解決跳轉會先執行的問題
  $.post("logOut").done(
    setTimeout(()=>{window.location.replace("http://localhost:4000/logIn")},1000)
  );
}

function clear_html(element_tag) {
  $(element_tag).html("");
}

//#endregion

/*筆記區

將全域變數傳入 setInterval 
setInterval((time)=>{time.close()}, 3000, time);

*/

/*棄用區

   var refresh_contacts= setInterval(()=>{
        $(".contact_list").html("");
        fetch_contacts_from_DB();
    }, 600000);
*/

/*測試區

  //idle_timer 測試按鈕
  $("#start").on("click", function () {
    time.start();
  });
  $("#close").on("click", function () {
    time.close();
  });
  $("#act_start").on("click", function () {
    time.act_listener_open(1);
  });
  $("#act_close").on("click", function () {
    time.act_listener_close(1);
  });
*/