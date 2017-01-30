<?php

use Illuminate\Database\Migrations\Migration;

class AddIndexToAuditData extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('auditData', function($table)
		{
            $table->index(['model', 'rowId'], 'auditData_model_rowId_index');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('auditData', function($table)
		{
            $table->dropIndex('auditData_model_rowId_index');
		});
	}

}
