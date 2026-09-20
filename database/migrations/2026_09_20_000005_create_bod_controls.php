<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('bod_controls',function(Blueprint $t){$t->id();$t->foreignId('analysis_run_id')->constrained()->cascadeOnDelete();$t->string('control_type');$t->string('control_code')->nullable();$t->decimal('do_initial',16,8)->nullable();$t->decimal('do_final',16,8)->nullable();$t->decimal('bod_result',16,8)->nullable();$t->decimal('expected_min',16,8)->nullable();$t->decimal('expected_max',16,8)->nullable();$t->decimal('acceptance_percent',10,5)->nullable();$t->string('status')->default('PENDING');$t->text('notes')->nullable();$t->timestamps();});
 }
 public function down(): void {Schema::dropIfExists('bod_controls');}
};