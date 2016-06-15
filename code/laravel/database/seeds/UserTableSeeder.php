<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB:: table('user')->delete();

		$user = [
					[
					'FirstName'=> 'Max',
					'LastName'=> 'Ruetz',
					'Street'=> 'Waldstr',
					'City'=> 'Ilmenau',
					'ZIP'=>  '98693',
					'MobilePhone'=>  '0174/234342',
					'eMail'=>  'max@example.com',
					'Matrikel'=>  '34567',
					'member_id'=> NULL
					],
					[
					'FirstName'=> 'Kevin',
					'LastName'=> 'Bartsch',
					'Street'=> 'Baumstr',
					'City'=> 'Berlin',
					'ZIP'=>  '13344',
					'MobilePhone'=>  '0162/234411',
					'eMail'=>  'kevin@example.com',
					'Matrikel'=>  '12345',
					'member_id'=> NULL
					],
					[
					'FirstName'=> 'Franz',
					'LastName'=> 'Stecher',
					'Street'=> 'Randomstr',
					'City'=> 'Erfurt',
					'ZIP'=>  '23457',
					'MobilePhone'=>  '0152/343457',
					'eMail'=>  'Franz@example.com',
					'Matrikel'=>  '45678',
					'member_id'=> NULL
					],
					[
					'FirstName'=> 'Martin',
					'LastName'=> 'Werchan',
					'Street'=> 'Baumstr',
					'City'=> 'Ilmenau',
					'ZIP'=>  '98693',
					'MobilePhone'=>  '0152/558323',
					'eMail'=>  'Martin@example.com',
					'Matrikel'=>  '23456',
					'member_id'=> NULL
					],
					[
					'FirstName'=> 'Olli',
					'LastName'=> 'Sommer',
					'Street'=> 'Apfelstr',
					'City'=> 'hamburg',
					'ZIP'=>  '95653',
					'MobilePhone'=>  '0152/558323',
					'eMail'=>  'olli@example.com',
					'Matrikel'=>  '23456',
					'member_id'=> NULL
					],
					[
					'FirstName'=> 'Andreas',
					'LastName'=> 'Wehenkel',
					'Street'=> 'Kirchenstr',
					'City'=> 'Melsungen',
					'ZIP'=>  '74542',
					'MobilePhone'=>  '0162/779534',
					'eMail'=>  'andreas@example.com',
					'Matrikel'=>  '31467',
					'member_id'=> 1
					],
					[
					'FirstName'=> 'Connor',
					'LastName'=> 'Schellhorn',
					'Street'=> 'Dorfstr',
					'City'=> 'Köln',
					'ZIP'=>  '43156',
					'MobilePhone'=>  '0162/112357',
					'eMail'=>  'connor@example.com',
					'Matrikel'=>  '63221',
					'member_id'=> 2
					],
				
					[
					'FirstName'=> 'David',
					'LastName'=> 'Scholz',
					'Street'=> 'Tasterturstr',
					'City'=> 'Erfurt',
					'ZIP'=>  '23457',
					'MobilePhone'=>  '0173/593053',
					'eMail'=>  'David@example.com',
					'Matrikel'=>  '44446',
					'member_id'=> 3
					],
					
					[
					'FirstName'=> 'Dirk',
					'LastName'=> 'Nowitzky',
					'Street'=> 'Basketballstr',
					'City'=> 'Bremen',
					'ZIP'=>  '73458',
					'MobilePhone'=>  '0153/592350',
					'eMail'=>  'dirk@example.com',
					'Matrikel'=>  '78793',
					'member_id'=> "4"
					],
					
					[
					'FirstName'=> 'Christian',
					'LastName'=> 'Woebke',
					'Street'=> 'Goethestr',
					'City'=> 'Ilmenau',
					'ZIP'=>  '23342',
					'MobilePhone'=>  '0174/595255',
					'eMail'=>  'chris@example.com',
					'Matrikel'=>  '12579',
					'member_id'=> "5"
					]

					
				  ];

		DB::table('user')->insert($user);
    }
}
