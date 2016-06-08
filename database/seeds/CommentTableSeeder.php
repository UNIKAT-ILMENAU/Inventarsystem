<?php

use Illuminate\Database\Seeder;

class CommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB:: table('comment')->delete();

		$comment = [
					[
					'Comment'=> 'Comment1'
					],
					[
					'Comment'=> 'Comment2'
					],
					[
					'Comment'=> 'Comment3'
					],
					[
					'Comment'=> 'Comment4'
					],
					[
					'Comment'=> 'Comment5'
					],

					
				  ];

		DB::table('comment')->insert($comment);
    }
}