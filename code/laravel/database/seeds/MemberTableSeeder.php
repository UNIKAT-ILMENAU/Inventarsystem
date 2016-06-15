<?php

use Illuminate\Database\Seeder;

class MemberTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB:: table('member')->delete();

		$member = [
					[
					'isActivated'=> 0,
					'isAdmin'=> 1,
					'Authorize'=> Hash::make('hash'),
					'password'=>  Hash::make('12345'),
					],
					[
					'isActivated'=> 1,
					'isAdmin'=> 1,
					'Authorize'=> Hash::make('hash2'),
					'password'=>  Hash::make('password1'),
					],
					[
					'isActivated'=> 1,
					'isAdmin'=> 1,
					'Authorize'=> Hash::make('hash3'),
					'password'=>  Hash::make('password3'),
					],
					[
					'isActivated'=> 1,
					'isAdmin'=> 1,
					'Authorize'=> Hash::make('hash4'),
					'password'=>  Hash::make('password4'),
					],
					[
					'isActivated'=> 1,
					'isAdmin'=> 1,
					'Authorize'=> Hash::make('hash5'),
					'password'=>  Hash::make('password5'),
					],
					
				  ];

		DB::table('member')->insert($member);
    }
}
