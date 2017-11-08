<?php

use App\Author;
use App\Book;
use App\Chapter;
use App\Photo;
use App\Serie;
use App\Store;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeneralSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('authors')->truncate();
        DB::table('books')->truncate();
        DB::table('chapters')->truncate();
        DB::table('photos')->truncate();
        DB::table('series')->truncate();
        DB::table('stores')->truncate();
        DB::table('book_store')->truncate();

        $faker = Faker\Factory::create();

        factory(Author::class, 50)->create()->each(function ($u) {
            for ($i = 0; $i < (($u->id + 1) % 3); ++$i) {
                $u->photos()->save(factory(Photo::class)->make());
            }
        });

        factory(Store::class, 50)->create()->each(function ($u) {
            for ($i = 0; $i < (($u->id + 1) % 3); ++$i) {
                $u->photos()->save(factory(Photo::class)->make());
            }
        });

        factory(Serie::class, 50)->create()->each(function ($u) {
            for ($i = 0; $i < (($u->id + 1) % 3); ++$i) {
                $u->photos()->save(factory(Photo::class)->make());
            }
        });

        factory(Book::class, 50)->create()->each(function ($u) use ($faker) {
            for ($i = 0; $i < (($u->id + 1) % 3); ++$i) {
                $u->photos()->save(factory(Photo::class)->make());
            }

            $store_id = $faker->randomElement(Store::all()->pluck('id')->toArray());
            $u->stores()->attach($store_id);
        });

        factory(Chapter::class, 50)->create()->each(function ($u) {
            $u->photos()->save(factory(Photo::class)->make());
        });

        // this assign allways a book for the first author, for example welcome page
        $book = Book::find(random_int(1, 50));
        $book->author_id = 1;
        $book->save();
    }
}
