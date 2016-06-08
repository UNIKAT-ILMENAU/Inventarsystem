<?php

use Illuminate\Database\Seeder;

class AttachmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB:: table('attachment')->delete();

		$attachment = [
					[
					'Name'=> 'Picture_srewdriver',
					'Value'=> 'localhost_screwdriver',
					'Filetype'=> '.jpg'
					],
					[
					'Name'=> 'Picture_drill',
					'Value'=> 'localhost_drill',
					'Filetype'=> '.png'
					],
					[
					'Name'=> 'manual_chainsaw',
					'Value'=> 'localhost_chainsaw',
					'Filetype'=> '.pdf'
					],
					[
					'Name'=> 'Picture_bulb',
					'Value'=> 'localhost_bulb',
					'Filetype'=> '.jpg'
					],
					[
					'Name'=> 'warnings_nails',
					'Value'=> 'localhost_nails',
					'Filetype'=> '.pdf'
					],

					
				  ];

		DB::table('attachment')->insert($attachment);
    }
}
