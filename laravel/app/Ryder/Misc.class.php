<?php
namespace App\Custom;
use App\Parameter;

class Misc
{
    public static function translateFieldNameToHumanReadable($fieldName)
    {

        return ucwords(preg_replace('/(?<!\ )[A-Z]/', ' $0', str_replace('_', ' ', $fieldName)));

    }

    public static function determineDb2Server()
    {
        $enrichServerParameter = Parameter::getValueFromName('enrichServer');
        if ($enrichServerParameter) {
            return $enrichServerParameter;
        }

        if (function_exists('env') && env('DB2_SERVER')) {
            return env('DB2_SERVER');
        }

        if (defined('DB2_SERVER')) {
            return DB2_SERVER;
        }

    }

    public static function determineDb2DefaultLibrary()
    {
        $enrichLibraryParameter = Parameter::getValueFromName('enrichImportLibrary');
        if ($enrichLibraryParameter) {
            return $enrichLibraryParameter;
        }

        if (function_exists('env') && env('DB2_DEFAULTLIBRARY')) {
            return env('DB2_DEFAULTLIBRARY');
        }

        if (defined('DB2_DEFAULTLIBRARY')) {
            return DB2_DEFAULTLIBRARY;
        }

    }

    public static function laraveliseOrderBy($orderBy)
    {

        $orderBy = str_replace(
            array('createdAt', 'updatedAt', 'processedAt'),
            array('created_at', 'updated_at', 'processed_at'),
            $orderBy
        );


        return $orderBy;

    }

    public static function generateGuid()
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    public static function determineAppPath()
    {
        if (function_exists('env') && env('APP_PATH')) {
            return env('APP_PATH');
        }

        if (defined('APP_PATH')) {
            return APP_PATH;
        }

        return '/var/www/sushi';

    }

    public static function isIpv4AddressValid($ipv4Address) {

        if (!filter_var($ipv4Address, FILTER_VALIDATE_IP) === false) {
            return TRUE;
        }

        return FALSE;

    }
}
