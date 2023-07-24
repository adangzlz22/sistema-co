<?php

use Database\Seeders\CustomReportSeeder;
use Database\Seeders\RolesSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\OrganismTypesSeeder;
use Database\Seeders\OrganismsSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\MenuSeeder;
use Database\Seeders\IconSeeder;
use Database\Seeders\ContractTypeSeeder;
use Database\Seeders\EntitiesSeeder;
use Database\Seeders\EntitiesTypesSeeder;
use Database\Seeders\FileCategoriesSeeder;
use Database\Seeders\JurisdictionContractSeeder;
use Database\Seeders\MeetingTypesSeeder;
use Database\Seeders\ModalitiesSeeder;
use Database\Seeders\PaymentMethodSeeder;
use Database\Seeders\StatusSeeder;
use Illuminate\Database\Seeder;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesSeeder::class,
            PermissionSeeder::class,
            EntitiesTypesSeeder::class,
            EntitiesSeeder::class,
            UserSeeder::class,
            IconSeeder::class,
            CategorySeeder::class,
            MenuSeeder::class,
            CustomReportSeeder::class,
            StatusSeeder::class,
            ModalitiesSeeder::class,
            FileCategoriesSeeder::class,
            MeetingTypesSeeder::class,
            
        ]);

    }
}
