<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AddressRepository;
use App\Http\Requests\AddressRegistrationRequest;
use App\Http\Requests\AddressFilterRequest;
use DB;
use Exception;
use App\Exceptions\AppCustomException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AddressController extends Controller
{
    protected $addressRepo;
    public $errorHead = null, $noOfRecordsPerPage = null;

    public function __construct(AddressRepository $addressRepo)
    {
        $this->addressRepo         = $addressRepo;
        $this->noOfRecordsPerPage   = config('settings.no_of_record_per_page');
        $this->errorHead            = config('settings.controller_code.AddressController');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AddressFilterRequest $request)
    {
        $noOfRecords = !empty($request->get('no_of_records')) ? $request->get('no_of_records') : $this->noOfRecordsPerPage;

        $params = [
                'id' => $request->get('address_id'),
            ];
        
        return view('addresses.list', [
                'addresses'         => $this->addressRepo->getAddresses($params, $noOfRecords),
                'params'            => $params,
                'noOfRecords'       => $noOfRecords,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('addresses.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        AddressRegistrationRequest $request,
        $id=null
    ) {
        $saveFlag            = false;
        $errorCode           = 0;
        $address            = null;

        //wrappin db transactions
        DB::beginTransaction();
        try {
            if(!empty($id)) {
                $address = $this->addressRepo->getAddress($id);
            }

            $addressResponse = $this->addressRepo->saveAddress([
                'name'          => $request->get('name'),
                'designation'   => $request->get('designation'),
                'address'       => $request->get('address'),
                'title'         => $request->get('title'),
            ], $address);

            if(!$addressResponse['flag']) {
                throw new AppCustomException("CustomError", $addressResponse['errorCode']);
            }

            DB::commit();
            $saveFlag = true;
        } catch (Exception $e) {
            //roll back in case of exceptions
            DB::rollback();

            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 1;
            }
        }

        if($saveFlag) {
            if(!empty($id)) {
                return [
                    'flag'  => true,
                    'id'    => $addressResponse['id']
                ];
            }

            return redirect(route('address.index', $addressResponse['id']))->with("message","Address details saved successfully. Reference Number : ". $addressResponse['id'])->with("alert-class", "success");
        }

        if(!empty($id)) {
            return [
                'flag'          => false,
                'errorCode'    => $errorCode
            ];
        }
        
        return redirect()->back()->with("message","Failed to save the address details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $errorCode  = 0;
        $address   = [];

        try {
            $address = $this->addressRepo->getAddress($id);
        } catch (Exception $e) {
        if($e->getMessage() == "CustomError") {
            $errorCode = $e->getCode();
        } else {
            $errorCode = 2;
        }
        //throwing methodnotfound exception when no model is fetched
        throw new ModelNotFoundException("Address", $errorCode);
    }
        return view('addresses.details', [
                'address'  => $address,
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
        $errorCode  = 0;
        $address   = [];

        try {
            $address = $this->addressRepo->getAddress($id);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 3;
            }
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Address", $errorCode);
        }

        return view('addresses.edit', [
            'address'  => $address,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(
        AddressRegistrationRequest $request,
        $id
    ) {
        $updateResponse = $this->store($request, $id);

        if($updateResponse['flag']) {
            return redirect(route('address.index', $updateResponse['id']))->with("message","Address details updated successfully. Updated Record Number : ". $updateResponse['id'])->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to update the address details. Error Code : ". $this->errorHead. "/". $updateResponse['errorCode'])->with("alert-class", "error");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return redirect()->back()->with("message", "Deletion restricted.")->with("alert-class", "error");
    }
}
