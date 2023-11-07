const message = [
`<div class="received message">
<div>photo</div>
<div class="message_text">message0</div>
</div>`
,`<div class="received message">
<div>photo</div>
<div class="message_text">message1</div>
</div>`,
`<div class="received message">
<div>photo</div>
<div class="message_text">message2</div>
</div>`,
`<div class="sent message">
<div>photo</div>
<div class="message_text">message3</div>
</div>`,
`<div class="sent message">
<div>photo</div>
<div class="message_text">message4</div>
</div>`,
`<div class="received message">
<div>photo</div>
<div class="message_text">message5</div>
</div>`,
`<div class="received message">
<div>photo</div>
<div class="message_text">message6</div>
</div>`,
`<div class="sent message">
<div>photo</div>
<div class="message_text">message7</div>
</div>`,
`<div class="received message">
<div>photo</div>
<div class="message_text">message8</div>
</div>`]

$(window).on("load", function(){

    load_Message_into_chat();

    $(".message_area").on("scroll", function(){
        if($(this).prop("scrollHeight")+$(this).scrollTop()-$(this).height()<1)
        {   
            load_Message_into_chat();
        }}
    )

    $(".message_input").click(function(){
        load_Message_into_chat();
    })

})

function fetchMessage(){
    return message.slice().reverse()
}

function load_Message_into_chat(){
    fetchMessage().forEach((element)=>{$(".message_area").append(element);})
}
