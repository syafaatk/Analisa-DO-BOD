<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::table('analysis_runs',function(Blueprint $t){$t->index(['parameter','status']);$t->index(['analysed_at']);$t->index(['sample_code','parameter']);});
  Schema::table('analysis_reviews',function(Blueprint $t){$t->index(['decision','reviewed_at']);});
  Schema::table('qc_observations',function(Blueprint $t){$t->index(['qc_type','status']);});
  Schema::table('bod_dilutions',function(Blueprint $t){$t->index(['analysis_run_id','dilution_code']);});
 };
 public function down(): void {
  Schema::table('analysis_runs',function(Blueprint $t){$t->dropIndex(['parameter','status']);$t->dropIndex(['analysed_at']);$t->dropIndex(['sample_code','parameter']);});
  Schema::table('analysis_reviews',function(Blueprint $t){$t->dropIndex(['decision','reviewed_at']);});
  Schema::table('qc_observations',function(Blueprint $t){$t->dropIndex(['qc_type','status']);});
  Schema::table('bod_dilutions',function(Blueprint $t){$t->dropIndex(['analysis_run_id','dilution_code']);});
 }
};