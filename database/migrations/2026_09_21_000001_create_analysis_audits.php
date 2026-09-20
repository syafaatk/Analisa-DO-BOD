<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('analysis_audits',function(Blueprint $t){$t->uuid('id')->primary();$t->foreignUuid('analysis_run_id')->constrained('analysis_runs')->cascadeOnDelete();$t->string('actor');$t->string('action');$t->string('from_status')->nullable();$t->string('to_status')->nullable();$t->json('changes')->nullable();$t->text('notes')->nullable();$t->timestamps();$t->index(['analysis_run_id','created_at']);});
 }
 public function down(): void {Schema::dropIfExists('analysis_audits');}
};
