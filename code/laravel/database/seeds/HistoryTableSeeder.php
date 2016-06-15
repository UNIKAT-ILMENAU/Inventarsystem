<?php

use Illuminate\Database\Seeder;

class HistoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
	public function run()
    {
		DB:: table('history')->delete();

		$history = [
					[
					'CommentID'=> 1,
					'CreatedByID'=> 1,
					'Item_ID'=> 1,
					'Event_ID'=> 1
					],
					[
					'CommentID'=> 2,
					'CreatedByID'=> 2,
					'Item_ID'=> 2,
					'Event_ID'=> 2
					],[
					'CommentID'=> 3,
					'CreatedByID'=> 3,
					'Item_ID'=> 3,
					'Event_ID'=> 3
					],[
					'CommentID'=> 4,
					'CreatedByID'=> 4,
					'Item_ID'=> 4,
					'Event_ID'=> 4
					],[
					'CommentID'=> 5,
					'CreatedByID'=> 5,
					'Item_ID'=> 5,
					'Event_ID'=> 5
					],

					
				  ];

		DB::table('history')->insert($history);
    }
}
