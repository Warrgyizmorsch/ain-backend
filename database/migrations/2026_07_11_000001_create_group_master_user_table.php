<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('group_master_user', function (Blueprint $table) { $table->id(); $table->foreignId('group_master_id')->constrained('group_masters')->cascadeOnDelete(); $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); $table->timestamps(); $table->unique(['group_master_id','user_id']); }); }
 public function down(): void { Schema::dropIfExists('group_master_user'); }
};
