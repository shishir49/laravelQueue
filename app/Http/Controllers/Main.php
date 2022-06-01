<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Jobs\customerData;
use App\Notifications\Email;
use App\Models\User;
use Illuminate\Support\Carbon;

class Main extends Controller
{
    public function index() {
        return view('welcome');
    }

    public function uploadCsv(Request $request) {
        
        $data = file($request->uploadCsv);
        $chunks = array_chunk($data,5);
        $path = resource_path('temp');

        foreach($chunks as $key => $chunk) {
            $name = "/tmp{$key}.csv";
            
            file_put_contents($path . $name, $chunk);
        }

        $files = glob("$path/*.csv");

        $header = []; 
        foreach($files as $key => $file) {
           $data = array_map('str_getcsv',file($file));

           if($key == 0) {
              $header = $data[0];
              
              unset($data[0]);
           }

           customerData::dispatch($data, $header);

           unlink($file);
        }

        $user = User::first();

        $user->notify((new Email())->delay(Carbon::now()->addSeconds(30)));

        return "done";
        
    }

    public function getCustomer(Request $request) {
        if($request->ajax()) {
            $branch_name = '';
            $gender      = '';

            if($request->branch_name != '' && $request->gender != '') {
               $output = Customer::where('branch_name', 'like', '%'.$request->branch_name.'%')->where('gender','like','%'.$request->gender.'%')->get();
            }else{
               $output = Customer::all();
            }

            return response()->json($output);

        }
    }
}
