<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Contact;
use Faker\Generator as Faker;

$factory->define(Contact::class, function (Faker $faker) {
    $faker->locale('uk_UA'); 
    
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
    ];
});
