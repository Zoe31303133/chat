if(!($my_uid = sessionStorage.getItem("uid")))
{
    window.location.replace("http://localhost:4000/logIn");
};

let session_room_id = sessionStorage.getItem('room_id');
let conn = new WebSocket('ws://localhost:8080');
let session_min_message_id;


$(document).ready(function(){

//#region main code
    
    online($my_uid);
    if(session_room_id){
        load_room(session_room_id);
    }

    let time = new Idle_timer();

    $('.my_photo').attr("src", "file/"+$my_uid+".jpg");
    //idle_timer 測試按鈕 
    $("#start").on("click",function(){time.start();});
    $("#close").on("click",function(){time.close();});
    $("#act_start").on("click",function(){time.act_listener_open(1);});
    $("#act_close").on("click",function(){time.act_listener_close(1);});

//#endregion



//#region listener

    $("#logout_btn").on("click", function(e){
        log_out();
        sessionStorage.clear();
    })


//#endregion

})


//#region functions

class Idle_timer{

    /*
    duration = -1 means user is active,
    duration >=0 which is idle duration.

    when duration reaching the maximum, the timer will report user is offline and stop counting.
    */

    constructor() {
        this.duration = 0;
        this.max_duration = 15;
        this.timer;
        }

    static timer_algorithm(Idle_timer){

        if(Idle_timer.duration == -1)
        {
            Idle_timer.duration = 0;
        }
        else if(Idle_timer.duration==Idle_timer.max_duration)
        {
            offline();
            clearInterval(Idle_timer.timer);
        }
        else
        {
            Idle_timer.duration++;
        }

        }

    keep_active(){

        if(this.duration==6)
        {
            online(this.uid);
            console.log('上線');
        }

        this.duration = -1;
        // console.log("s");
        }

    start() {
        this.timer = setInterval(Idle_timer.timer_algorithm, 1000, this);
        }

    close(){
        clearInterval(this.timer);
        }

    act_listener_open(){
        var time = this;
        $(window).on('keydown mousedown mouseover scroll', function(){            
            time.keep_active();});
        }

    act_listener_close(){
        var time = this;
        $(window).off('keydown mousedown mouseover scroll');
        }      
}

// add "sent" class if it was sent by me
function isSentByMe(element){
    var result = "";
    if(element[0]==$my_uid)
    {
        result = "sent";
    }
    
    return result;
}

function create_text_DOM(element){

    return `<div class="${isSentByMe(element)} message">
    <img class="user_img" src="../file/${element[0]}.jpg" alt="photo">
    <div class="message_text">${element[1]}</div>
    </div>`
}

function load_contact_list()
{   
    $.get( "chatroom/fetch_contacts_from_DB.php?my_uid="+$my_uid, function(data) {
        var contacts = JSON.parse(data);
        // TEST
        console.dir(contacts);
        //
        display_contacts_list(contacts);
  });
}

function display_contacts_list(data){
    // clear_html(".contact_list");
    data.forEach((contact_data)=>{
        var contact_id = contact_data['id'];
        var name = contact_data['name'];
        var status = contact_data['status']=="offline"?"":`<div class="onlie_green_dot"></div>`

        var contact = document.createElement('div');
        contact.innerHTML = `
        <div class="contact">
        <div class="position-relative mx-2">
                <img src="file/${contact_id}.jpg" alt="X" class="user_img">
                ${status}
                </div>
                <div>
                    <span class="chat_name">${name}</span>
                    <span class="chat_last_message"></span>
                </div>
            </div>
        `;

        contact.addEventListener('click', ()=>{
            get_roomID(contact_id);
            sessionStorage.setItem("current_room_photo_id", contact_id);
            sessionStorage.setItem("current_room_photo_name", name);
            
        });


        var contact_list = document.getElementsByClassName("contact_list");
        contact_list[0].appendChild(contact);
    })
}

function load_room(session_room_id,opposite_uid,name){
    
    var opposite_uid =  sessionStorage.getItem("current_room_photo_id");
    var name =  sessionStorage.getItem("current_room_photo_name");

    if(!$('.chat').is(':empty')){
        $('.chat').html(`<div class="chat_header">
        <div class="chat_header_chat_name">
            <div  class="position-relative">
                <img src="file/${opposite_uid}.jpg" alt="X" class="room_photo user_img">
                <div class="onlie_green_dot"></div>
            </div>
            <span>${name}</span>
        </div>

        <div class="chat_header_icon_wrap">
            <button class="chat_header_icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
                <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/>
              </svg>
            </button>
            <button class="chat_header_icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-camera-video-fill" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M0 5a2 2 0 0 1 2-2h7.5a2 2 0 0 1 1.983 1.738l3.11-1.382A1 1 0 0 1 16 4.269v7.462a1 1 0 0 1-1.406.913l-3.111-1.382A2 2 0 0 1 9.5 13H2a2 2 0 0 1-2-2z"/>
                  </svg>
            </button>
            <button class="chat_header_icon toggle" >
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                </svg>
            </button>    
        </div>
    </div>
    <div class="message_area">
        message_area
    </div>
    <div class="message_input">
        <div class="message_input_upload">
            <div class="message_input_upload_icon" >
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-plus" viewBox="0 0 16 16">
                    <path d="M8 6.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V11a.5.5 0 0 1-1 0V9.5H6a.5.5 0 0 1 0-1h1.5V7a.5.5 0 0 1 .5-.5"/>
                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5z"/>
                  </svg>
            </div>
            <div class="message_input_upload_icon" >
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-image-fill" viewBox="0 0 16 16">
                    <path d="M.002 3a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-12a2 2 0 0 1-2-2zm1 9v1a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V9.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062zm5-6.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0"/>
                  </svg>
            </div>
        </div>
        <input type="text" class="message_input_text" placeholder="say something...">
        <button id="message_input_sendBtn" class="message_input_sendBtn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send-fill" viewBox="0 0 16 16">
                <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471z"/>
              </svg>
        </button>
    </div>`);
    }


    if(!session_room_id)
    {return false;}

    clear_html(".message_area");
    load_Message_into_chat(session_room_id);

    $(".message_area").on("scroll", function(){


        if($(this).prop("scrollHeight")+$(this).scrollTop()-$(this).height()<65)
        {   
            if(session_min_message_id != "end")
            {
                load_history(session_room_id, session_min_message_id);
            }
            else
            {
                //TEST
                alert("到頂了！");
            }
            
        }}
    )

    $(".message_input_sendBtn").click(function(){
        send_message();
        $(".message_input_text").val("");
        $(".message_area").scrollTop($(".message_area").prop("scrollHeight"));
    })

    $(".message_input_text").on("keydown",function(e){
        console.log(e);
        if(e.keyCode=="13")
        {
            $("#message_input_sendBtn").trigger("click");
        }
    })



    }

function get_roomID(opposite_uid){

    $.get("chatroom/room.php?uid1="+$my_uid+"&uid2="+opposite_uid,  function(room_id) {
    
        //TODO: get回來的room_id後面會有額外的\n\n\n，暫用trim解決

        room_id = room_id.trim();
        session_room_id = room_id;
        sessionStorage.setItem("room_id", session_room_id);



        load_room(room_id);

    })
}


function load_Message_into_chat(session_room_id){
    $.get("chatroom/fetch_message_from_DB.php", { room_id: session_room_id})
    .done(function( data ) {
        
        data=JSON.parse(data);
        session_min_message_id = data['min_message_id'];

        if(session_min_message_id==null){
            return false;
        }

        masseges = data['messages'];
        masseges.reverse();
        masseges.forEach((element)=>{$(".message_area").append(create_text_DOM(element));} );
    });
    
    }

function load_history(session_room_id, min_message_id)
{    $.get("chatroom/fetch_message_from_DB.php", { room_id: session_room_id, min_message_id: min_message_id})
        .done(function( data ) {
            data=JSON.parse(data);
            console.log(data);
            if(!data)
                {
                    session_min_message_id = "end";
                }
                else
                {
                    session_min_message_id = data['min_message_id'];
                    masseges = data['messages'];
                    masseges.reverse();
                    masseges.forEach((element)=>{$(".message_area").append(create_text_DOM(element));} );
                }
        
        });
}

function send_message(){

    var text = $(".message_input_text").val();
    var tzoffset = (new Date()).getTimezoneOffset() * 60000;
    var datetime = new Date(Date.now()-tzoffset).toISOString().slice(0, 19).replace('T', ' ');
    var session_room_id = sessionStorage.getItem("room_id"); 

    // DOM
    $(".message_area").prepend(`<div class="sent message">
    <img class="user_img" src="../file/` + $my_uid + `.jpg" alt="photo">
    <div class="message_text">${text}</div>
    </div>`);

    $(".message_input_text").val("");
    $(".message_area").scrollTop($(".message_area").prop("scrollHeight"));

    //Database
    sql = `insert into messages (text, sentbyuid, datetime, room_id) values ("${text}" , "${$my_uid}" , "${datetime}", "${session_room_id}");`  

    $.post( "chatroom/sendMessage.php", { sql: sql} );
    conn.send(`{"action":"send_message", "uid":"`+ $my_uid+`" , "room_id":"`+ session_room_id+`"}`);
}


function online($my_uid){
    conn.onopen = function(e) {
        console.log("Connection established!");
        conn.send(`{"action":"connect", "uid":"`+ $my_uid+`"}`);

        conn.onmessage = function(e){

            var data = JSON.parse(e.data);
            switch(data['action'])
            {
                case "status":
                    //聯絡人清單搜尋id
                    $(".contact_list").html("");
                    load_contact_list();
                    break;

                case "receive_message":
                    var room_id = data['room_id'];
                    
                    if(room_id = sessionStorage.getItem("room_id"))
                    {load_room(room_id);}
                    break;
            }
        }
            
        }
}

function offline(){
    conn.close();
}

function log_out(){
    $.post("logOut")
    .done(
        window.location.replace("http://localhost:4000/logIn")
      )
}

function clear_html(element_tag)
{
    $(element_tag).html("");}


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