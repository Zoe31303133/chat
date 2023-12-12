/*input field:
1. photo
2. id
3. password
4. password_again
*/

$(document).ready(function(){
    
    $("#send").on("click", function(e){
        e.stopPropagation();
 
        $isValid=true;

        if(validTest("id")==true)
        {
            if(validTest("password")==true)
            {
                $.ajax("logIn/logIn.inc.php",{
                    type: "POST",
                    datatype: "json",
                    data: 
                    {
                            uid: $("#id").val(),
                            password: $("#password").val()
                    },
                    success: function(response)
                    {
                            switch(response){
                                case "no_user":
                                    alert("無此帳號");
                                    break;
                                case "wrong_password":
                                    alert("密碼錯誤！")
                                    break;
                                case "login_success":
                                    alert("登入成功！")
                                    sessionStorage.setItem("uid", $("#id").val());
                                    window.location.replace("http://localhost:4000/chatroom");
                                    break;
                            }
                    }
                })    
            }
        }
    }); 
});

// level 2 function
function validTest(field){
    switch(field){
        case "id":
            //TODO:補充驗證機制
            if(isEmpty("id"))
            {return false;}
            console.log("id");
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
