<div class="dropup position-absolute bottom-0 end-0 m-5">
    <button id="chat-btn" type="button" class="btn btn-primary hide-toggle rounded-circle" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
        <i class="bi bi-chat"></i>
    </button>
    <div class="dropdown-menu message-box" onclick="event.stopPropagation()" style="padding: 0;">
        <section class="chat-section">
            <div class="row d-flex justify-content-center">
                <div class="col-md-12 col-lg-12 col-xl-12">
                    <div class="card" id="chat2">
                        <div class="card-header d-flex justify-content-between align-items-center p-3">
                            <h5 class="mb-0">Hello! <span id="id_user_hello" style="font-size:15px;"></span></h5>
                            <button type="button" class="border-0 bg-transparent" onclick="document.getElementById('chat-btn').click()"><i class="bi bi-x text-bold" style="font-size:25px"></i></button>
                        </div>
                        <div id="id_user_data_form" class="d-none">
                            <form onsubmit="storeUser()" action="{{ route('initialize_user') }}">
                                <div class="card-body chat-body" data-mdb-perfect-scrollbar="true" >
                                    <!-- Form For Newcomer -->
                                    <div id="id_newuser_form">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="id_user_name" name="user_name" placeholder="John Smith" value="Tabish Ali Mughal" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email-address" class="form-label">Email Address</label>
                                            <input type="email" class="form-control" id="id_user_email" name="user_email" placeholder="name@example.com" value="tabishalimughal@gmail.com" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email-address" class="form-label">Contact</label>
                                            <input type="number" class="form-control" id="id_user_contact" name="user_contact" placeholder="03123456789" value="03112918396" required>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="role" id="id_customer" value="customer" checked>
                                            <label class="form-check-label" for="customer">
                                                I Am A Customer
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="role" id="id_dealer" value="dealer">
                                            <label class="form-check-label" for="dealer">
                                                I Am A Dealer
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-muted d-flex justify-content-start align-items-center p-3">
                                    <input type="submit" class="btn btn-primary form-control">
                                </div>
                            </form>
                        </div>
                        <div id="id_msg_content" class="d-none">
                            <div class="card-body chat-body" id="id_scroll_div" data-mdb-perfect-scrollbar="true" style="display:flex;flex-direction:column-reverse;">
                                <!-- Messages Of User If Authenticated -->
                                <div id="id_messages">
                                    <div class="d-flex flex-row justify-content-start mb-4">
                                        <p class="small p-2 ms-3 mb-1 rounded-3" style="background-color: #f5f6f7;">
                                            <div id="wave">
                                                <span class="dot"></span>
                                                <span class="dot"></span>
                                                <span class="dot"></span>
                                            </div>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <form id="id_message_form" onsubmit="sendMessage()" action="/tech-support/message/user" method="POST">
                                @csrf
                                <div class="card-footer text-muted d-flex justify-content-start align-items-center p-3">
                                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3-bg.webp" alt="avatar 3" style="width: 40px; height: 100%;">
                                    <input id="id_message_text" type="text" class="form-control" name="message" placeholder="Type message" required autocomplete="off">
                                    <input id="id_user_id" type="text" name="id" class="d-non" hidden>
                                    <button class="ms-3 border-0" type="submit"><i class="bi bi-send-fill"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>


<script>
    function checkUser(){
        if (localStorage.getItem('user_id') == null && '{{ Auth()->User()->name ?? "null" }}' == 'null'){
            document.getElementById('id_msg_content').classList.add('d-none');
            document.getElementById('id_user_data_form').classList.remove('d-none');
        }else{
            if ('{{ Auth()->User()->name ?? "null" }}' != 'null'){
                localStorage.setItem('user_id' , "{{ Auth()->User()->id ?? '' }}");
                localStorage.setItem('user_name' , "{{ Auth()->User()->name ?? '' }}");
            }
            document.getElementById('id_user_hello').innerHTML = " " + localStorage.getItem('user_name') ;
            document.getElementById('id_user_id').value = localStorage.getItem('user_id') ;
            document.getElementById('id_user_data_form').classList.add('d-none');
            document.getElementById('id_msg_content').classList.remove('d-none');
            fetchMessages();
        }
    }
    function storeUser (){
        event.preventDefault();
        $.ajax({
            url: "{{ route('initialize_user') }}",
            method: "get",
            data : {
                '_token' : "{{ csrf_token() }}",
                'id' : "p_" + (Date.now() + Math.floor(Math.random() * 100)) ,
                'name' : document.getElementById('id_user_name').value ,
                'email' : document.getElementById('id_user_email').value,
                'contact' : document.getElementById('id_user_contact').value,
                'role' : (document.getElementById('id_customer').checked ? 'customer':'dealer'),
            },
            success: function(data){
                if (data.user_id){
                    localStorage.setItem('user_id' , data.user_id);
                    localStorage.setItem('user_name' , data.name);
                    localStorage.setItem('user_email' , data.email);
                    localStorage.setItem('user_contact' , data.contact);
                    localStorage.setItem('role' , data.role);
                    checkUser();
                }
            },
            error: function (xhr, status, error){
                alert('Cant Register User At This Time , Please Try Again Later');
            },
        });
    }
    function sendMessage (){
        event.preventDefault();
        $.ajax({
            url: "/tech-support/message/user/send",
            method: "post",
            data : {
                '_token' : "{{ csrf_token() }}",
                'id' : document.getElementById('id_user_id').value ,
                'message' : document.getElementById('id_message_text').value ,
            },
            success: function(data){
                document.getElementById('id_message_text').value = '';
                fetchMessages();
            },
            error: function (xhr, status, error){
                alert('Cant Send Messages At This Time , Please Try Again Later');
            },
        });
    }
    function fetchMessages (){
        $.ajax({
            url: "/tech-support/message/user/fetch/messages",
            method: "get",
            data : {
                '_token' : "{{ csrf_token() }}",
                'id' : localStorage.getItem('user_id') ,
            },
            success: function(data){
                var messages = '';
                var date = '';
                messages += '<div class="pt-1"><div style="display:grid">';
                messages += '<div><p class="small p-2 mb-1 text-primary rounded-3 bg-light border border-primary" style="width:max-content;float:left;">Hello!</p></div>';
                
                messages += '<div><p class="small p-2 mb-1 text-primary rounded-3 bg-light border border-primary" style="width:max-content;float:left;">' + localStorage.getItem('user_name') + '</p></div>';
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
                        messages += '<div><p class="small p-2 mb-2 text-white rounded-3 bg-primary message-block-right user-message-width">';
                        messages += '<span class="text-light" style="display:">' + element.message + '</span>'
                        messages += '<span class="text-light time-stamp">' + time + '</span>'
                        messages += '</p></div>';
                    }
                    if (element.reply_user_id != null){
                        messages += '<div class="small p-2 mb-2 text-white rounded-3 bg-light border border-primary message-block-left user-message-width">';
                        messages += '<span class="text-dark" style="display:block;font-size: 10px;">' + element.reply_user.name + '</span>'
                        messages += '<span class="text-primary" style="display:">' + element.message + '</span>'
                        messages += '<span class="text-dark time-stamp">' + time + '</span>'
                        messages += '</div>';
                    }
                }
                messages += '</div>';
                document.getElementById('id_messages').innerHTML = messages ;
            },
            error: function (xhr, status, error){
                alert('Cant Fetch Messages At This Time , Please Try Again Later');
            },
        });
    }
    // setInterval(function(){
        $(checkUser());
    // }, 1000);
</script>