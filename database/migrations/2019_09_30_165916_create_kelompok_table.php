<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKelompokTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref.kelompok', function (Blueprint $table) {
			$table->increments('kelompok_id');
			$table->string('nama_kelompok');
			$table->integer('kurikulum');
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('last_sync')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref.kelompok');
    }
}
