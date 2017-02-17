<?php

namespace App\Http\Controllers;

use App\DamageReport;
use App\DefectReport;
use App\VehicleCheck;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ApiController extends Controller {


    public function index()
    {

        return [];

    }

    public function vehicle($unitNumber)
    {


        $output = [
            'damageReports' => [],
            'defectReports' => [],
            'vehicleChecks' => []
        ];

        $damageReports = DamageReport::where('unitNumber', $unitNumber)->orderBy('created_at', 'DESC')->get();

        foreach ($damageReports as $damageReport) {
            $output['damageReports'][$damageReport->id] = [
                'date' => date('d/m/Y', strtotime($damageReport->created_at)),
                'time' => date('H:i:s', strtotime($damageReport->created_at)),
                'driverFirstName' => $damageReport->driverFirstName,
                'driverLastName' => $damageReport->driverLastName,
                'damageLocation' => $damageReport->damageLocation,
                'damageDescription' => $damageReport->damageDescription,
                'damageDriverSignature' => $damageReport->damageDriverSignature
            ];
        }

        $defectReports = DefectReport::where('unitNumber', $unitNumber)->orderBy('created_at', 'DESC')->get();

        foreach ($defectReports as $defectReport) {
            $output['defectReports'][$defectReport->id] = [
                'date' => date('d/m/Y', strtotime($defectReport->created_at)),
                'time' => date('H:i:s', strtotime($defectReport->created_at)),
                'driverFirstName' => $defectReport->driverFirstName,
                'driverLastName' => $defectReport->driverLastName,
                'defectCategory' => $defectReport->defectCategory,
                'defectDescription' => $damageReport->defectDescription,
                'driverSignature' => $damageReport->driverSignature
            ];
        }

        $vehicleChecks = VehicleCheck::where('unitNumber', $unitNumber)->orderBy('created_at', 'DESC')->get();

        foreach ($vehicleChecks as $vehicleCheck) {
            $output['vehicleChecks'][$vehicleCheck->id] = [
                'date' => date('d/m/Y', strtotime($vehicleCheck->created_at)),
                'time' => date('H:i:s', strtotime($vehicleCheck->created_at)),
                'driverFirstName' => $vehicleCheck->driverFirstName,
                'driverLastName' => $vehicleCheck->driverLastName,
                'oil' => $vehicleCheck->oil,
                'water' => $vehicleCheck->water,
                'lights' => $vehicleCheck->lights,
                'tyres' => $vehicleCheck->tyres,
                'brakes' => $vehicleCheck->brakes
            ];
        }

        return $output;

    }

    public function damageReportCreate()
    {
        $item = new DamageReport();

        foreach ($item->getFillable() as $fieldName) {
            $item->$fieldName = Input::get($fieldName) ? Input::get($fieldName) : '';
        }
        $item->save();

        $output = array(
            'outcome' => 'Success',
            'id' => $item->id
        );

        return $output;

    }

    public function defectReportCreate()
    {
        $item = new DefectReport();

        foreach ($item->getFillable() as $fieldName) {
            $item->$fieldName = Input::get($fieldName) ? Input::get($fieldName) : '';
        }
        $item->save();

        $output = array(
            'outcome' => 'Success',
            'id' => $item->id
        );

        return $output;

    }

    public function vehicleCheckCreate()
    {
        $item = new VehicleCheck();

        foreach ($item->getFillable() as $fieldName) {

            if (in_array($fieldName, $item->booleanColumns)) {
                $value = Input::get($fieldName) ? 1 : 0;
            } else {
                $value = Input::get($fieldName) ? Input::get($fieldName) : '';
            }

            $item->$fieldName = $value;
        }
        $item->save();

        $output = array(
            'outcome' => 'Success',
            'id' => $item->id
        );

        return $output;

    }

}
