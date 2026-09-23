<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('lab_users',function(Blueprint $t){$t->uuid('id')->primary();$t->string('name');$t->string('email')->unique();$t->string('role')->default('analyst');$t->string('password_hash')->nullable();$t->boolean('active')->default(true);$t->timestamps();});
  Schema::create('analysis_reviews',function(Blueprint $t){$t->uuid('id')->primary();$t->foreignUuid('analysis_run_id')->constrained()->cascadeOnDelete();$t->string('reviewer');$t->string('decision');$t->text('comments')->nullable();$t->timestamp('reviewed_at')->nullable();$t->timestamps();});
  Schema::table('analysis_runs',function(Blueprint $t){$t->string('status')->default('DRAFT')->index();$t->string('approved_by')->nullable();$t->timestamp('approved_at')->nullable();});
 }
 public function down(): void {Schema::table('analysis_runs',function(Blueprint $t){$t->dropColumn(['status','approved_by','approved_at']);});Schema::dropIfExists('analysis_reviews');Schema::dropIfExists('lab_users');}
};