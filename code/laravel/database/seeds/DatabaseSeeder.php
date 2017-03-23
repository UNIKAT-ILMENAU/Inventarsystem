<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if( App::environment('local')) {
            $this->call('CategoryTableSeeder');
            $this->call('MaterialTableSeeder');
            $this->call('CommentTableSeeder');
            $this->call('MemberTableSeeder');
            $this->call('UserTableSeeder');
            $this->call('PlaceTableSeeder');
            $this->call('EventTableSeeder');
            $this->call('ItemTableSeeder');
            $this->call('RentalTableSeeder');
            $this->call('HistoryTableSeeder');
        } else {
            // insert Fake Material for Devices
            $this->call('MaterialTableSeeder');
            $this->call('EventTableSeeder');
        }



    	

    }
}
