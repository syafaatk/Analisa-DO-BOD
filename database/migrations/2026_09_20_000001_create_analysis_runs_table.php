<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration{
 public function up():void{Schema::create('analysis_runs',function(Blueprint $t){$t->uuid('id')->primary();$t->string('sample_code')->index();$t->enum('parameter',['DO','BOD5']);$t->string('method_version');$t->string('analyst')->nullable();$t->timestamp('analysed_at')->nullable();$t->json('inputs');$t->json('calculation')->nullable();$t->json('uncertainty')->nullable();$t->text('notes')->nullable();$t->timestamps();});}
 public function down():void{Schema::dropIfExists('analysis_runs');}
};
