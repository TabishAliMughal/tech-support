<?php

namespace App\Http\Controllers;

use App\Models\SupportChats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupportChatsController extends Controller
{

    public function initialize_user(){
        // $user = DB::table('chat_users')->where('email',request('email'))->where('contact',request('contact'))->get();
        // if (count($user) <= 0){
            DB::table('chat_users')->insert([
                'user_id' => request('id'),
                'name' => request('name'),
                'email' => request('email'),
                'role' => request('role'),
                'contact' => request('contact'),
            ]);
            $user = DB::table('chat_users')->where('user_id',request('id'))->get();
            return ($user[0]);
        // }else{
        //     return ($user[0]);
        // }
    }

    public function user_send_message(){
        DB::table('support_chats')->insert([
            'user_id' => request('id') ,
            'reply_user_id' => request('') ,
            'message' => request('message') ,
            'is_read' => 0 ,
        ]);
        return array('status' => "Success");
    }

    public function fetch_my_messages(){
        return SupportChats::chat_messages(request('id'));
        // return (SupportChats::where('user_id' , request('id'))->get());
    }

    public function fetch_all_chats(){
        return SupportChats::all_chats();
    }

    public function fetch_chat_messages(){
        return SupportChats::chat_messages(request('id'));
    }

    public function admin_send_message(){
        DB::table('support_chats')->insert([
            'user_id' => request('id') ,
            'reply_user_id' => request('admin') ,
            'message' => request('message') ,
            'is_read' => 0 ,
        ]);
        return (DB::table('support_chats')->where('user_id' , request('id'))->get());
    }
}
