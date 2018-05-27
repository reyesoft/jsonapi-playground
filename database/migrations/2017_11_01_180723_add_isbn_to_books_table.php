<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

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
                $table->integer('isbn')->unsigned()->after('series_id');
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
