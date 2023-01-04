<div class="container">
    @if(Auth()->User())
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Messages
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div id="id_user_chats" style="overflow-y: scroll;" class="col col-md-4 chats-list">
                            </div>
                            <div class="col col-md-8 messages-list">
                                <div class="card">
                                    <div id="id_card_header" class="card-header">
                                        <span id="id_user_name_head" class="position-absolute" style="left:10px;top:0px;">User</span>
                                        <span id="id_user_email_head" class="position-absolute text-muted" style="left:10px;font-size:10px;top:25px">Email</span>
                                        <span id="id_user_id_head" class="position-absolute text-muted" style="right:10px;font-size:10px;top:5px;">ID</span>
                                        <span id="id_user_role_head" class="position-absolute text-muted" style="right:10px;font-size:10px;top:25px">Role</span>
                                        <br>
                                    </div>
                                    <div class="card-body" style="min-height:385px;max-height:385px;overflow:auto;display:flex;flex-direction:column-reverse;">
                                            <div id="id_messages" style="display:grid;">
                                                <div><p class="small p-2 me-3 mb-1 text-white rounded-3 bg-primary" style="width:max-content;float:right;"></p></div>
                                                <div><p class="small p-2 me-3 mb-1 text-white rounded-3 bg-primary" style="width:max-content;float:left;"></p></div>
                                            </div>
                                        </div>
                                    <div id="id_card_footer" class="card-footer d-none">
                                        <form onsubmit="sendMessage()" sction="/tech-support/message/admin/send">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <input id="id_message_text" type="text" class="form-control" placeholder="Type Reply" required>
                                                    <input id="id_user_id" type="text" name="user_id" class="d-non" required hidden>
                                                    <input id="id_admin_id" type="text" name="admin_id" class="d-non" required hidden>
                                                </div>
                                                <div class="col-md-2">
                                                    <button class="btn btn-primary form-control">
                                                        <i class="bi bi-send-fill"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
<script>
    $.ajax({
        url: "/tech-support/message/admin/fetch",
        method: "get",
        success: function(data){
            chats = '';
            for (let i = 0; i < data.length; i++) {
                const element = data[i];
                user_id = "'" + element.id + "'";
                chats += '<div class="chat-block" onclick="getChats(' + (user_id) + ')" >';
                chats += element.user.name;
                chats += '</div>';
            }
            document.getElementById('id_user_chats').innerHTML = chats;
        },
        error: function (xhr, status, error){
            // alert('Cant Fetch Chats At This Time , Please Try Again Later');
        },
    })
</script>
<script>
    var reload = 'false';
    // setInterval(function(){
        if (reload == 'true'){
            getChats(document.getElementById('id_user_id').value);
        }
    // }, 3000);
    function getChats (user_id){
        $.ajax({
            url: "/tech-support/message/admin/fetch/messages",
            method: "get",
            data: {
                '_token' : "{{ csrf_token() }}" , 
                'id' : user_id ,
            },
            success: function(data){
                var messages = '';
                var date = '';
                messages += '<div class="pt-1"><div style="display:grid">';
                
                for (let i = 0; i < data.messages.length; i++) {
                    const element = data.messages[i];
                    time = (new Date(element.created_at).getHours()) + ":" + (new Date(element.created_at).getMinutes().toLocaleString('en-US', {minimumIntegerDigits: 2,useGrouping: false}));
                    msg_date = new Date(element.created_at).toJSON().slice(0, 10);
                    let currentDate = new Date().toJSON().slice(0, 10);
                    if (msg_date != date){
                        date = msg_date;
                        if(new Date(currentDate).getTime() == new Date(msg_date).getTime()){
                            messages += '<div class="text-center my-4 date-stamp-block"><a class="text-muted position-relative date-stamp">Today</a></div>';
                        }else if(new Date(currentDate).getTime() - 86400000 == new Date(msg_date).getTime()){
                            messages += '<div class="text-center my-4 date-stamp-block"><a class="text-muted position-relative date-stamp">Yesterday</a></div>';
                        }else{
                            messages += '<div class="text-center my-4 date-stamp-block"><a class="text-muted position-relative date-stamp">' + String(new Date(date)).substr(0,15) + '</a></div>';
                        }
                    };
                    if (element.reply_user_id == null){
                        messages += '<div class="small p-2 mb-2 text-white rounded-3 bg-light border border-primary message-block-left admin-message-width">';
                        messages += '<span class="text-primary" style="display:">' + element.message + '</span>'
                        messages += '<span class="text-dark time-stamp">' + time + '</span>'
                        messages += '</div>';
                    }
                    if (element.reply_user_id != null){
                        messages += '<div><p class="small p-2 mb-2 text-white rounded-3 bg-primary message-block-right admin-message-width">';
                        messages += '<span class="text-dark" style="display:block;font-size: 10px;">' + element.reply_user.name + '</span>'
                        messages += '<span class="text-light" style="display:">' + element.message + '</span>'
                        messages += '<span class="text-dark time-stamp">' + time + '</span>'
                        messages += '</div>';
                    }
                }
                messages += '</div>';
                document.getElementById('id_messages').innerHTML = messages ;
                document.getElementById('id_user_id').value = user_id ;
                document.getElementById('id_admin_id').value = '{{ Auth()->User()->id }}' ;
                document.getElementById('id_user_name_head').innerHTML = data.chat_user['name'];
                document.getElementById('id_user_role_head').innerHTML = (String(data.chat_user['role']) == "undefined" ? "Registered User":String(data.chat_user['role']).charAt(0).toUpperCase() + String(data.chat_user['role']).slice(1));
                document.getElementById('id_user_id_head').innerHTML = (String(data.chat_user['user_id']) == "undefined" ? data.chat_user['id']:data.chat_user['user_id']);
                document.getElementById('id_user_email_head').innerHTML = data.chat_user['email'];
                document.getElementById('id_card_footer').classList.remove('d-none') ;
                reload = 'true' ;
            },
            error: function (xhr, status, error){
                alert('Cant Fetch Messages At This Time , Please Try Again Later');
            },
        })
    }
    
    function sendMessage (){
        event.preventDefault();
        $.ajax({
            url: "/tech-support/message/admin/send",
            method: "post",
            data : {
                '_token' : "{{ csrf_token() }}",
                'id' : document.getElementById('id_user_id').value ,
                'message' : document.getElementById('id_message_text').value ,
                'admin' : document.getElementById('id_admin_id').value ,
            },
            success: function(data){
                document.getElementById('id_message_text').value = '';
                getChats(document.getElementById('id_user_id').value);
            },
            error: function (xhr, status, error){
                alert('Cant Send Messages At This Time , Please Try Again Later');
            },
        });
    }

</script>