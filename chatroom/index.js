
let = $my_uid ="";
if(!($my_uid = sessionStorage.getItem("uid")))
{
    window.location.replace("http://localhost:4000/logIn");
};

let conn = new WebSocket('ws://localhost:8080');

// message db example
const chat_history = [
    ["1", "message"],
    ["2", "message"],
    ["1", "message"],
    ["2", "message"],
    ["2", "message"],
    ["1", "message"],
    ["2", "message"],
    ["2", "message"]
]

$(document).ready(function(){

//#region main code
    
    online($my_uid);

    let time = new Idle_timer();

    //idle_timer 測試按鈕 
    $("#start").on("click",function(){time.start();});
    $("#close").on("click",function(){time.close();});
    $("#act_start").on("click",function(){time.act_listener_open(1);});
    $("#act_close").on("click",function(){time.act_listener_close(1);});

    // Initialize

    $(".contact_list").html("");
    fetch_contacts_from_DB();
    
    // load_contacts();
    load_Message_into_chat();

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

function make_text_to_DOM(element){

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

function display_contacts_list($data){
    $data.forEach((contact)=>{
        var id = contact['id'];
        var name = contact['name'];
        var status = contact['status']=="offline"?"":"online"

        $(".contact_list").append(`
        <div class="contact"  href="${id}">
            <div> ${name}</div>
            <div class="status ${status}">  
        </div>
    </div>
        `)
    })
}

function load_Message_into_chat(){
    fetchMessage().forEach((element)=>{$(".message_area").append(make_text_to_DOM(element));})
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

//#endregion