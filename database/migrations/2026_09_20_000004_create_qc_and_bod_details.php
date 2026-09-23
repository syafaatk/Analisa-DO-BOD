<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('bod_dilutions',function(Blueprint $t){$t->uuid('id')->primary();$t->foreignUuid('analysis_run_id')->constrained()->cascadeOnDelete();$t->decimal('sample_volume_ml',12,4);$t->decimal('final_volume_ml',12,4);$t->decimal('p',16,10);$t->decimal('do_initial',16,8);$t->decimal('do_final',16,8);$t->decimal('do_depletion',16,8)->nullable();$t->string('dilution_code')->nullable();$t->timestamps();});
  Schema::create('qc_observations',function(Blueprint $t){$t->uuid('id')->primary();$t->foreignUuid('analysis_run_id')->constrained()->cascadeOnDelete();$t->string('qc_type');$t->string('sample_code')->nullable();$t->decimal('replicate_1',16,8)->nullable();$t->decimal('replicate_2',16,8)->nullable();$t->decimal('rpd',16,8)->nullable();$t->decimal('target',16,8)->nullable();$t->decimal('lower_limit',16,8)->nullable();$t->decimal('upper_limit',16,8)->nullable();$t->string('status')->default('PENDING');$t->json('details')->nullable();$t->timestamps();});
 }
 public function down(): void {Schema::dropIfExists('qc_observations');Schema::dropIfExists('bod_dilutions');}
};