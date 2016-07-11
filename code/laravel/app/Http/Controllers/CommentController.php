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
    // This methode returns a specific comment
    // Used: /api/v1/restricted/comment/{id}
    //=================================================== 
    public function getComment($id)
    {
        //returns Comment, creation- and update-date
        return DB::table('comment')
            ->where('id',$id)
            ->select('Comment', 'created_at', 'updated_at')
            ->get();

    }

    //===================================================
    // This methode updates a specific comment
    // Used: /api/v1/restricted/comment/update/{id}
    //=================================================== 
    public function CommentUpdate(Request $request, $id) 
    {  
        //sets variables to incomming values by their keys
        $R_comment = $request->input('comment');
        $current = Carbon::now('Europe/Berlin');

        //update comment
        DB::table('comment')->where('id', $id)->update(
            [
             'Comment' => $R_comment,
             'updated_at' => $current]);   

        //return comment id
        return $id;
    }


}
