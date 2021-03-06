<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SystemTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('city');
            $table->string('country');
            $table->string('iata')->nullable();
            $table->string('icao');
            $table->double('lat');
            $table->double('lon');
            $table->longText('data')->nullable(); //JSON Data for All gate information for the system.
            $table->softDeletes();
        });

        Schema::create('airlines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('icao');
            $table->string('iata')->nullable();
            $table->string('name');
            $table->string('logo')->nullable(); // References Storage
            $table->string('widget')->nullable(); // References Storage
            $table->string('callsign');
            $table->softDeletes();
        });
        Schema::create('hubs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('airport_id')->unsigned();
            $table->foreign('airport_id')->references('id')->on('airports')->onDelete('cascade');
            $table->integer('airline_id')->unsigned();
            $table->foreign('airline_id')->references('id')->on('airlines')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('codeshares', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('airline_id')->unsigned();
            $table->foreign('airline_id')->references('id')->on('airlines')->onDelete('cascade');
            $table->string('weburl')->nullable();
            $table->string('apikey', 64);
            $table->timestamps();
        });

        Schema::create('aircraft_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('icao')->nullable();
            $table->boolean('userdefined');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('aircraft', function (Blueprint $table) {
            $table->increments('id');
            $table->string('icao');
            $table->string('name');
            $table->string('manufacturer');
            $table->string('registration');
            $table->integer('status');
            $table->integer('hub_id')->unsigned()->nullable();
            $table->foreign('hub_id')->references('id')->on('airports')->onDelete('set null');
            $table->integer('location_id')->unsigned()->nullable();
            $table->foreign('location_id')->references('id')->on('airports')->onDelete('set null');
            $table->integer('airline_id')->unsigned()->nullable();
            $table->foreign('airline_id')->references('id')->on('airlines')->onDelete('set null');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('aircraft_group_pivot', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('aircraft_id')->unsigned();
            $table->foreign('aircraft_id')->references('id')->on('aircraft')->onDelete('cascade');
            $table->integer('aircraft_group_id')->unsigned();
            $table->foreign('aircraft_group_id')->references('id')->on('aircraft_groups')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('airline_id')->unsigned();
            $table->foreign('airline_id')->references('id')->on('airlines')->onDelete('cascade');
            $table->string('flightnum');
            $table->integer('depapt_id')->unsigned();
            $table->foreign('depapt_id')->references('id')->on('airports')->onDelete('cascade');
            $table->integer('arrapt_id')->unsigned();
            $table->foreign('arrapt_id')->references('id')->on('airports')->onDelete('cascade');
            $table->integer('altapt_id')->unsigned()->nullable();
            $table->foreign('altapt_id')->references('id')->on('airports')->onDelete('cascade');
            $table->integer('aircraft_group_id')->nullable()->unsigned();
            $table->foreign('aircraft_group_id')->references('id')->on('aircraft_groups')->onDelete('set null');
            $table->boolean('seasonal');
            $table->date('startdate')->nullable();
            $table->date('enddate')->nullable();
            $table->time('deptime')->nullable();
            $table->time('arrtime')->nullable();
            $table->integer('type');
            $table->boolean('enabled');
            $table->text('defaults')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('flights', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('fo_id')->unsigned()->nullable();
            $table->foreign('fo_id')->references('id')->on('users')->onDelete('set null');
            $table->integer('airline_id')->unsigned();
            $table->foreign('airline_id')->references('id')->on('airlines')->onDelete('cascade');
            $table->string('flightnum');
            $table->integer('depapt_id')->unsigned();
            $table->foreign('depapt_id')->references('id')->on('airports')->onDelete('cascade');
            $table->integer('arrapt_id')->unsigned();
            $table->foreign('arrapt_id')->references('id')->on('airports')->onDelete('cascade');
            $table->integer('altapt_id')->unsigned()->nullable();
            $table->foreign('altapt_id')->references('id')->on('airports')->onDelete('cascade');
            $table->integer('aircraft_id')->unsigned();
            $table->foreign('aircraft_id')->references('id')->on('aircraft')->onDelete('cascade');
            $table->text('route')->nullable();
            $table->integer('cruise')->nullable();
            $table->text('route_data')->nullable();
            $table->integer('landingrate')->nullable();
            $table->text('load')->nullable();
            $table->time('deptime')->nullable();
            $table->time('arrtime')->nullable();
            $table->time('out')->nullable();
            $table->time('off')->nullable();
            $table->time('on')->nullable();
            $table->time('in')->nullable();
            $table->timestamps();
        });
        Schema::create('flight_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('flight_id')->unsigned();
            $table->foreign('flight_id')->references('id')->on('flights')->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('comment');
            $table->integer('type');
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
        //
        Schema::drop('schedules');
        Schema::drop('pireps');
        Schema::drop('airports');
        Schema::drop('aircraft');
        Schema::drop('settings');
        Schema::drop('hubs');
        Schema::drop('flights');
    }
}
