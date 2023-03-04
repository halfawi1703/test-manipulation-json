<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataController extends Controller
{
    public function index()
    {
        // return storage_path();
        $customerDetail = json_decode(file_get_contents(storage_path() . "/customer-detail.json"), true);
        $workshop = json_decode(file_get_contents(storage_path() . "/workshop.json"), true);


        foreach ($customerDetail['data'] as $key => $value) {
       
            $workshopData = $workshop['data'][array_search($value['booking']['workshop']['code'], array_column($workshop['data'], 'code'))];

            $workshopDataCode = $value['booking']['workshop']['code'];
            
            $data[] = [
                "name" => $value['name'],
                "email" => $value['email'],
                "booking_number" => $value['booking']['booking_number'],
                "book_date" => $value['booking']['book_date'],
                "ahass_code" => $value['booking']['workshop']['code'],
                "ahass_name" => $value['booking']['workshop']['name'],
                "ahass_address" => $workshopDataCode == $workshopData['code'] ? $workshopData['address'] : null,
                "ahass_contact" => $workshopDataCode == $workshopData['code'] ? $workshopData['phone_number'] : null,
                "ahass_distance" => $workshopDataCode == $workshopData['code'] ? $workshopData['distance'] : 0,
                "motorcycle_ut_code" => $value['booking']['motorcycle']['ut_code'],
                "motorcycle" => $value['booking']['motorcycle']['name']
            ];
        }

        $result = [];
        foreach ($data as $key => $row)
        {
            $result[$key] = $row['ahass_distance'];
        }
        array_multisort($result, SORT_ASC, $data);

        $finalData = [
            'status' => 1,
            'message' => 'Data Successfully Retrieved.',
            'data' => $data
        ];

        return response()->json($finalData, 200);
    }
}
