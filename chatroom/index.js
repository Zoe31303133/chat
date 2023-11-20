const user = "01"; //本人

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
    
    fetch_contacts_from_DB();


    $("#logOut_btn").on("click", function(e){
        e.stopPropagation();
        alert("S");
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
    if(element[0]==user)
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
    $.ajax("chatroom/fetch_contacts_from_DB.php",{
    type:"POST",
    datatype: "json",
    data:{},
    success: function($data)
    {
        $data = JSON.parse($data);
        display_contacts_list($data);
    }
})

}

function display_contacts_list($data){
    $data.forEach((contact)=>{
        $(".contact_list").append(`
        <div class="contact">${contact['name']}</div>
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
    <img class="user_img" src="../file/${user}.jpg" alt="photo">
    <div class="message_text">${$(".message_input_text").val()}</div>
    </div>`);
}