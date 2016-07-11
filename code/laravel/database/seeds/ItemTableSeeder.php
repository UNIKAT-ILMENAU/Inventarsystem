<?php

use Illuminate\Database\Seeder;

class ItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB:: table('item')->delete();

		$item = [
					[
					'Name'=> 'Phillips screwdriver',
					'State' => 1,
					'Description'=> 'description screwdriver',
					'deleted'=> 0,
					'visible'=> 1,
					'CreatedByID'=> 1,
					'PlaceStartID'=> 5,
					'CategoryStartID'=> 2,
					'material_id'=> 1,
					'Attachment_ID'=> 1
					],
					[
					'Name'=> 'Claw hammer',
					'State' => 3,
					'Description'=> 'description claw hammer',
					'deleted'=> 0,
					'visible'=> 0,
					'CreatedByID'=> 2,
					'PlaceStartID'=> 5,
					'CategoryStartID'=> 1,
					'material_id'=> 1,
					'Attachment_ID'=> NULL
					],
					[
					'Name'=> 'drill',
					'State' => 0,
					'Description'=> 'description drill',
					'deleted'=> 1,
					'visible'=> 0,
					'CreatedByID'=> 3,
					'PlaceStartID'=> 5,
					'CategoryStartID'=> 1,
					'material_id'=> 1,
					'Attachment_ID'=> 2
				    
					],
					[
					'Name'=> 'srews',
					'State' => 1,
					'Description'=> 'description screws',
					'deleted'=> 0,
					'visible'=> 1,
					'CreatedByID'=> 4,
					'PlaceStartID'=> 3,
					'CategoryStartID'=> 9,
					'material_id'=> 2,
					'Attachment_ID'=> NULL
					],
					
					[
					'Name'=> 'bulb 60W',
					'State' => 1,
					'Description'=> 'description bulb',
					'deleted'=> 0,
					'visible'=> 1,
					'CreatedByID'=> 1,
					'PlaceStartID'=> 3,
					'CategoryStartID'=> 4,
					'material_id'=> 3,
					'Attachment_ID'=> 4
					],
					[
					'Name'=> 'Saw',
					'State' => 1,
					'Description'=> 'description saw',
					'deleted'=> 1,
					'visible'=> 0,
					'CreatedByID'=> 5,
					'PlaceStartID'=> 5,
					'CategoryStartID'=> 8,
					'material_id'=> 1,
					'Attachment_ID'=> NULL
					],
					[
					'Name'=> 'nail',
					'State' => 0,
					'Description'=> 'description nail',
					'deleted'=> 0,
					'visible'=> 1,
					'CreatedByID'=> 2,
					'PlaceStartID'=> 3,
					'CategoryStartID'=> 4,
					'material_id'=> 4,
					'Attachment_ID'=> 5
					],
					[
					'Name'=> 'door knop',
					'State' => 1,
					'Description'=> 'description door knop',
					'deleted'=> 0,
					'visible'=> 1,
					'CreatedByID'=> 4,
					'PlaceStartID'=> 3,
					'CategoryStartID'=> 4,
					'material_id'=> 5,
					'Attachment_ID'=> NULL
					],
					[
					'Name'=> 'marmor plate',
					'State' => 1,
					'Description'=> 'description marmor plate',
					'deleted'=> 1,
					'visible'=> 0,
					'CreatedByID'=> 3,
					'PlaceStartID'=> 3,
					'CategoryStartID'=> 5,
					'material_id'=> 6,
					'Attachment_ID'=> NULL
					],
					[
					'Name'=> 'woodsaw',
					'State' => 1,
					'Description'=> 'description chainsaw',
					'deleted'=> 1,
					'visible'=> 0,
					'CreatedByID'=> 5,
					'PlaceStartID'=> 5,
					'CategoryStartID'=> 6,
					'material_id'=> 1,
					'Attachment_ID'=> 3
					],
										[
					'Name'=> 'Phillips screwdriver',
					'State' => 1,
					'Description'=> 'description screwdriver',
					'deleted'=> 0,
					'visible'=> 1,
					'CreatedByID'=> 1,
					'PlaceStartID'=> 5,
					'CategoryStartID'=> 7,
					'material_id'=> 1,
					'Attachment_ID'=> 1
					],
					[
					'Name'=> 'Claw hammer',
					'State' => 3,
					'Description'=> 'description claw hammer',
					'deleted'=> 0,
					'visible'=> 0,
					'CreatedByID'=> 2,
					'PlaceStartID'=> 5,
					'CategoryStartID'=> 1,
					'material_id'=> 1,
					'Attachment_ID'=> NULL
					],
					[
					'Name'=> 'drill',
					'State' => 2,
					'Description'=> 'description drill',
					'deleted'=> 1,
					'visible'=> 0,
					'CreatedByID'=> 3,
					'PlaceStartID'=> 5,
					'CategoryStartID'=> 4,
					'material_id'=> 1,
					'Attachment_ID'=> 2
				    
					],
					[
					'Name'=> 'screws',
					'State' => 1,
					'Description'=> 'description screws',
					'deleted'=> 0,
					'visible'=> 1,
					'CreatedByID'=> 4,
					'PlaceStartID'=> 3,
					'CategoryStartID'=> 5,
					'material_id'=> 7,
					'Attachment_ID'=> NULL
					],
					[
					'Name'=> 'Saw',
					'State' => 2,
					'Description'=> 'description saw',
					'deleted'=> 1,
					'visible'=> 0,
					'CreatedByID'=> 5,
					'PlaceStartID'=> 5,
					'CategoryStartID'=> 1,
					'material_id'=> 1,
					'Attachment_ID'=> NULL
					],
					[
					'Name'=> 'bulb',
					'State' => 1,
					'Description'=> 'description bulb',
					'deleted'=> 0,
					'visible'=> 1,
					'CreatedByID'=> 1,
					'PlaceStartID'=> 3,
					'CategoryStartID'=> 5,
					'material_id'=> 8,
					'Attachment_ID'=> 4
					],
					[
					'Name'=> 'nail',
					'State' => 1,
					'Description'=> 'description nail',
					'deleted'=> 0,
					'visible'=> 1,
					'CreatedByID'=> 2,
					'PlaceStartID'=> 3,
					'CategoryStartID'=> 6,
					'material_id'=> 9,
					'Attachment_ID'=> 5
					],
					[
					'Name'=> 'wooden plate',
					'State' => 0,
					'Description'=> 'description wooden plate',
					'deleted'=> 0,
					'visible'=> 1,
					'CreatedByID'=> 3,
					'PlaceStartID'=> 3,
					'CategoryStartID'=> 5,
					'material_id'=> 10,
					'Attachment_ID'=> NULL
					],
					[
					'Name'=> 'door knop',
					'State' => 0,
					'Description'=> 'description door knop',
					'deleted'=> 0,
					'visible'=> 1,
					'CreatedByID'=> 4,
					'PlaceStartID'=> 3,
					'CategoryStartID'=> 3,
					'material_id'=> 11,
					'Attachment_ID'=> NULL
					],
					[
					'Name'=> 'chainsaw',
					'State' => 1,
					'Description'=> 'description chainsaw',
					'deleted'=> 1,
					'visible'=> 0,
					'CreatedByID'=> 5,
					'PlaceStartID'=> 5,
					'CategoryStartID'=> 4,
					'material_id'=> 1,
					'Attachment_ID'=> 3
					],
					
				  ];

		DB::table('item')->insert($item);
    }
}
