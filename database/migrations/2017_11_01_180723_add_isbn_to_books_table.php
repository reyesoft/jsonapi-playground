<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsbnToBooksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table(
            'books', function (Blueprint $table): void {
                $table->integer('isbn')->unsigned()->after('serie_id');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(
            'books', function (Blueprint $table): void {
                $table->dropColumn('isbn');
            }
        );
    }
}
