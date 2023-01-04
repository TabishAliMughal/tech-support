<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupportChats extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
        'reply_user_id',
        'message',
        'sender_role',
        'is_read',
    ];

    public static function all_chats(){
        $chats = array();
        $temp_chats = DB::table('support_chats')->select('user_id')->groupBy('user_id')->get();
        for ($i=0; $i < count($temp_chats); $i++) {
            $element = ($temp_chats[$i]);
            if (strstr($element->user_id , 'p_')){
                $user['user'] = DB::table('chat_users')->where('user_id',$element->user_id)->get()[0];
                $user['id'] = $element->user_id ;
                array_push($chats , $user);
            }else{
                $user['user'] = DB::table('users')->where('id',$element->user_id)->get()[0];
                $user['id'] = $element->user_id ;
                array_push($chats , $user);
            }

        }
        return $chats ;
    }

    public static function chat_messages($chat_id){
        $raw_messages = (DB::table('support_chats')->where('user_id' , $chat_id)->orderBy('created_at')->get());
        $messages = array();
        for ($i=0; $i < count($raw_messages); $i++) { 
            $message = $raw_messages[$i];
            if ($message->reply_user_id != null) {
                $message->reply_user = DB::table('users')->where('id',$message->reply_user_id)->get()[0];
                array_push($messages , $message);
            }else{
                array_push($messages , $message);
            }
        };
        if (strstr($chat_id , 'p_')){
            $user = DB::table('chat_users')->where('user_id',$chat_id)->get()[0];
        }else{
            $user = DB::table('users')->where('id',$chat_id)->get()[0];
        }
        return ['messages' => $messages , 'chat_user' => $user];
    }

}
