<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('laboratories',function(Blueprint $t){$t->uuid('id')->primary();$t->string('code')->unique();$t->string('name');$t->boolean('active')->default(true);$t->text('address')->nullable();$t->string('phone')->nullable();$t->string('email')->nullable();$t->timestamps();});
  $labId=(string) Str::uuid(); DB::table('laboratories')->insert(['id'=>$labId,'code'=>'LAB-DEFAULT','name'=>'Default Laboratory','active'=>true,'created_at'=>now(),'updated_at'=>now()]);
  $tables=['analysis_runs','method_versions','samples','instruments','reagents','qc_results','uncertainty_models','uncertainty_components','bod_dilutions','qc_observations','bod_controls','analysis_reviews','analysis_audits'];
  foreach($tables as $table){
   Schema::table($table,function(Blueprint $t){$t->foreignUuid('laboratory_id')->nullable()->after('id')->index();});
   DB::table($table)->update(['laboratory_id'=>$labId]);
  }
  Schema::table('lab_users',function(Blueprint $t){$t->foreignUuid('laboratory_id')->nullable()->after('id')->index();});
  DB::table('lab_users')->update(['laboratory_id'=>$labId]);
  foreach([['method_versions','code'],['samples','sample_code'],['instruments','code'],['reagents','code'],['uncertainty_models','code']] as [$table,$column]){
   Schema::table($table,function(Blueprint $t) use($table,$column){$t->dropUnique($table.'_'.$column.'_unique');});
   Schema::table($table,function(Blueprint $t) use($column){$t->unique(['laboratory_id',$column]);});
  }
  Schema::table('lab_users',function(Blueprint $t){$t->unique(['laboratory_id','email']);});
 }
 public function down(): void {
  foreach(['analysis_audits','analysis_reviews','bod_controls','qc_observations','bod_dilutions','uncertainty_components','uncertainty_models','qc_results','reagents','instruments','samples','method_versions','analysis_runs'] as $table) Schema::table($table,function(Blueprint $t){$t->dropColumn('laboratory_id');});
  Schema::table('lab_users',function(Blueprint $t){$t->dropUnique('lab_users_laboratory_id_email_unique');$t->dropColumn('laboratory_id');});
  Schema::dropIfExists('laboratories');
 }
};