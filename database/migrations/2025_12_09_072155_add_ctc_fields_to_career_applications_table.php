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
    Schema::table('career_applications', function (Blueprint $table) {
        $table->string('current_ctc')->nullable()->after('role_title');
        $table->string('expected_ctc')->nullable()->after('current_ctc');
        $table->string('notice_period')->nullable()->after('expected_ctc');
    });
}

public function down()
{
    Schema::table('career_applications', function (Blueprint $table) {
        $table->dropColumn(['current_ctc', 'expected_ctc', 'notice_period']);
    });
}

};
