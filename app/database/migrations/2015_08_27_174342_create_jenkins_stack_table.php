<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJenkinsStackTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		
		Schema::create('jenkins_stack',function($table))
		$table->increments('id');
		$table->text('question_title');
		$table->text('question');
		$table->foreign('u_id')->references('id')->on('users')

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		
		Schema::drop('jenkins_stack');
	}

}
?>