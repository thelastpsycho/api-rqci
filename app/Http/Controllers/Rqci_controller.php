<?php 

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Firebase\JWT\JWT;


class Rqci_controller extends Controller{

    function avg_room_score(){                         
            $url = "https://checklist.anvayabali.com/room/api_avgscore"; // Ganti dengan URL API JSON kamu

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // hasil sebagai string
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: application/json'
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            
            $data = json_decode($response, true);

            // Tampilkan hasil
           return response()->json($data);              
    }

    function room_score($room){                         
        $url = "https://checklist.anvayabali.com/room/api_score/$room"; // Ganti dengan URL API JSON kamu
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // hasil sebagai string
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        curl_close($ch);        
        $data = json_decode($response, true);        
       return response()->json($data);              
    }

    function list_defect($room){                         
        $url = "https://checklist.anvayabali.com/room/api_room_defect/$room"; // Ganti dengan URL API JSON kamu
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // hasil sebagai string
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        curl_close($ch);        
        $data = json_decode($response, true);        
       return response()->json($data);              
    }
    // function avg

}