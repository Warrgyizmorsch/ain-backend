<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (Schema::hasTable('teams') && !Schema::hasColumn('teams', 'is_delete')) {
            Schema::table('teams', function (Blueprint $table) {
                $table->boolean('is_delete')->default(false);
            });
        }
    }

    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('is_delete');
        });
    }

};
