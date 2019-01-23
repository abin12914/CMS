<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AuthorityRepository;
use App\Http\Requests\AuthorityRegistrationRequest;
use App\Http\Requests\AuthorityFilterRequest;
use DB;
use Exception;
use App\Exceptions\AppCustomException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthorityController extends Controller
{
    protected $authorityRepo;
    public $errorHead = null, $noOfRecordsPerPage = null;

    public function __construct(AuthorityRepository $authorityRepo)
    {
        $this->authorityRepo         = $authorityRepo;
        $this->noOfRecordsPerPage   = config('settings.no_of_record_per_page');
        $this->errorHead            = config('settings.controller_code.AuthorityController');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AuthorityFilterRequest $request)
    {
        $noOfRecords    = !empty($request->get('no_of_records')) ? $request->get('no_of_records') : $this->noOfRecordsPerPage;

        $params = [
                'id' => $request->get('authority_id'),
            ];
        
        return view('authorities.list', [
                'authorities'   => $this->authorityRepo->getAuthorities($params, $noOfRecords),
                'params'        => $params,
                'noOfRecords'   => $noOfRecords,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('authorities.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        AuthorityRegistrationRequest $request,
        $id=null
    ) {
        $errorCode  = 0;
        $authority  = null;

        //wrappin db transactions
        DB::beginTransaction();
        try {
            if(!empty($id)) {
                $authority = $this->authorityRepo->getAuthority($id);
            }

            $authorityResponse = $this->authorityRepo->saveAuthority([
                'name'          => $request->get('name'),
                'designation'   => $request->get('designation'),
            ], $authority);

            if(!$authorityResponse['flag']) {
                throw new AppCustomException("CustomError", $authorityResponse['errorCode']);
            }

            DB::commit();
            
            if(!empty($id)) {
                return [
                    'flag'  => true,
                    'id'    => $authorityResponse['id']
                ];
            }

            return redirect(route('authority.index'))->with("message","Authority details saved successfully. Reference Number : ". $authorityResponse['id'])->with("alert-class", "success");
        } catch (Exception $e) {
            //roll back in case of exceptions
            DB::rollback();

            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 1;
            }
        }

        if(!empty($id)) {
            return [
                'flag'          => false,
                'errorCode'    => $errorCode
            ];
        }
        
        return redirect()->back()->with("message","Failed to save the authority details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
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
        $authority   = [];

        try {
            $authority = $this->authorityRepo->getAuthority($id);
        } catch (Exception $e) {
        if($e->getMessage() == "CustomError") {
            $errorCode = $e->getCode();
        } else {
            $errorCode = 2;
        }
        //throwing methodnotfound exception when no model is fetched
        throw new ModelNotFoundException("Authority", $errorCode);
    }
        return view('authorities.details', [
                'authority'  => $authority,
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
        $authority   = [];

        try {
            $authority = $this->authorityRepo->getAuthority($id);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 3;
            }
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Authority", $errorCode);
        }

        return view('authorities.edit', [
            'authority'  => $authority,
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
        AuthorityRegistrationRequest $request,
        $id
    ) {
        $updateResponse = $this->store($request, $id);

        if($updateResponse['flag']) {
            return redirect(route('authority.index'))->with("message","Authority details updated successfully. Updated Record Number : ". $updateResponse['id'])->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to update the authority details. Error Code : ". $this->errorHead. "/". $updateResponse['errorCode'])->with("alert-class", "error");
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
