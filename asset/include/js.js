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


$(window).on("load", function(){

    $("*").on("click", function(e){
        e.stopPropagation();

    
    })

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

})

//TODO:研究以html文字建立DOM物件，再用addClass/removeClass做變動

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

function load_Message_into_chat(){
    fetchMessage().forEach((element)=>{$(".message_area").append(make_text_to_DOM(element));})
}

function send_message(){
    $(".message_area").prepend(`<div class="sent message">
    <img class="user_img" src="../file/${user}.jpg" alt="photo">
    <div class="message_text">${$(".message_input_text").val()}</div>
    </div>`);
}