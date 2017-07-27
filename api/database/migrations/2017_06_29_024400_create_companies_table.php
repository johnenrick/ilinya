<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('companies');
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('business_type_id');
            $table->string('name',100);
            $table->string('code',100);
            $table->string('email', 100);
            $table->string('contact_number',20);
            $table->string('address',100);
            $table->decimal('location_lat', 10, 8)->nullable();
            $table->decimal('location_lng', 10, 8)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
