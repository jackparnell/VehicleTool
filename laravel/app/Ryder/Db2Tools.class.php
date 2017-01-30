<?php
namespace App\Custom;

/**
 * Class Db2Tools
 *
 * Note: This file may not be suitable for copying and pasting between applications.
 *
 * Determination of @DB2_DEFAULTLIBRARY may differ between applications.
 */
class Db2Tools
{
	public static function generateDb2Sql($queryName, $parameters=array())
	{
		if (!is_array($parameters)) {
			return '';
		}

		$sqlFilePath = Misc::determineAppPath().'/laravel/database/sql/db2/'.$queryName.'.sql';
		if (!file_exists($sqlFilePath)) {
            throw new \RuntimeException('Fetching of DB2 SQL failed. File does not exist at : '.$sqlFilePath);
		}
		$sql = file_get_contents($sqlFilePath);

        if (!$sql) {
            throw new \RuntimeException('Fetching of DB2 SQL failed. File path was: '.$sqlFilePath);
        }

		$sql = str_replace(
			array('@DB2_DEFAULTLIBRARY'),
			array(Misc::determineDb2DefaultLibrary()),
			$sql
		);

		foreach ($parameters as $key => $value) {
			if (is_array($value)) {
				// If value is an array, assume it's going to be used in an IN()
				$inString = '';
				foreach ($value as $arrayValue) {
					$inString .= "'".db2_escape_string( (string) trim($arrayValue) )."',";
				}
				$inString = rtrim($inString, ',');

				$sql = str_replace($key, $inString, $sql);

			} else {

				if ($key == ':typeColumnName') {
					$sql = str_replace($key, db2_escape_string( (string) trim($value) ), $sql);
				} else {
					$sql = str_replace($key, "'".db2_escape_string( (string) trim($value) )."'", $sql);
				}
			}
		}

		// echo $sql.PHP_EOL;

		return $sql;

	}
}