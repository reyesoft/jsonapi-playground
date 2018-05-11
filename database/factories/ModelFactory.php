<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

use App\Author;
use App\Book;
use App\Chapter;
use App\Photo;
use App\Series;
use App\Store;
use App\User;

$factory->define(
    App\User::class, function (Faker\Generator $faker) {
        static $password;

        return [
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'password' => $password ?: $password = bcrypt('secret'),
            'remember_token' => str_random(10),
        ];
    }
);

$factory->define(
    App\Author::class, function (Faker\Generator $faker) {
        return [
            'name' => $faker->name,
            'birthplace' => $faker->country,
            'date_of_birth' => $faker->date($format = 'Y-m-d', $max = 'now'),
            'date_of_death' => $faker->date($format = 'Y-m-d', $max = 'now'),
        ];
    }
);

$factory->define(
    App\Book::class, function (Faker\Generator $faker) {
        return [
            'author_id' => $faker->randomElement(Author::all()->pluck('id')->toArray()),
            'series_id' => $faker->randomElement(Series::all()->pluck('id')->toArray()),
            'date_published' => $faker->date($format = 'Y-m-d', $max = 'now'),
            'title' => $faker->company,
            'isbn' => $faker->isbn10,
        ];
    }
);

$factory->define(
    App\Chapter::class, function (Faker\Generator $faker) {
        return [
            'book_id' => $faker->randomElement(Book::all()->pluck('id')->toArray()),
            'title' => $faker->numerify('Chapter ###'),
            'ordering' => $faker->numerify('#######'),
        ];
    }
);

$factory->define(
    App\Store::class, function (Faker\Generator $faker) {
        return [
            'name' => $faker->numerify('Store ###'),
            'address' => $faker->streetAddress(),
            'private_data' => $faker->company(),
            'created_by' => $faker->randomElement(User::all()->pluck('id')->toArray()),
        ];
    }
);

$factory->define(
    App\Series::class, function (Faker\Generator $faker) {
        return [
            'title' => $faker->numerify('Series #######'),
        ];
    }
);

$factory->define(
    App\Photo::class, function (Faker\Generator $faker) {
        return [
            'title' => $faker->numerify('Photo ###'),
            'uri' => $faker->imageUrl(400, 300, 'abstract', true, 'Faker'),
        ];
    }
);
