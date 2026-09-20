<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
  Schema::create('uncertainty_models',function(Blueprint $t){$t->id();$t->string('code')->unique();$t->string('parameter');$t->string('model_name');$t->decimal('coverage_factor',10,5)->default(2);$t->string('unit')->nullable();$t->boolean('active')->default(true);$t->text('notes')->nullable();$t->timestamps();});
  Schema::create('uncertainty_components',function(Blueprint $t){$t->id();$t->foreignId('uncertainty_model_id')->constrained()->cascadeOnDelete();$t->string('code');$t->string('name');$t->string('source_type');$t->string('distribution')->nullable();$t->decimal('standard_uncertainty',20,10)->nullable();$t->decimal('sensitivity_coefficient',20,10)->nullable();$t->decimal('contribution',20,10)->nullable();$t->decimal('degrees_of_freedom',20,10)->nullable();$t->decimal('relative_contribution_percent',12,6)->nullable();$t->json('observations')->nullable();$t->timestamps();});
 }
 public function down(): void { Schema::dropIfExists('uncertainty_components'); Schema::dropIfExists('uncertainty_models'); }
};