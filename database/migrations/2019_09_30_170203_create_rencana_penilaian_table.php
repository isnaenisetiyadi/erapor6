<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRencanaPenilaianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rencana_penilaian', function (Blueprint $table) {
            $table->uuid('rencana_penilaian_id');
			$table->uuid('sekolah_id');
			$table->uuid('pembelajaran_id');
			$table->integer('kompetensi_id');
			$table->string('nama_penilaian');
			$table->uuid('metode_id');
			$table->integer('bobot');
			$table->string('keterangan')->nullable();
			$table->uuid('rencana_penilaian_id_migrasi')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync')->nullable()->default(null);
			$table->primary('rencana_penilaian_id');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('pembelajaran_id')->references('pembelajaran_id')->on('pembelajaran')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('metode_id')->references('teknik_penilaian_id')->on('teknik_penilaian')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rencana_penilaian', function (Blueprint $table) {
            $table->dropForeign(['metode_id']);
			$table->dropForeign(['pembelajaran_id']);
			$table->dropForeign(['sekolah_id']);
        });
        Schema::dropIfExists('rencana_penilaian');
    }
}
