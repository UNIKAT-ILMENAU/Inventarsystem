<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB:: table('category')->delete();

		$category = [
					[
					'Name'=> 'Handtools',
					'Description'=> 'Tools you can use with one hand',
					'BeforeID'=> NULL
					],
					[
					'Name'=> 'screwdriver',
					'Description'=> 'Tool to srew soemthing in or out',
					'BeforeID'=> 1
					],
					[
					'Name'=> 'hammer',
					'Description'=> 'Tool to hammer something in',
					'BeforeID'=> 1
					],
					[
					'Name'=> 'electro tools',
					'Description'=> 'Tools which are powered with electric',
					'BeforeID'=> NULL
					],
					[
					'Name'=> 'hometools',
					'Description'=> 'Tools you usally have at home',
					'BeforeID'=> NULL
					],

					
				  ];

		DB::table('category')->insert($category);
    }
}
