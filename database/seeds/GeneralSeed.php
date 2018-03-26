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
    public function run(): void
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

        factory(Author::class, 50)->create()->each(
            function ($u): void {
                for ($i = 0; $i < (($u->id + 1) % 3); ++$i) {
                    $u->photos()->save(factory(Photo::class)->make());
                }
            }
        );

        factory(Store::class, 50)->create()->each(
            function ($u): void {
                for ($i = 0; $i < (($u->id + 1) % 3); ++$i) {
                    $u->photos()->save(factory(Photo::class)->make());
                }
            }
        );

        factory(Serie::class, 50)->create()->each(
            function ($u): void {
                for ($i = 0; $i < (($u->id + 1) % 3); ++$i) {
                    $u->photos()->save(factory(Photo::class)->make());
                }
            }
        );

        factory(Book::class, 50)->create()->each(
            function ($u) use ($faker): void {
                for ($i = 0; $i < (($u->id + 1) % 3); ++$i) {
                    $u->photos()->save(factory(Photo::class)->make());
                }

                $store_id = $faker->randomElement(Store::all()->pluck('id')->toArray());
                $u->stores()->attach($store_id);
            }
        );

        factory(Chapter::class, 50)->create()->each(
            function ($u): void {
                $u->photos()->save(factory(Photo::class)->make());
            }
        );
    }
}
