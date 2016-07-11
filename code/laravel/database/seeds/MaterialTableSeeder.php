<?php

use Illuminate\Database\Seeder;

class MaterialTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB:: table('material')->delete();

		$material = [
          [
          'StorageValue'=> NULL,
          'CriticalStorageValue'=> NULL,
          'UoM'=> NULL,
          'UoM_short'=> NULL,
          'BuildType'=> NULL,
          'SalePrice'=> NULL
          ],					
          [
					'StorageValue'=> 70,
      		'CriticalStorageValue'=> 75,
     			'UoM'=> 'centimeter',
     			'UoM_short'=> 'cm',
     			'BuildType'=> 'heavy',
      		'SalePrice'=> 7.50
					],
					[
					'StorageValue'=> 234,
      		'CriticalStorageValue'=> 200,
     			'UoM'=> 'milliliter',
     			'UoM_short'=> 'ml',
     			'BuildType'=> 'light',
      		'SalePrice'=> 4.35
					],
					[
					'StorageValue'=> 460,
      		'CriticalStorageValue'=> 400,
     			'UoM'=> 'kilogram',
     			'UoM_short'=> 'kg',
     			'BuildType'=> 'big',
      		'SalePrice'=> 3.33
					],
					[
					'StorageValue'=> 20,
      		'CriticalStorageValue'=> 2,
     			'UoM'=> 'tonns',
     			'UoM_short'=> 't',
     			'BuildType'=> 'thin',
      		'SalePrice'=> 9.99
					],       
          [
          'StorageValue'=> 75,
          'CriticalStorageValue'=> 75,
          'UoM'=> 'centimeter',
          'UoM_short'=> 'cm',
          'BuildType'=> 'heavy',
          'SalePrice'=> 7.50
          ],
          [
          'StorageValue'=> 234,
          'CriticalStorageValue'=> 12,
          'UoM'=> 'milliliter',
          'UoM_short'=> 'ml',
          'BuildType'=> 'light',
          'SalePrice'=> 4.35
          ],
          [
          'StorageValue'=> 1460,
          'CriticalStorageValue'=> 400,
          'UoM'=> 'kilogram',
          'UoM_short'=> 'kg',
          'BuildType'=> 'big',
          'SalePrice'=> 3.33
          ],
          [
          'StorageValue'=> 220,
          'CriticalStorageValue'=> 80,
          'UoM'=> 'tonns',
          'UoM_short'=> 't',
          'BuildType'=> 'thin',
          'SalePrice'=> 9.99
          ],
          [
          'StorageValue'=> 210,
          'CriticalStorageValue'=> 21,
          'UoM'=> 'tonns',
          'UoM_short'=> 't',
          'BuildType'=> 'thin',
          'SalePrice'=> 9.99
          ],
          [
          'StorageValue'=> 330,
          'CriticalStorageValue'=> 32,
          'UoM'=> 'tonns',
          'UoM_short'=> 't',
          'BuildType'=> 'thin',
          'SalePrice'=> 9.99
          ],
          [
          'StorageValue'=> 459,
          'CriticalStorageValue'=> 100,
          'UoM'=> 'tonns',
          'UoM_short'=> 't',
          'BuildType'=> 'thin',
          'SalePrice'=> 9.99
          ],
					
				  ];

		DB::table('material')->insert($material);
    }
}
