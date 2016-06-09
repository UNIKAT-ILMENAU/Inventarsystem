<?php

use Illuminate\Database\Seeder;

class RentalTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB:: table('rental')->delete();

		$rental = [
					[
					'User_id'=> 1,
					'CreatedByID'=> 1,
					'EndDate'=> date('Y-m-d')
					],
					[
					'User_id'=> 2,
					'CreatedByID'=> 2,
					'EndDate'=> date('Y-m-d')
					],
					[
					'User_id'=> 3,
					'CreatedByID'=> 3,
					'EndDate'=> date('Y-m-d')
					],
					[
					'User_id'=> 4,
					'CreatedByID'=> 4,
					'EndDate'=> date('Y-m-d')
					],
					[
					'User_id'=> 5,
					'CreatedByID'=> 5,
					'EndDate'=> date('Y-m-d')
					],

					
				  ];

		DB::table('rental')->insert($rental);
    }
}
