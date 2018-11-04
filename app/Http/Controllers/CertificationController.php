<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CertificationRepository;
use App\Http\Requests\CertificationRegistrationRequest;
use App\Http\Requests\CertificationFilterRequest;
use \Carbon\Carbon;
use DB;
use Exception;
use App\Exceptions\AppCustomException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CertificationController extends Controller
{
    protected $certificateRepo;
    public $errorHead = null, $noOfRecordsPerPage = null;

    public function __construct(CertificationRepository $certificateRepo)
    {
        $this->certificateRepo      = $certificateRepo;
        $this->noOfRecordsPerPage   = config('settings.no_of_record_per_page');
        $this->errorHead            = config('settings.controller_code.CertificationController');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CertificationFilterRequest $request)
    {
        $noOfRecords    = !empty($request->get('no_of_records')) ? $request->get('no_of_records') : $this->noOfRecordsPerPage;

        $params = [
                'wage_type' => $request->get('wage_type'),
                'id'        => $request->get('certificate_id'),
            ];
        
        return view('certification.list', [
                'certification'         => $this->certificateRepo->getCertifications($params, $noOfRecords),
                'wageTypes'         => config('constants.certificateWageTypes'),
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
        return view('certification.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        CertificationRegistrationRequest $request,
        $id=null
    ) {
        $saveFlag            = false;
        $errorCode           = 0;
        $certificate            = null;

        //wrappin db transactions
        DB::beginTransaction();
        try {
            if(!empty($id)) {
                $certificate = $this->certificateRepo->getCertification($id);
            }

            $certificateResponse = $this->certificateRepo->saveCertification([
                'name'                  => $request->get('name'),
                'description'           => $request->get('description'),
                'authority_id'          => $request->get('authority_id'),
                'certificate_type'      => $request->get('certificate_type'),
                'certificate_content'   => $request->get('certificate_content'),
            ], $certificate);

            if(!$certificateResponse['flag']) {
                throw new AppCustomException("CustomError", $certificateResponse['errorCode']);
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
                    'id'    => $certificateResponse['id']
                ];
            }

            return redirect(route('certificate.show', $certificateResponse['id']))->with("message","Certification details saved successfully. Reference Number : ". $certificateResponse['id'])->with("alert-class", "success");
        }

        if(!empty($id)) {
            return [
                'flag'          => false,
                'errorCode'    => $errorCode
            ];
        }
        
        return redirect()->back()->with("message","Failed to save the certificate details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
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
        $certificate   = [];

        try {
            $certificate = $this->certificateRepo->getCertification($id);
        } catch (Exception $e) {
        if($e->getMessage() == "CustomError") {
            $errorCode = $e->getCode();
        } else {
            $errorCode = 2;
        }
        //throwing methodnotfound exception when no model is fetched
        throw new ModelNotFoundException("Certification", $errorCode);
    }
        return view('certification.details', [
                'certificate'  => $certificate,
                'wageTypes' => config('constants.certificateWageTypes'),
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
        $certificate   = [];

        try {
            $certificate = $this->certificateRepo->getCertification($id);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 3;
            }
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Certification", $errorCode);
        }

        return view('certification.edit', [
            'certificate'  => $certificate,
            'wageTypes' => config('constants.certificateWageTypes'),
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
        CertificationRegistrationRequest $request,
        AccountRepository $accountRepo,
        TransactionRepository $transactionRepo,
        $id
    ) {
        $updateResponse = $this->store($request, $accountRepo, $transactionRepo, $id);

        if($updateResponse['flag']) {
            return redirect(route('certificate.show', $updateResponse['id']))->with("message","Certification details updated successfully. Updated Record Number : ". $updateResponse['id'])->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to update the certificate details. Error Code : ". $this->errorHead. "/". $updateResponse['errorCode'])->with("alert-class", "error");
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
