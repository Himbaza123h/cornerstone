<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function index()
    {
        return view('landing.index');
    }

    public function view()
    {
        return view('donate.view');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'donator_names' => 'required|string|max:255',
            'donator_phone' => 'required|string|min:10|max:12',
            'amount' => 'required|integer|min:0',
            'payment_method' => 'required|string|max:255',
        ]);

        //PAYMENT

        //default parameter for payment
        $random_number = rand(1, 100);
        $refid = time() . '' . rand(100, 999) . '' . rand(1000, 9999);
        $email = 'tonynerd8@gmail.com';
        $details = 'Donation Payment';
        $currency = 'RWF';
        $returl = '/';
        $redirecturl = '/payment-page';
        $retailerid = $random_number;

        if ($request->payment_method == 'momo') {
            $bankid = '63510';
            $payment_number = $request->donator_phone;
            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'The Phone number must not be greater than 12 characters',
                        'error' => $validator->errors(),
                    ],
                    400,
                );
            }
        } else {
            $payment_number = $request->donator_phone;
            $bankid = '000';
        }

        $donator_phone = $request->donator_phone;
        if (substr(preg_replace('/[^0-9]/', '', $donator_phone), 0, 4) == '2507' && strlen($donator_phone) == 12) {
            $donator_phone = $donator_phone;
            $rightphone = true;
        } else {
            if (substr(preg_replace('/[^0-9]/', '', $donator_phone), 0, 2) == '07' && strlen($donator_phone) == 10) {
                $donator_phone = '25' . $donator_phone;
                $rightphone = true;
            } else {
                $rightphone = false;
            }
        }
        if (!$rightphone) {
            // response
            $response = [
                'status' => false,
                'message' => 'Invalid phone number. 07********',
            ];
            return response()->json($response);
        }

        // Create an array to hold the form data
        $form_data = [
            // 'action' => $action,
            'msisdn' => $payment_number,
            'email' => $email,
            'details' => $details,
            'refid' => $refid,
            'amount' => $request->amount,
            'currency' => $currency,
            'cname' => $request->donator_names,
            'cnumber' => $request->amount,
            'pmethod' => $request->payment_method,
            'retailerid' => '01',
            'returl' => $returl,
            'redirecturl' => $redirecturl,
            'bankid' => $bankid,
        ];
        // Encode the form data as a JSON object
        $json_data = json_encode($form_data);

        $ch = curl_init('https://pay.esicia.com');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_USERPWD, 'cornerstone:7Pqa9n');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Send the JSON data to the server
        $responses = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }

        // Close the cURL session
        curl_close($ch);
        // var_dump($json_data);

        $Payment = new Payment();
        $Payment->reference_id = $refid;
        $Payment->donator_names = $request->donator_names;
        $Payment->amount = $request->amount;
        $Payment->donator_phone = $request->donator_phone;
        $Payment->payment_method = $request->payment_method;
        $Payment->payment_status = 'pending';

        try {
            $Payment->save();
            return redirect()
                ->route('donate.view')
                ->with('success', 'Your Payment received successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Something went wrong! try again. ' . $e->getMessage()]);
        }
    }
}
