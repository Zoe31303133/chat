/*input field:
1. photo
2. name
3. password
4. password_again
*/

$(document).ready(function(){
    
    $("#send").on("click", function(e){
        e.stopPropagation();
 
        $isValid=true;

        $isValid=validTest("name");
        $isValid=validTest("password");
        
       if($isValid==true)
       {
            $.ajax("logIn/logIn.inc.php",{
                type: "POST",
                datatype: "json",
                data: {
                    name: $("#name").val(),
                    password: $("#password").val()
                },
                success: function(data){
                    switch(data){
                        case "wrong_password":
                            alert("密碼錯誤！")
                            break;
                        case "login_success":
                            alert("登入成功！")
                            window.location.replace("http://localhost:4000/chatroom");
                            break;
                    }
                }
            })    
        }
    }); 
});

// level 2 function
function validTest(field){
    switch(field){
        case "name":
            //TODO:補充驗證機制
            if(isEmpty("name"))
            {return false;}
            console.log("name");
            return true;
            //TD
            break;

        case "password":
            //TODO:補充驗證機制
            if(isEmpty("password"))
            {return false;}
            console.log("password");
            return true;
            //TD
            break;
    }
}


// level 1 function
function isEmpty(field){

    if($("#"+field).val()=="")
    {
        alert(field+"空白");
        return true
    }
    return false;
}
