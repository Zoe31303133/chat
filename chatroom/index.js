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

    $uid = sessionStorage.getItem("uid");
    var conn = WebSocket;
    online($uid);


    // Initialize

    $(".contact_list").html("");
    fetch_contacts_from_DB();
    
    load_contacts();
    load_Message_into_chat();

//#endregion


//#region listener

    /* idle_time
        positive numer: idle duration
        0 : into idle mode
        -1 : user acting 
    */
    var idle_time = 0;
    var listen_idle = setInterval(()=>{
        // console.log(idle_time);
        if(idle_time<0)
        {idle_time = 0;}
        else if(idle_time==60){
            offline();
            clearInterval(listen_idle);
        }
        else{
            idle_time++;
        }
    }, 1000);  

    var refresh_contacts= setInterval(()=>{
        $(".contact_list").html("");
        fetch_contacts_from_DB();
        
    }, 10000);
    
    $(window).on('keydown mousedown mouseover scroll', function(e){
        // console.log(idle_time);


        if(idle_time==-1)
        {return true}
        
        if(idle_time==6)
        {

            online($uid);

            console.log('更新上線狀態至database');
            listen_idle = setInterval(()=>{
                // console.log(idle_time);
                if(idle_time<0)
                {idle_time = 0;}
                else if(idle_time==6){
                    offline();
                    clearInterval(listen_idle);
                }
                else{
                    idle_time++;
                }
            }, 1000);
        }

        idle_time = -1;

    })

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

function fetchMessage(){
    return chat_history.slice().reverse()
}

// add "sent" class if it was sent by me
function isSentByMe(element){
    var result = "";
    if(element[0]==$uid)
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
    $.get( "chatroom/fetch_contacts_from_DB.php", function(data) {
        var contacts = JSON.parse(data);
        console.dir(contacts);
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
    <img class="user_img" src="../file/` + $uid + `.jpg" alt="photo">
    <div class="message_text">${$(".message_input_text").val()}</div>
    </div>`);
}

function online($uid){
    conn = new WebSocket('ws://localhost:8080');
    conn.onopen = function(e) {
        console.log("Connection established!");
        conn.send(`{"action":"connect", "uid":"`+ $uid+`"}`);

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