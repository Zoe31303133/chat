/*input field:
1. photo
2. id
3. password
4. password_again
*/

$(document).ready(function(){
    
    $("#send_btn").on("click", function(e){
        e.stopPropagation();

        var uid_input = $("#id").val().trim();
        var password_input = $("#password").val().trim();

        if(validTest("id", uid_input)==true)
        {
            if(validTest("password", password_input)==true)
            {
                $.ajax("logIn/logIn.inc.php",{
                    type: "POST",
                    datatype: "json",
                    data: 
                    {
                            uid: uid_input,
                            password: password_input
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
                                    sessionStorage.setItem("uid", uid_input);
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
function validTest(field, input){
    
    switch(field){
        
        case "id":

            if(input.length===0)
            {
                alert("請輸入ID");
                return false;
            }

            var re = new RegExp("^[A-Za-z0-9\-\_]{1,25}$");
            if(!re.test(input)){
                alert("ID輸入不符合格式: 大小寫英文字母、數字、底線(_)與橫線(-)")
                return false
            }
            break;

        case "password":
            
            if(input.length===0)
            {
                alert("請輸入密碼");
                return false;
            }

            var re = new RegExp("^[A-Za-z0-9\-\_]{1,50}$");
            if(!re.test(input)){
                alert("密碼輸入不符合格式: 大小寫英文字母、數字、底線(_)與橫線(-)")
                return false
            }

            return true;
            break;
    }

    return true;
}

