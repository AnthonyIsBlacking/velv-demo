<?php

namespace App\Http\Controllers;

use App\Models\Server;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Properties\PropertiesController;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ServersController extends Controller
{

    // Get all Servers
    public function index(Request $request)
    {

        // Getting the values within the $request to see if it is empty or not
        $requestData = $request->all();


        // Copied from here
        $formattedServers = $this->get_list();
        // dd($test);


        // Check if we have request data, if so, we want to filter the servers according to what we're asked
        if( !empty($requestData['ram']) ){
            
            // Checking if there's a RAM filter
            if( isset($requestData['ram']) && !empty($requestData['ram']) ){
                $ram = $requestData['ram'];
            }else{
                $ram = '';
            }

            // Checking if there's a RAM filter
            if( isset($requestData['hdd']) && !empty($requestData['hdd']) ){
                $hdd = '';
            }else{
                $hdd = '';
            }

            // Checking if there's a RAM filter
            if( isset($requestData['location']) && !empty($requestData['location']) ){
                $location = '';
            }else{
                $location = '';
            }

            $criteria = [
                'ram' => '16GB', // Example RAM value
                // 'hdd' => '2TB', // Example HDD value
                // 'location' => 'Amsterdam', // Example location value
            ];

            // $formattedServersArray = $this->filter_search($formattedServers->toArray(), $ram, $hdd, $location);
            // $formattedServers = collect($formattedServersArray);

        }

        // dd($requestData);
        

        $formattedData = $formattedServers->values()->all();

        // dd($formattedData);

        // Get unique values of the columns to insert in the inputs/selects
        $input_values = [];
        $input_values['hdd'] = $this->get_unique_values_column($formattedData, 'hdd');
        $input_values['location'] = $this->get_unique_values_column($formattedData, 'location');
        $input_values['ram'] = $this->get_unique_values_column($formattedData, 'ram');

        // dd($input_values);

        // $hdd_unique_values = $this->get_unique_values_column($formattedData, 'hdd');
        // $ram_unique_values = $this->get_unique_values_column($formattedData, 'ram');
        // $location_unique_values = $this->get_unique_values_column($formattedData, 'location');

        
        // Return as JSON
        // return response()->json($formattedData);

        return view('servers',[
            'input_values' => $input_values,
        ]);
    }

    // Get servers
    public function server_list(Request $request)
    {

        // Getting the values within the $request to see if it is empty or not
        $requestData = $request->all();

        // Read data from the servers Excel file
        $servers = Excel::toCollection(null, storage_path('app/public/servers-excel/servers.xlsx'));

        // Retrieving only the servers and excluding the first row (consequently first item in the array, hence the slice)
        $data = $servers->first()->slice(1);

        // Retrieving only the headers
        // Also transforming the heads to small case just as a matter of good practice (at least to me :) )
        $headers = array_map('strtolower', $servers->first()->first()->toArray());


        // Combine the column headers with each row to create associative arrays
        $formattedServers = $data->map(function ($server) use ($headers) {
            // dump($servers);
            // dd($server);

            // Convert the row to an array
            $serverArray = $server->toArray();
            
            // Pad or truncate the row to match the number of column headers
            $serverArray = array_pad($serverArray, count($headers), '');

            // Combine the column headers with the row values
            return array_combine($headers, $serverArray);
        });



        // Check if we have request data, if so, we want to filter the servers according to what we're asked
        /*
        if( !empty($requestData['ram']) ){
            
            // Checking if there's a RAM filter
            if( isset($requestData['ram']) && !empty($requestData['ram']) ){
                $ram = $requestData['ram'];
            }else{
                $ram = '';
            }

            // Checking if there's a RAM filter
            if( isset($requestData['hdd']) && !empty($requestData['hdd']) ){
                $hdd = '';
            }else{
                $hdd = '';
            }

            // Checking if there's a RAM filter
            if( isset($requestData['location']) && !empty($requestData['location']) ){
                $location = '';
            }else{
                $location = '';
            }

            $criteria = [
                'ram' => '16GB', // Example RAM value
                // 'hdd' => '2TB', // Example HDD value
                // 'location' => 'Amsterdam', // Example location value
            ];

            // $formattedServersArray = $this->filter_search($formattedServers->toArray(), $ram, $hdd, $location);
            // $formattedServers = collect($formattedServersArray);

        }
        */

        // dd($requestData);
        

        $formattedData = $formattedServers->values()->all();

        // dd($formattedData);

        // Get unique values of the columns to insert in the inputs/selects
        // $input_values = [];
        // $input_values['hdd'] = $this->get_unique_values_column($formattedData, 'hdd');
        // $input_values['location'] = $this->get_unique_values_column($formattedData, 'location');
        // $input_values['ram'] = $this->get_unique_values_column($formattedData, 'ram');

        // dd($input_values);

        // $hdd_unique_values = $this->get_unique_values_column($formattedData, 'hdd');
        // $ram_unique_values = $this->get_unique_values_column($formattedData, 'ram');
        // $location_unique_values = $this->get_unique_values_column($formattedData, 'location');

        // Return as JSON
        return response()->json($formattedData);
    }

    // Get servers list
    public function get_list(){
        
        // Read data from the servers Excel file
        $servers = Excel::toCollection(null, storage_path('app/public/servers-excel/servers.xlsx'));

        // Retrieving only the servers and excluding the first row (consequently first item in the array, hence the slice)
        $data = $servers->first()->slice(1);

        // Retrieving only the headers
        // Also transforming the heads to small case just as a matter of good practice (at least to me :) )
        $headers = array_map('strtolower', $servers->first()->first()->toArray());


        // Combine the column headers with each row to create associative arrays
        $formattedServers = $data->map(function ($server) use ($headers) {
            // dump($servers);
            // dd($server);

            // Convert the row to an array
            $serverArray = $server->toArray();
            
            // Pad or truncate the row to match the number of column headers
            $serverArray = array_pad($serverArray, count($headers), '');

            // Combine the column headers with the row values
            return array_combine($headers, $serverArray);
        });

        return $formattedServers;

    }

    // Filter the server list according to the filters set
    function search($rows, $criteria)
    {

        // Fetch servers
        $servers = $this->get_list();
        $formattedData = $formattedServers->values()->all();

        return array_filter($rows, function ($row) use ($criteria) {
            foreach ($criteria as $field => $value) {
                // Check if the field exists in the row and matches the specified value
                if (isset($row[$field]) && strpos($row[$field], $value) === false) {
                    return false; // If any condition fails, exclude the row
                }
            }
            return true; // Include the row if all conditions are met
        });
    }

    // Get unique values of HDDs in the excel
    public function get_unique_values_column($array, $column){

        $unique_values = [];

        // Iterate through each server to collect unique HDD values
        foreach ($array as $server) {
            $hdd = $server[$column];
            if (!in_array($hdd, $unique_values)) {
                $unique_values[] = $hdd;
            }
        }

        return $unique_values;
    }

    public function store(Request $request)
    {

        return Server::create($request->all());
    }

    public function show(Server $article)
    {
        return $article;
    }

    // UPDATE
    public function update(Request $request, Server $article)
    {

        $article->update($request->all());

        return $article;
    }

    public function destroy(Server $article)
    {
        $article->delete();

        return response()->json(null, 204);
    }

}
