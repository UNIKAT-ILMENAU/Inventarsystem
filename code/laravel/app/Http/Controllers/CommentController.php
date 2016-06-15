<?php

namespace App\Http\Controllers;


use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;

use App\Http\Requests;

class CommentController extends Controller
{   
    //===================================================
    //This methode returns a specific comment
    //=================================================== 
    public function getComment($id)
    {
        return DB::table('comment')
            ->where('id',$id)
            ->select('Comment', 'created_at', 'updated_at')
            ->get();

    }

    //===================================================
    //This methode updates a specific comment
    //=================================================== 
    public function CommentUpdate(Request $request, $id) 
    {  
        $R_comment = $request->input('comment');
        $current = Carbon::now();

        $message = DB::table('comment')->where('id', $id)->update(
            [
             'Comment' => $R_comment,
             'updated_at' => $current]);   

        return $id;
    }


}
