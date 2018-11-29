<?php

use Illuminate\Database\Seeder;

class ContactTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(App\Contact::class, 50000)->create()->each(function($contact){
            $contact->save();
            });
    }
}
