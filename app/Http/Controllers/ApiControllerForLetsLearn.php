<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\TokenService;

class ApiControllerForLetsLearn extends Controller
{    
    
    public function ll_fetchLeads(Request $request)
    {
        $token = TokenService::generateToken();
        $url = env('LL_BASE_URL') . '/api/ll-leads';

        // Get pagination and filter parameters
        $page = $request->input('page', 1);
        $queryParams = [
            'page' => $page,
            'additional_filter1' => $request->input('additional_filter1'),
            'additional_filter2' => $request->input('additional_filter2'),
            'additional_filter3' => $request->input('additional_filter3'),
            'additional_filter4' => $request->input('additional_filter4'),
            'additional_filter5' => $request->input('additional_filter5'),
            'additional_filter6' => $request->input('additional_filter6'),
            'additional_filter7' => $request->input('additional_filter7'),
            'additional_filter8' => $request->input('additional_filter8'),
        ];

        // Build query string with parameters
        $url .= '?' . http_build_query(array_filter($queryParams));

        // Initialize cURL request
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json',
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return back()->with('error', 'Error fetching leads: ' . curl_error($ch));
        }

        curl_close($ch);

        // Decode the JSON response
        $data = json_decode($response, true);

        // Extract leads and pagination information
        $leads = $data['data'];
        $pagination = [
            'current_page' => $data['current_page'],
            'last_page' => $data['last_page'],
            'per_page' => $data['per_page'],
            'total' => $data['total'],
        ];

        return view('letslearn.leads.ll_leads', compact('leads', 'pagination'));
    }



    public function updateLead(Request $request, $id)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'project_title' => 'required|string|max:255',
            'pages' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'deadline' => 'required|date',
            'work_type' => 'required|string|max:255',
            'service_type' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'assignment_details' => 'required|string',
        ]);

        // Prepare to send data to LL API
        $token = TokenService::generateToken();
        $url = env('LL_BASE_URL') ."/api/ll-leads/update/{$id}";  // Update API endpoint for LL

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); // Specify the request type
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($validatedData)); // Send validated data as JSON
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'Accept: application/json',
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return back()->with('error', 'Error updating lead: ' . curl_error($ch));
        }

        curl_close($ch);

        // Decode the JSON response from the API
        $responseData = json_decode($response, true);

        // Check if the response indicates success
        if (isset($responseData['message']) && $responseData['message'] === 'Lead updated successfully.') {
            return back()->with('success', 'Lead updated successfully on LL.');
        } else {
            return back()->with('error', 'Failed to update lead on LL.');
        }
    }
    
    public function convertLead(Request $request, $id)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'project_title' => 'required|string|max:255',
            'pages' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'deadline' => 'required|date|after_or_equal:today',
            'delivery_time' => 'nullable|string|max:100',
            'assignment_details' => 'required|string|max:255',
            'service_type' => 'required|string|max:255',
            'work_type' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
        ]);

        // Prepare to send data to LL API
        $token = TokenService::generateToken();
        $url = env('LL_BASE_URL') ."/api/ll-leads/convert/{$id}";  // Update API endpoint for LL

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); // Specify the request type
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($validatedData)); // Send validated data as JSON
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'Accept: application/json',
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return back()->with('error', 'Error converting lead: ' . curl_error($ch));
        }

        curl_close($ch);

        // Decode the JSON response from the API
        $responseData = json_decode($response, true);

        // Check if the response indicates success
        if (isset($responseData['message']) && $responseData['message'] === 'Lead converted successfully.') {
            return back()->with('success', 'Lead converted successfully on LL.');
        } else {
            return back()->with('error', 'Failed to convert lead on LL.');
        }
    }
    
    public function cancelLead(Request $request, $id)
    {
        // Validate the incoming request        
        $validatedData = $request->validate([
            'cancellation_message' => 'required|string|max:255',
        ]);

        // Prepare to send data to LL API
        $token = TokenService::generateToken();
        $url = env('LL_BASE_URL') ."/api/ll-leads/cancel/{$id}";  // Update API endpoint for LL

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); // Specify the request type
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($validatedData)); // Send validated data as JSON
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'Accept: application/json',
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return back()->with('error', 'Error cancel lead: ' . curl_error($ch));
        }

        curl_close($ch);

        // Decode the JSON response from the API
        $responseData = json_decode($response, true);

        // Check if the response indicates success
        if (isset($responseData['message']) && $responseData['message'] === 'Lead cancelled successfully.') {
            return back()->with('success', 'Lead cancelled successfully on LL.');
        } else {
            return back()->with('error', 'Failed to cancel lead on LL.');
        }
    }
    
    public function getFilesByOrder(Request $request)
    {
        // Validate the incoming request for order_id
        $validatedData = $request->validate([
            'order_id' => 'required',
        ]);

        // Prepare to send a request to LL API
        $token = TokenService::generateToken();
        $url = env('LL_BASE_URL') ."/api/ll-leads/files";  // LL API endpoint for fetching files
        // Initialize cURL session
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '?order_id=' . $validatedData['order_id']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json', // Change this to accept JSON
        ]);

        // Execute cURL request and capture the response
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Get the HTTP status code

        // Check for cURL errors
        if (curl_errno($ch)) {
            curl_close($ch);
            return response()->json(['error' => 'Error fetching files: ' . curl_error($ch)], 500);
        }

        curl_close($ch);

        // Handle different HTTP response codes
        if ($httpCode == 404) {
            return response()->json(['error' => 'No files found for this order.'], 404);
        }

        if ($httpCode == 200) {
            // Decode the JSON response
            $filesData = json_decode($response, true);

            if (isset($filesData['files']) && count($filesData['files']) > 0) {
                // Return the files data in the response
                return response()->json(['files' => $filesData['files']], 200);
            } else {
                return response()->json(['error' => 'Failed to retrieve files.'], 500);
            }
        }

        // If an unexpected HTTP code is returned
        return response()->json(['error' => 'Failed to retrieve files.'], 500);
    }

    public function checkUser(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
        ]);

        $token = TokenService::generateToken(); 
        $url = env('LL_BASE_URL') . "/api/ll-check-user"; 

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '?email=' . $validatedData['email']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            curl_close($ch);
            return response()->json(['error' => 'Error fetching user data: ' . curl_error($ch)], 500);
        }

        curl_close($ch);

        if ($httpCode == 200) {
            $userData = json_decode($response, true);

            if ($userData && isset($userData['user'])) {
                return response()->json(['exists' => true, 'user' => $userData['user']], 200);
            } else {
                return response()->json(['exists' => false, 'error' => 'User not found'], 404);
            }
        }

        return response()->json(['exists' => false, 'error' => 'Failed to retrieve user data'], $httpCode);
    }
    
    public function createLead(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'country_code' => 'required|numeric',
            'contact' => 'required|numeric|digits_between:9,15',
            'project_title' => 'required|string|max:255',
            'pages' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'deadline' => 'required|date|after_or_equal:today',
            'delivery_time' => 'nullable|string|max:100',
            'assignment_details' => 'required|string|max:255',
            'service_type' => 'required|string|max:255',
            'work_type' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
        ]);

        $token = TokenService::generateToken();
        $url = env('LL_BASE_URL') . "/api/ll-leads/create";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'Accept: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($validatedData));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            curl_close($ch);
            return response()->json(['error' => 'Error creating lead: ' . curl_error($ch)], 500);
        }

        curl_close($ch);
        $responseData = json_decode($response, true);
        
        if (isset($responseData['message']) && $responseData['message'] === 'Lead created successfully.') {
            return back()->with('success', 'Lead created successfully on LL.');
        } else {
            return back()->with('error', 'Failed to create lead on LL.');
        }
        
    }

    // c-leads on ll
    
    public function ll_fetchC_Leads(Request $request)
    {
        $token = TokenService::generateToken();
        $url = env('LL_BASE_URL') . '/api/ll-c-leads';

        // Get pagination and filter parameters
        $page = $request->input('page', 1);
        $queryParams = [
            'page' => $page,
            'additional_filter1' => $request->input('additional_filter1'),
            'additional_filter2' => $request->input('additional_filter2'),
            'additional_filter3' => $request->input('additional_filter3'),
            'additional_filter4' => $request->input('additional_filter4'),
            'additional_filter5' => $request->input('additional_filter5'),
            'additional_filter6' => $request->input('additional_filter6'),
            'additional_filter7' => $request->input('additional_filter7'),
            'additional_filter8' => $request->input('additional_filter8'),
        ];

        // Build query string with parameters
        $url .= '?' . http_build_query(array_filter($queryParams));

        // Initialize cURL request
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json',
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return back()->with('error', 'Error fetching c-leads: ' . curl_error($ch));
        }

        curl_close($ch);

        // Decode the JSON response
        $data = json_decode($response, true);

        // Extract leads and pagination information
        $leads = $data['data'];
        $pagination = [
            'current_page' => $data['current_page'],
            'last_page' => $data['last_page'],
            'per_page' => $data['per_page'],
            'total' => $data['total'],
        ];

        return view('letslearn.leads.ll_c_leads', compact('leads', 'pagination'));
    }

    public function restore(Request $request, $id)
    {
        $token = TokenService::generateToken();
        $url = env('LL_BASE_URL') . "/api/ll-leads/restore/{$id}";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'Accept: application/json',
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return back()->with('error', 'Error restoring lead: ' . curl_error($ch));
        }

        curl_close($ch);

        $responseData = json_decode($response, true);

        if (isset($responseData['message']) && $responseData['message'] === 'Lead restored successfully.') {
            return back()->with('success', 'Lead restored successfully on LL.');
        } else {
            return back()->with('error', 'Failed to restore lead on LL.');
        }
    }
    
    public function delete(Request $request, $id)
    {
        $token = TokenService::generateToken();
        $url = env('LL_BASE_URL') . "/api/ll-leads/delete/{$id}";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'Accept: application/json',
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return back()->with('error', 'Error deleting lead: ' . curl_error($ch));
        }

        curl_close($ch);

        $responseData = json_decode($response, true);

        if (isset($responseData['message']) && $responseData['message'] === 'Lead deleted successfully.') {
            return back()->with('success', 'Lead deleted successfully on LL.');
        } else {
            return back()->with('error', 'Failed to delete lead on LL.');
        }
    }

    // orders on ll
    
    public function ll_fetchOrders(Request $request)
    {
        $token = TokenService::generateToken();
        $url = env('LL_BASE_URL') . '/api/ll-orders';

        // Get pagination and filter parameters
        $page = $request->input('page', 1);
        $queryParams = [
            'page' => $page,
            'additional_filter1' => $request->input('additional_filter1'),
            'additional_filter2' => $request->input('additional_filter2'),
            'additional_filter3' => $request->input('additional_filter3'),
            'additional_filter4' => $request->input('additional_filter4'),
            'additional_filter5' => $request->input('additional_filter5'),
            'additional_filter6' => $request->input('additional_filter6'),
            'additional_filter7' => $request->input('additional_filter7'),
            'additional_filter8' => $request->input('additional_filter8'),
            'status' => $request->input('status'),
        ];

        // Build query string with parameters
        $url .= '?' . http_build_query(array_filter($queryParams));

        // Initialize cURL request
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json',
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return back()->with('error', 'Error fetching c-leads: ' . curl_error($ch));
        }

        curl_close($ch);

        // Decode the JSON response
        $data = json_decode($response, true);

        // Extract leads and pagination information
        $orders = $data['data'];
        $pagination = [
            'current_page' => $data['current_page'],
            'last_page' => $data['last_page'],
            'per_page' => $data['per_page'],
            'total' => $data['total'],
        ];

        return view('letslearn.orders.ll_orders', compact('orders', 'pagination'));
    }

    public function updateOrder(Request $request, $id)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'project_title' => 'required|string|max:255',
            'pages' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'deadline' => 'required|date|after_or_equal:today', // Ensure the deadline is not before today
            'delivery_time' => 'nullable|string|max:100',
            'assignment_details' => 'required|string|max:255',
            'service_type' => 'required|string|max:255',
            'work_type' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'order_status' => 'required|string|max:255',
        ]);

        // Prepare to send data to LL API
        $token = TokenService::generateToken();
        $url = env('LL_BASE_URL') ."/api/ll-order/update/{$id}";  // Update API endpoint for LL

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); // Specify the request type
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($validatedData)); // Send validated data as JSON
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'Accept: application/json',
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return back()->with('error', 'Error updating order: ' . curl_error($ch));
        }

        curl_close($ch);

        // Decode the JSON response from the API
        $responseData = json_decode($response, true);

        // Check if the response indicates success
        if (isset($responseData['message']) && $responseData['message'] === 'Order updated successfully.') {
            return back()->with('success', 'Order updated successfully on LL.');
        } else {
            return back()->with('error', 'Failed to update order on LL.');
        }
    }

    public function ll_SearchUser(Request $request)
    {
        $query = $request->input('user');

        // Generate API token (using a hypothetical TokenService here)
        $token = TokenService::generateToken();

        // LL API endpoint for searching users
        $url = env('LL_BASE_URL') . '/api/search-user?user=' . urlencode($query);

        // Initialize cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json',
        ]);

        // Execute cURL and fetch response
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return response()->json(['error' => 'Error fetching user data: ' . curl_error($ch)], 500);
        }
        curl_close($ch);

        // Decode the JSON response to an array and return it as JSON
        return response()->json(json_decode($response, true));
    }

}
