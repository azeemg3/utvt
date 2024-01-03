<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\BusinessContact;
use App\Models\BusinessSetting;
use App\Models\BusinessSmtp;
use Illuminate\Http\Request;
use DB;

class BusinessSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $businessSetting = BusinessSetting::first();
        $businessContact=BusinessContact::first();
        $businessSmtp=BusinessSmtp::first();
        return view('settings.business_settings.index',compact('businessSetting','businessContact','businessSmtp'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules=[
            'business_name' => 'required',
            'business_email' => 'required',
            ];
            $message=[
            'business_name.required' => 'The Business Name required',
            'business_email.required' => 'The Business Email required',
        ];
        $this->validate($request,$rules,$message);
        $data = $request->all();
        $id = $request->id;
        //company contact
        $cdData['business_name']=$request->get('business_name');
        $cdData['business_email']=$request->get('business_email');
        $cdData['business_phone']=$request->get('business_phone');
        $cdData['business_phone']=$request->get('business_phone');
        $cdData['business_ntn']=$request->get('business_ntn');
        $cdData['business_license']=$request->get('business_license');
        $cdData['business_country']=$request->get('business_country');
        $cdData['business_city']=$request->get('business_city');
        $cdData['business_address']=$request->get('business_address');
        $cdData['business_other_details']=$request->get('business_other_details');
        //business contact details
        $bData['contact_name']=$request->get('contact_name');
        $bData['contact_mobile']=$request->get('contact_mobile');
        $bData['contact_wahts_app']=$request->get('contact_wahts_app');
        $bData['contact_email']=$request->get('contact_email');
        $bData['contact_country']=$request->get('contact_country');
        $bData['contact_city']=$request->get('contact_city');
        $bData['contact_address']=$request->get('contact_address');
        $bData['contact_address']=$request->get('contact_other_details');
        //Mail host setting optional
        $mData['mail_host']=$request->get('mail_host');
        $mData['mail_port']=$request->get('mail_port');
        $mData['mail_address']=$request->get('mail_address');
        $mData['mail_password']=$request->get('mail_password');
        $mData['mail_from']=$request->get('mail_from');
        $mData['mail_encryption']=$request->get('mail_encryption');
        DB::beginTransaction();
        try {
            if ($id == 0 || $id == '') {
                $ret = BusinessSetting::create($cdData);
                if($ret){
                        $bData['BID']=$ret->id;
                        BusinessContact::create($bData);
                        if(!empty($request->get('mail_host'))){
                            BusinessSmtp::create($mData);
                        }
                }
            }else{
                $ret = BusinessSetting::where('id',$id)->update($cdData);
                if($ret){
                        $bData['BID']=$id;
                        BusinessContact::where(['BID'=>$id])->update($bData );
                        if(!empty($request->get('mail_host'))){
                            BusinessSmtp::updateOrCreate($mData, ['BID'=>$id]);
                        }
                }
            }
            DB::commit();
            return $ret;
        } catch (QueryException $e) {
            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
