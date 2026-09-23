<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('clients',function(Blueprint $t){$t->uuid('id')->primary();$t->uuid('laboratory_id');$t->string('code',50);$t->string('name');$t->text('address')->nullable();$t->string('phone',50)->nullable();$t->string('email')->nullable();$t->boolean('active')->default(true);$t->timestamps();$t->unique(['laboratory_id','code']);});
  Schema::create('client_users',function(Blueprint $t){$t->uuid('id')->primary();$t->uuid('laboratory_id');$t->uuid('client_id');$t->string('name');$t->string('email');$t->string('password_hash');$t->string('role',30)->default('client_user');$t->boolean('active')->default(true);$t->timestamps();$t->unique(['laboratory_id','email']);});
  Schema::table('analysis_runs',function(Blueprint $t){$t->uuid('client_id')->nullable()->index();});
  Schema::create('client_report_accesses',function(Blueprint $t){$t->uuid('id')->primary();$t->uuid('laboratory_id');$t->uuid('analysis_run_id');$t->uuid('client_user_id');$t->string('action',20);$t->string('ip_address',45)->nullable();$t->text('user_agent')->nullable();$t->timestamp('accessed_at')->useCurrent();$t->timestamps();});
 }
 public function down(): void {Schema::dropIfExists('client_report_accesses');Schema::table('analysis_runs',fn(Blueprint $t)=>$t->dropColumn('client_id'));Schema::dropIfExists('client_users');Schema::dropIfExists('clients');}
};