/*input field:
1. photo
2. name
3. password
4. password_again
*/

$(document).ready(function(){
    
    $("#send_btn").on("click", function(e){
        e.stopPropagation();
 
        $isValid=true;
        
        $isValid=validTest("name");
        $isValid=validTest("password");
        $isValid=validTest("password_again");
    
       if($isValid==true)
       {   

            var form_data = new FormData($("#signUp_form").get(0));
        console.log(form_data);
            $.ajax({
                url: '/signUp/signUp.inc.php',
                type: "POST",
                processData: false, //important
                contentType: false, //important
                data: form_data,
                success: function(response){

                    response = response.trim();

                    if(response=="success")
                    {
                        alert("註冊成功！");
                        window.location.replace("http://localhost:4000/logIn");
                    }
                    else if(response=="user_exist")
                    {
                        alert("該使用者名稱已存在");
                    }
                }})

        
        
        }
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
function validTest(field){
    switch(field){
        case "photo":
            //TODO:補充驗證機制
            console.log("photo");
            //TD
            break;

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

        case "password_again":
            //TODO:補充驗證機制
            if(isEmpty("password_again")||passwordNotMatch())
            {return false;}
            console.log("password_again");
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

function passwordNotMatch(){
    if($("#password").val()!=$("#password_again").val())
    {
        alert("password not match");
        return true;
    }
    else
    {

        return false;
    }
}