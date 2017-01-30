<?php
namespace App\Custom;
use App\Parameter;

/**
 *
 * @author Jack Parnell <Jack_Parnell@ryder.com>
 *
 */
class Db2Connection
{
	private static $conn;

	/**
	 * Get instance of DatabaseConnection singleton, creating it if it does not exist
	 * @return Db2Connection
	 */
	public static function getConnection()
	{
		if (!self::$conn) {
			new Db2Connection();
		}
		return self::$conn;
	}

	/**
	 * Construct database connection based on global variables.
	 */
	final private function __construct()
	{
        try {
            if (!extension_loaded('ibm_db2')) {
                throw new \Exception('ibm_db2 modules not loaded.', 75501);
            }
            $connectionString = 'DRIVER={IBM DB2 ODBC DRIVER};DATABASE='.env('DB2_NAME').';HOSTNAME='.Misc::determineDb2Server().';PORT='.env('DB2_PORT').';PROTOCOL='.env('DB2_PROTOCOL').';UID='.env('DB2_USER').';PWD='.env('DB2_PASS').';';

            // echo $connectionString.PHP_EOL;

            // $connectionString .= 'libraries=b402fd3a:PTF628P167:ENRICHUKW3:EMMSUKWEB3:UKEMMSWEB3:ASTPTFUKW3:ASTUKWEB3:UKASTWEB3;';

            $enrichLibraries = Parameter::getValueFromName('enrichLibraries');

            // $enrichLibraries = 'PTF628P167:UKEMMSDTA';

            $i5_libl = implode(
                ' ',
                explode(
                    ':',
                    $enrichLibraries
                )
            );

			self::$conn = db2_connect(
                $connectionString,
                '',
                '',
                array(
                    'i5_naming' => DB2_I5_NAMING_ON,
                    // 'i5_libl' => $i5_libl
                )
            );
        }
		catch (\Exception $e) {
            $message = db2_conn_error().' '.db2_conn_errormsg().PHP_EOL.db2_stmt_error().' '.db2_stmt_errormsg();
            if (strtoupper(PHP_SAPI) == 'CLI') {
                echo $enrichLibraries.PHP_EOL;
                echo $i5_libl.PHP_EOL;
                echo $message;
            }
            abort(500, $e->getMessage());
		}



	}

	/**
	 * Class is a singleton and cloning should not occur
	 */
	private function __clone()
	{
		die();
	}

}