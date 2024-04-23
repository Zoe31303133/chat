$(window).on("load", function(){

    // loading current user info

    $.get("/profile/get_user_info.inc.php").done((response)=>{

        $user_info = JSON.parse(response);
        
        $("#inputName").val($user_info['name']);
        $("#inputEmail").val($user_info['email']);
        $("#inputPhone").val($user_info['phone']);

        $my_uid = sessionStorage.getItem("uid");
        $(".my_photo, #edit_photo").attr("src", "file/" + $my_uid + ".jpg");
    }
    )
    
    // switch edit mode

    $("#user_info input").attr("disabled", "disabled");

    $("#edit_btn, #cancel_btn").on("click", function(){
        $(".control_btn").toggleClass("d-none");

        $("#photo_edit_button").toggleClass("d-none");

        if($("#user_info input").attr("disabled")== "disabled"){
            $("#user_info input").removeAttr("disabled");
        } 
        else{
            $("#user_info input").attr("disabled", "disabled");
        }
    })

    // photo preview

    $("#photo").on("change", function(e){
        console.log(e.target.files[0]);
        var reader = new FileReader();
        reader.onload =function (e) { 
            $("#edit_photo").attr("src", e.target.result);
         }

        reader.readAsDataURL(e.target.files[0]);
        
    })

    // sumit edit

    $("#submit_btn").on("click", function () {


        var my_uid = sessionStorage.getItem('uid');
        var update_user_info ={};

        update_user_info['uid']  = my_uid;

        $("input").each(function(e){

            var field = $(this).attr("name") ;
            var value = $(this).val();
            update_user_info[field] = value;

        });


        var form_data = new FormData($("#update_form").get(0));
        form_data.append("uid", my_uid);

        $.ajax({
            url: '/profile/update_user_info.inc.php',
            type: "POST",
            processData: false, //important
            contentType: false, //important
            data: form_data,
            success: function(response){
                console.log(response);
                location.reload();
            },
            error: function (response,status, status_code_text) { 
                console.log(response);
            
                if(response.status == 400)
                {
                    alert("資料格式錯誤: " + status_code_text);
                }
                
            }
          });

    })


    
})