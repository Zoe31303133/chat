

const chat_history = [
    ["01", "message"],
    ["02", "message"],
    ["01", "message"],
    ["02", "message"],
    ["02", "message"],
    ["01", "message"],
    ["02", "message"],
    ["02", "message"]
]

$(document).ready(function(){

    $uid = sessionStorage.getItem("uid");

    var conn = new WebSocket('ws://localhost:8080');
    conn.onopen = function(e) {
        console.log("Connection established!");
        conn.send(`{"action":"connect", "uid":"`+ $uid+`"}`);
    };

    conn.onmessage = function(e){
      alert(e.data);
    }

    $.get("chatroom/update_online_status.php",{status:"online"})
    
    var idle_time = 0;

    /* idle_time
        positive numer: idle duration
        0 : into idle mode
        -1 : user acting 
    
    */

    var listen_idle = setInterval(()=>{
        console.log(idle_time);
        if(idle_time<0)
        {idle_time = 0;}
        else if(idle_time==6){
            $.get("chatroom/update_online_status.php",{status:"offline"})
            clearInterval(listen_idle);
        }
        else{
            idle_time++;
        }
    }, 1000);
    

    $(window).on('keydown mousedown mouseover scroll', function(e){
        console.log(idle_time);


        if(idle_time==-1)
        {return true}
        
        if(idle_time==6)
        {

            $.get("chatroom/update_online_status.php",{status:"online"});


            console.log('更新上線狀態至database');
            listen_idle = setInterval(()=>{
                console.log(idle_time);
                if(idle_time<0)
                {idle_time = 0;}
                else if(idle_time==6){
                    $.get("chatroom/update_online_status.php",{status:"offline"})
                    clearInterval(listen_idle);
                }
                else{
                    idle_time++;
                }
            }, 1000);
        }

        idle_time = -1;

    })

    fetch_contacts_from_DB();
   

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

    // Initialize
    load_contacts();
    load_Message_into_chat();

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

})


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
        $(".contact_list").append(`
        <div class="contact">
            <div> ${contact['name']}</div>
            <div class="status offline">  </div>
    </div>
        `)
    })



}

function load_contacts($data){

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