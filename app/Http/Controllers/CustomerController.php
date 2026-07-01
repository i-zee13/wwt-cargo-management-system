<?php

namespace App\Http\Controllers;

use App\Models\CustomerModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.customers.index');
    }
    public function getCustomersList()
    {
        $customers = CustomerModel::select('id', 'customer_picture', 'email', 'first_name', 'last_name', 'phone_number', 'status', 'address')->get();

        return response()->json([
            'status'        =>  'success',
            'msg'           =>  "customers data fetched",
            'customers'     =>  $customers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = null)
    {
        $customer = '';
        if ($id) {
            $customer = CustomerModel::find($id);
        }
        return view('admin.customers.create-customer', compact('customer'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $emailExist    = CustomerModel::where('id', '!=', $request->customer_updating_id)->where('email', $request->email)->exists();
        if ($emailExist) {
            
            return response()->json([
                'status' => 'error',
                'msg' => "Email is already associated with another customer"
            ]);
        }
        if ($request->customer_updating_id) {
            $customer = CustomerModel::find($request->customer_updating_id);
            if (!$customer) {
                return response()->json([
                    'status' => 'error',
                    'msg' =>    'Invalid Customer Record!!!'
                ]);
            }
            $customer->updated_at           =     Carbon::now();
            $customer->updated_by           =     Auth::user()->id;
        } else {
            $customer                       =     new CustomerModel();
            $customer->created_at           =     Carbon::now();
            $customer->created_by           =     Auth::user()->id;
            $customer->updated_at           =     NULL;
            $customer->updated_by           =     NULL;
        }
        if ($request->hasFile('customerPicture')) {
            $completeFileName           = $request->file('customerPicture')->getClientOriginalName();
            $fileNameOnly               = pathinfo($completeFileName, PATHINFO_FILENAME);
            $extension                  = $request->file('customerPicture')->getClientOriginalExtension();
            $empPicture                 = str_replace(' ', '_', $fileNameOnly) . '_' . time() . '.' . $extension;
            $path                       = $request->file('customerPicture')->storeAs('public/customers', $empPicture);
            if (Storage::exists('public/customers/' . str_replace('./storage/customers/', '', $customer->customer_picture))) {
                Storage::delete('public/customers/' . str_replace('./storage/customers/', '', $customer->customer_picture));
            }
            $customer->customer_picture          = '/storage/customers/' . $empPicture;
        }
        $customer->first_name               =     $request->first_name;
        $customer->last_name                =     $request->last_name;
        $customer->phone_number             =     $request->phone;
        $customer->email                    =     $request->email;
        $customer->city_id                  =     $request->city;
        $customer->state_id                 =     $request->state;
        $customer->country_id               =     $request->country;
        $customer->address                  =     $request->address; 
        if ($request->password) {
            $password                       =     bcrypt($request->password);
            $customer->password             =     $password; 
        }
        $customer->save();
        return response()->json([
            'status' => 'success',
            'msg' => $request->customer_updating_id ? 'Customer Updated Successfully' : 'Customer Added Successfully'
        ]); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {
        $customerId          = $request->customerId;
        $customer            = CustomerModel::find($customerId);
        $status              = $customer->status == 1 ? 0 : 1;
        $customer->status    = $status;
        $customer->save();
        return response()->json([
            'status' => 'success',
            'action' => $status
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
