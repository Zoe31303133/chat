
let = $my_uid ="";
if(!($my_uid = sessionStorage.getItem("uid")))
{
    window.location.replace("http://localhost:4000/logIn");
};

let session_room_id = sessionStorage.getItem('room_id');
let conn = new WebSocket('ws://localhost:8080');

$(document).ready(function(){

//#region main code
    
    online($my_uid);
    load_room(session_room_id);
    // Initialize

    $(".contact_list").html("");
    fetch_contacts_from_DB();
    
    // load_contacts();
    load_Message_into_chat();


    let time = new Idle_timer();

    //idle_timer 測試按鈕 
    $("#start").on("click",function(){time.start();});
    $("#close").on("click",function(){time.close();});
    $("#act_start").on("click",function(){time.act_listener_open(1);});
    $("#act_close").on("click",function(){time.act_listener_close(1);});


//#endregion


//#region listener


    $("#logOut_btn").on("click", function(e){
        e.stopPropagation();
        $.ajax("logOut",{
            type: "POST",
            datatype: "json",
            data: {},
            success: function(){
                window.location.replace("http://localhost:4000/logIn");
            }
        })
    })

    $("*").on("click", function(e){
        e.stopPropagation();    
    })

    $(".message_area").on("scroll", function(){
        if($(this).prop("scrollHeight")+$(this).scrollTop()-$(this).height()<1)
        {   
            load_Message_into_chat();
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

function fetchMessage(){
    return chat_history.slice().reverse()
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

function fetch_contacts_from_DB()
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
        var status = contact_data['status']=="offline"?"":"online"

        var contact = document.createElement('div');
        contact.innerHTML = `
        <div class="contact"  href="${contact_id}">
                <div> ${name}</div>
                <div class="status ${status}">  
            </div>
        </div>`;

        contact.addEventListener('click', ()=>{
            get_roomID(contact_id);
        });


        var contact_list = document.getElementsByClassName("contact_list");
        contact_list[0].appendChild(contact);
    })
   
}


function get_roomID(opposite_uid){

    $.get("chatroom/room.php?uid1="+$my_uid+"&uid2="+opposite_uid,  function(room_id) {
    
        //TODO: get回來的room_id後面會有額外的\n\n\n，暫用trim解決

        room_id = room_id.trim();
        sessionStorage.setItem("room_id", room_id);
        load_room(room_id);
    })
}


function load_room(session_room_id){
        
    if(!session_room_id)
    {return false;}

    clear_html(".message_area");
    load_Message_into_chat(session_room_id);

    }

function load_Message_into_chat(session_room_id){
    $.get("chatroom/fetch_message_from_DB.php", { room_id: session_room_id})
    .done(function( data ) {
        data=JSON.parse(data);
        data.reverse();
        data.forEach((element)=>{$(".message_area").append(create_text_DOM(element));} );
      });
    
    // .forEach((element)=>{$(".message_area").append(create_text_DOM(element));})
}

function send_message(){
    $(".message_area").prepend(`<div class="sent message">
    <img class="user_img" src="../file/` + $my_uid + `.jpg" alt="photo">
    <div class="message_text">${$(".message_input_text").val()}</div>
    </div>`);
}

function online($my_uid){
    conn = new WebSocket('ws://localhost:8080');
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
                    fetch_contacts_from_DB();
                    break;
            }
        }
    };
}

function offline(){
    conn.close();
}

function clear_html(element_tag)
{
    $(element_tag).html("");}


//#endregion