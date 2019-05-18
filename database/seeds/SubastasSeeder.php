<?php

use Illuminate\Database\Seeder;

class SubastasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Subasta::class)->times(17)->create();
    }
}