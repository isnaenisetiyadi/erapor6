<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatatanWaliTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catatan_wali', function (Blueprint $table) {
            $table->uuid('catatan_wali_id');
			$table->uuid('sekolah_id');
			$table->uuid('anggota_rombel_id');
			$table->text('uraian_deskripsi');
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync')->nullable()->default(null);
			$table->primary('catatan_wali_id');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('anggota_rombel_id')->references('anggota_rombel_id')->on('anggota_rombel')
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
        Schema::table('catatan_wali', function (Blueprint $table) {
            $table->dropForeign(['anggota_rombel_id']);
			$table->dropForeign(['sekolah_id']);
        });
        Schema::dropIfExists('catatan_wali');
    }
}
