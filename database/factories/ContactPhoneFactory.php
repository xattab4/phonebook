<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Contact;
use App\Model\ContactPhone;
use Faker\Generator as Faker;

$contacts = Contact::get()->pluck('id')->toArray();

$factory->define(ContactPhone::class, function (Faker $faker) use ($contacts) {
    $faker->locale('uk_UA'); 

    return [
        'contact_id' => $contacts[array_rand($contacts, 1)],
        'phone' => $faker->phoneNumber,
    ];
});
