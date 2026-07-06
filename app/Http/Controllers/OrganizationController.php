<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Http\Requests\OrganizationRequest; 
use App\Models\OrganizationPhones; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */




    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        $record         = Organization::FIRST();
        $phone_numbers  = DB::table('organization_phone_no')->get();
        $modelName      = class_basename(new Organization());
        if ($record) {
            return view('organization.index', compact('record', 'phone_numbers'));
        } else {
            return view('organization.index');
        }
    }
    public function getOrganizationPhones()
    {
        $phone_numbers = DB::table('organization_phone_no')->get();
        return response()->JSON([
            'status' => 'success',
            'msg' => 'success',
            'phone_numbers' => $phone_numbers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(OrganizationRequest $request)
    { 
        $checkCreate   = false;
        $logo          = '';
        $save_organization      = Organization::first();
        if (!($save_organization)) {
            $checkCreate        = true;
            $save_organization  = new Organization();
        }
        

        if ($request->hasFile('organization_logo')) {
            $completeFileName  = $request->file('organization_logo')->getClientOriginalName();
            $fileNameOnly      = pathinfo($completeFileName, PATHINFO_FILENAME);
            $extension         = $request->file('organization_logo')->getClientOriginalExtension();
            $empPicture        = str_replace(' ', '_', $fileNameOnly) . '_' . time() . '.' . $extension;
        
             $request->file('organization_logo')->move(public_path('organization'), $empPicture);
         
            if (! empty($save_organization->logo) && file_exists(public_path(ltrim($save_organization->logo, '/')))) {
                @unlink(public_path(ltrim($save_organization->logo, '/')));
            }
         
            $logo = '/organization/' . $empPicture;
        } else if (!empty($save_organization->logo)) {
            $logo = $save_organization->logo;
        }
        

        $organizationData = [
            'name'                => $request->name,
            'phone'               => $request->phone,
            'email'               => $request->email,
            'registration_number' => $request->registration_number,
            'vat_number'          => $request->vat_number,
            'postal_code'         => $request->postal_code,
            'address'             => $request->address,
            'facebook_link'       => $request->facebook_link,
            'instagram_link'      => $request->instagram_link,
            'linkedin_link'       => $request->linkedin_link,
            'youtube_link'        => $request->youtube_link,
            'twitter_link'        => $request->twitter_link,
            'logo'                => $logo,

        ];

        foreach ($request->type as $key => $types) {
            $phoneNo = OrganizationPhones::find($key);
            if (!$phoneNo) {
                $phoneNo = new OrganizationPhones();
            }
            $phoneNo->type            =  $types[0];
            $phoneNo->phone_number    =  $request->phone_numbers[$key][0];

            // Save or update the record
            $phoneNo->save();
        }
        if ($checkCreate) {
            $save_organization->create($organizationData);
        } else {
            $save_organization->Update($organizationData);
        }
        
        return response()->JSON([
            'status' => 'success',
            'msg' => 'success'
        ]);
    }
    public function generateOrganizationStructureTags($request, $logo)
    {
        $organizationData = [
            "@context"      => "http://schema.org",
            "@type"         => "Organization",
            "name"          => $request->name,
            "legalName"     => $request->name,
            "description"   => $request->meta_description,
            "telephone"     => $request->phone,
            "email"         => $request->email,
            "address" => [
                "@type" => "PostalAddress",
                "streetAddress" => $request->address,
                "postalCode"    => $request->postal_code,
            ],
            "taxID"              => $request->vat_number,
            "registrationNumber" => $request->registration_number,
            "sameAs" => [
                $request->facebook_link,
                $request->instagram_link,
                $request->linkedin_link,
                $request->youtube_link,
                $request->twitter_link
            ],
            "logo" => $logo
        ];

        // Step 2: Convert array to JSON-LD format
        $jsonLdData = json_encode($organizationData, JSON_PRETTY_PRINT);
        return $jsonLdData;
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return void
     */


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     */
    public function destroy(Request $request)
    {
        if ($request->id) {
            OrganizationPhones::find($request->id)->delete();
        }
        return response()->JSON([
            'status' => 'success',
            'msg' => 'deleted'
        ]);
    }
}
