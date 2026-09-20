<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
  Schema::create('method_versions',function(Blueprint $t){$t->id();$t->string('code')->unique();$t->string('name');$t->string('standard');$t->string('version');$t->boolean('active')->default(true);$t->text('notes')->nullable();$t->timestamps();});
  Schema::create('samples',function(Blueprint $t){$t->id();$t->string('sample_code')->unique();$t->string('sample_name')->nullable();$t->string('matrix')->nullable();$t->timestamp('received_at')->nullable();$t->string('customer')->nullable();$t->text('notes')->nullable();$t->timestamps();});
  Schema::create('instruments',function(Blueprint $t){$t->id();$t->string('code')->unique();$t->string('name');$t->string('serial_number')->nullable();$t->string('calibration_due')->nullable();$t->string('status')->default('active');$t->timestamps();});
  Schema::create('reagents',function(Blueprint $t){$t->id();$t->string('code')->unique();$t->string('name');$t->string('lot_number')->nullable();$t->decimal('concentration',14,8)->nullable();$t->string('unit')->nullable();$t->date('expiry_date')->nullable();$t->timestamps();});
  Schema::create('qc_results',function(Blueprint $t){$t->id();$t->foreignId('analysis_run_id')->constrained('analysis_runs')->cascadeOnDelete();$t->string('qc_type');$t->decimal('expected_value',16,8)->nullable();$t->decimal('observed_value',16,8)->nullable();$t->decimal('difference_percent',16,8)->nullable();$t->string('status')->default('pending');$t->json('details')->nullable();$t->timestamps();});
 }
 public function down(): void { foreach(['qc_results','reagents','instruments','samples','method_versions'] as $t) Schema::dropIfExists($t); }
};