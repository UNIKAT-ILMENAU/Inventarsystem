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
    	$this->call('CategoryTableSeeder');
    	$this->call('MaterialTableSeeder');
        
    	$this->call('CommentTableSeeder');
        $this->call('MemberTableSeeder');
        $this->call('UserTableSeeder');
        $this->call('PlaceTableSeeder');
        $this->call('EventTableSeeder');
        $this->call('AttachmentTableSeeder');
        $this->call('ItemTableSeeder');
        $this->call('RentalTableSeeder');
        $this->call('HistoryTableSeeder');
		
		
    	

    }
}
