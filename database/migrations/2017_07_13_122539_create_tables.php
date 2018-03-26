<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(
            'photos', function (Blueprint $table): void {
                $table->increments('id');
                $table->integer('photoable_id')->unsigned();
                $table->string('photoable_type');
                $table->string('title')->nullable();
                $table->string('uri')->nullable();
                $table->timestamps();
            }
        );

        Schema::create(
            'series', function (Blueprint $table): void {
                $table->increments('id');
                $table->string('title')->nullable();
                $table->timestamps();
            }
        );

        Schema::create(
            'authors', function (Blueprint $table): void {
                $table->increments('id');
                $table->string('name');
                $table->date('date_of_birth')->nullable();
                $table->date('date_of_death')->nullable();
                $table->timestamps();
            }
        );

        Schema::create(
            'books', function (Blueprint $table): void {
                $table->increments('id');
                $table->integer('author_id')->unsigned();
                $table->integer('serie_id')->nullable()->unsigned();
                $table->date('date_published');
                $table->string('title')->nullable();
                $table->timestamps();

                $table->foreign('author_id')->references('id')->on('authors');
                $table->foreign('serie_id')->references('id')->on('series');
            }
        );

        Schema::create(
            'chapters', function (Blueprint $table): void {
                $table->increments('id');
                $table->integer('book_id')->unsigned();
                $table->string('title')->nullable();
                $table->integer('ordering')->nullable();
                $table->timestamps();

                $table->foreign('book_id')->references('id')->on('books');
            }
        );

        Schema::create(
            'stores', function (Blueprint $table): void {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->timestamps();
            }
        );

        Schema::create(
            'book_store', function (Blueprint $table): void {
                $table->integer('book_id')->unsigned();
                $table->integer('store_id')->unsigned();
                $table->timestamps();

                $table->foreign('book_id')->references('id')->on('books');
                $table->foreign('store_id')->references('id')->on('stores');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('photos');
        Schema::dropIfExists('series');
        Schema::dropIfExists('authors');
        Schema::dropIfExists('books');
        Schema::dropIfExists('chapters');
        Schema::dropIfExists('stores');
        Schema::dropIfExists('book_store');
    }
}
