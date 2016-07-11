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
		DB:: table('category')->delete();

		$category = [
					[
					'Name'=> 'tools',
					'Description'=> 'Tools you can use with one hand',
					'BeforeID'=> NULL
					],
					[
					'Name'=> 'handtools',
					'Description'=> 'Tools you usally have at home',
					'BeforeID'=> 1
					],
					[
					'Name'=> 'screwdriver',
					'Description'=> 'Tool to srew soemthing in or out',
					'BeforeID'=> 2
					],
					[
					'Name'=> 'hammer',
					'Description'=> 'Tool to hammer something in',
					'BeforeID'=> 2
					],
					[
					'Name'=> 'electro tools',
					'Description'=> 'Tools which are powered with electric',
					'BeforeID'=> 1
					],
					[
					'Name'=> 'hometools',
					'Description'=> 'Tools you usally have at home',
					'BeforeID'=> 1
					],
					[
					'Name'=> 'communication',
					'Description'=> 'Tools you usally have at home',
					'BeforeID'=> 5
					],
					[
					'Name'=> 'garden tool',
					'Description'=> 'Tools you usally have at home',
					'BeforeID'=> 6
					],
					[
					'Name'=> 'toolstools',
					'Description'=> 'Tools you can use with one hand',
					'BeforeID'=> NULL
					],
					
					
				  ];

		DB::table('category')->insert($category);
    }

}
