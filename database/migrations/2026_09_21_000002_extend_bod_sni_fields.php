<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::table('analysis_runs',function(Blueprint $t){$t->timestamp('sampling_at')->nullable();$t->timestamp('bod_incubation_start')->nullable();$t->timestamp('bod_incubation_end')->nullable();$t->decimal('sample_ph',6,3)->nullable();$t->decimal('sample_temperature',6,2)->nullable();$t->decimal('storage_temperature',6,2)->nullable();$t->decimal('storage_hours',10,2)->nullable();$t->string('storage_compliance')->nullable();$t->string('interference_treatment')->nullable();});
  Schema::table('bod_dilutions',function(Blueprint $t){$t->timestamp('dilution_at')->nullable();$t->timestamp('do_initial_at')->nullable();$t->timestamp('do_final_at')->nullable();$t->decimal('diluent_volume_ml',12,4)->nullable();$t->decimal('incubation_temperature',6,2)->nullable();$t->decimal('incubation_hours',10,2)->nullable();$t->string('selection_status')->default('PENDING');});
 };
 public function down(): void {
  Schema::table('bod_dilutions',function(Blueprint $t){$t->dropColumn(['dilution_at','do_initial_at','do_final_at','diluent_volume_ml','incubation_temperature','incubation_hours','selection_status']);});
  Schema::table('analysis_runs',function(Blueprint $t){$t->dropColumn(['sampling_at','bod_incubation_start','bod_incubation_end','sample_ph','sample_temperature','storage_temperature','storage_hours','storage_compliance','interference_treatment']);});
 }
};
