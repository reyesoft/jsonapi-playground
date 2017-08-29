<?php

use App\Author;
use App\Book;
use App\Chapter;
use App\Photo;
use App\Serie;
use App\Store;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Author::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->numerify('Author ###'),
        'date_of_birth' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'date_of_death' => $faker->date($format = 'Y-m-d', $max = 'now'),
    ];
});


$factory->define(App\Book::class, function (Faker\Generator $faker) {

    return [
        'author_id' => $faker->randomElement(Author::all()->pluck('id')->toArray()),
        'serie_id' => $faker->randomElement(Serie::all()->pluck('id')->toArray()),
        'date_published' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'title' => $faker->numerify('Book ###'),
    ];
});

$factory->define(App\Chapter::class, function (Faker\Generator $faker) {

    return [
        'book_id' => $faker->randomElement(Book::all()->pluck('id')->toArray()),
        'title' => $faker->numerify('Chapter ###'),
        'ordering' => $faker->numerify('#######'),
    ];
});

$factory->define(App\Store::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->numerify('Store ###'),
    ];
});

$factory->define(App\Serie::class, function (Faker\Generator $faker) {

    return [
        'title' => $faker->numerify('Serie #######'),
    ];
});

$factory->define(App\Photo::class, function (Faker\Generator $faker) {

    return [
        'title' => $faker->numerify('Photo ###'),
        'uri' => $faker->url
    ];
});
