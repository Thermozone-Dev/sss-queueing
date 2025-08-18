<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('priority_type', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
        });

        DB::table('priority_type')->insert([
            [
                'id' => 1,
                'name' => 'Senior Citizen (Nakakatanda)'
            ],
            [
                'id' => 2,
                'name' => 'Person with Disability (PWD) (May Kapansanan)'
            ],
            [
                'id' => 3,
                'name' => 'Pregnant Woman (Buntis)'
            ],
        ]);

        Schema::dropIfExists('queues');

        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->integer('queue_number',4)->nullable()->autoIncrement(false);
            $table->string('name',50);
            $table->string('mobile_num', 15)->nullable();
            $table->char('last_transacted_by', 36)->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->unsignedBigInteger('priority_type')->nullable();
            $table->unsignedBigInteger('status_id')->default(1)->nullable();

            $table->foreign('status_id')->references('id')->on('queue_statuses')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('priority_type')->references('id')->on('priority_type')->onUpdate('cascade')->onDelete(null);
            $table->foreign('transaction_id')->references('id')->on('transactions')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('last_transacted_by')->references('id')->on('users')->onUpdate('cascade')->onDelete(null);
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queues');
        Schema::dropIfExists('priority_type');
    }
};
