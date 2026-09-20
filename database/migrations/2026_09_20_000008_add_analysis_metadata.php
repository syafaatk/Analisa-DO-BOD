<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::table('analysis_runs',function(Blueprint $t){$t->string('sample_matrix')->nullable();$t->string('batch_code')->nullable()->index();$t->string('instrument_code')->nullable();$t->string('reagent_lot')->nullable();$t->string('report_number')->nullable()->unique();$t->timestamp('submitted_at')->nullable();});
 }
 public function down(): void {Schema::table('analysis_runs',function(Blueprint $t){$t->dropColumn(['sample_matrix','batch_code','instrument_code','reagent_lot','report_number','submitted_at']);});}
};