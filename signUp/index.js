
$(document).ready(function(){
    
    $("#send_btn").on("click", function(e){

        var form_data = new FormData($("#signUp_form").get(0));

        var id = form_data.get('id');
        if(!validTest("id",id)) return false;

        var name = form_data.get('name');    
        if(!validTest("name",name)) return false;

        var phone = form_data.get('phone');
        if(!validTest("phone",phone)) return false;

        var email = form_data.get('email');
        if(!validTest("email",email)) return false;

        var password = form_data.get('password');
        if(!validTest("password",password)) return false;

        if(passwordNotMatch())
        {
            alert("兩次密碼輸入不相符");
            
            return false;
        }

        $.ajax({
            url: '/signUp/signUp.inc.php',
            type: "POST",
            processData: false, //important
            contentType: false, //important
            data: form_data,
            success: function(){

                alert("註冊成功");
                window.location.replace("http://localhost:4000/logIn");
            },
            error: function(response){
                alert(response.responseJSON.message);
            }
    })

       
    }); 

    $("#photo").on("change", function(e){
        console.log(e.target.files[0]);
        var reader = new FileReader();
        reader.onload =function (e) { 
            $("#edit_photo").attr("src", e.target.result);
        }

        reader.readAsDataURL(e.target.files[0]);
    });

});


// level 2 function
function validTest(field, value){
    switch(field){
        case "photo":
            //TODO:補充驗證機制
            break;

        case "id":
            if(isEmpty(value))
            {
                alert("請輸入ID");
                return false;
            }

            var regex = new RegExp("^[A-Za-z0-9\-\_]{1,25}$")
                  
            if(!regex.test(value))
            {
                alert("ID不符格式");
                return false;
            }
            
            return true;

        case "name":
            if(isEmpty(value))
            {
                alert("請輸入姓名");
                return false;
            }

            var regex = new RegExp("^[A-Za-z0-9\-\_]{1,10}")
            regex.test(value);
                        
            if(!regex.test(value))
            {
                alert("名稱不符格式");
                return false;
            }
            
            return true;

        case "phone":
            if(isEmpty(value))
            {
                alert("請輸入電話");
                return false;
            }

            var regex = new RegExp("^09[0-9]{8}$")
            
            if(!regex.test(value))
            {
                alert("電話不符格式");

                return false;
            }
            

            return true;


        case "email":
            if(isEmpty(value))
            {
                alert("請輸入電子信箱");
                return false;
            }

            var regex = new RegExp("^[a-zA-z0-9_]+@[a-zA-z0-9]+\.[a-zA-z0-9]+$")
            
            if(!regex.test(value)||value.length>50)
            {
                alert("信箱不符格式");
                return false;
            }
            
            return true;
            //TD
            break;

        case "password":
            if(isEmpty(value))
            {
                alert("請設定密碼");
                return false;
            }

            var regex = new RegExp("^[A-Za-z0-9\-\_]{1,50}$");

            if(!regex.test(value))
            {
                alert("密碼不符格式");
                return false;
            }
            return true;

    }
}


// level 1 function
function isEmpty(value){

    value = value.trim();
    return value.length===0;
}

function passwordNotMatch(){
    if($("#password").val()!=$("#password_again").val())
    {
        return true;
    }
    else
    {
        return false;
    }
}