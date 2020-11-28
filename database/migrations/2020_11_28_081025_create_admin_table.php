<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->increments('idAdmin');
            $table->string('nama');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('hakAkses');
            $table->timestamps();
        });
        Schema::create('barang', function (Blueprint $table) {
            $table->increments('idBarang');
            $table->string('namaBarang');
            $table->string('kategori');
            $table->integer('harga');
            $table->timestamps();
        });
        Schema::create('barang_stock', function (Blueprint $table) {
            $table->increments('idBarangStock');
            $table->integer('idBarang')->unsigned();
            $table->string('ukuran');
            $table->integer('stock');
            $table->foreign('idBarang')->references('idBarang')->on('barang');
            $table->timestamps();
        });
        Schema::create('stocking', function (Blueprint $table) {
            $table->increments('idStocking');
            $table->timestamps();
        });
        Schema::create('detail_stocking', function(Blueprint $table) {
            $table->increments('idDetailStocking');
            $table->integer('idStocking')->unsigned();
            $table->integer('idBarangStock')->unsigned();
            $table->integer('jumlahBarang');
            $table->foreign('idStocking')->references('idStocking')->on('stocking');
            $table->foreign('idBarangStock')->references('idBarangStock')->on('barang_stock');
        });
        Schema::create('user', function(Blueprint $table) {
            $table->increments('idUser');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('nama');
        });
        Schema::create('checkout', function(Blueprint $table){
            $table->increments('idCheckout');
            $table->string('provinsi');
            $table->string('kota');
            $table->string('kecamatan');
            $table->string('kelurahan');
            $table->string('alamat');
            $table->string('atasNama');
            $table->string('noTelpon');
            $table->string('resi');
            $table->string('bukti');
            $table->timestamps();
        });
        Schema::create('cart', function(Blueprint $table) {
            $table->increments('idCart');
            $table->integer('idUser')->unsigned();
            $table->integer('idBarangStock')->unsigned();
            $table->integer('idCheckout')->unsigned()->nullable();
            $table->integer('jumlah');
            $table->foreign('idBarangStock')->references('idBarangStock')->on('barang_stock');
            $table->foreign('idCheckout')->references('idCheckout')->on('checkout');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin');
        Schema::dropIfExists('barang');
        Schema::dropIfExists('barang_stock');
        Schema::dropIfExists('stocking');
        Schema::dropIfExists('detail_stocking');
        Schema::dropIfExists('users');
        Schema::dropIfExists('checkout');
        Schema::dropIfExists('cart');

    }
}
