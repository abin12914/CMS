<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CertificateRepository;
use App\Repositories\AccountRepository;
use App\Repositories\TransactionRepository;
use App\Http\Requests\CertificateRegistrationRequest;
use App\Http\Requests\CertificateFilterRequest;
use \Carbon\Carbon;
use DB;
use Exception;
use App\Exceptions\AppCustomException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CertificateController extends Controller
{
    protected $certificateRepo;
    public $errorHead = null, $noOfRecordsPerPage = null;

    public function __construct(CertificateRepository $certificateRepo)
    {
        $this->certificateRepo         = $certificateRepo;
        $this->noOfRecordsPerPage   = config('settings.no_of_record_per_page');
        $this->errorHead            = config('settings.controller_code.CertificateController');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CertificateFilterRequest $request)
    {
        $noOfRecords    = !empty($request->get('no_of_records')) ? $request->get('no_of_records') : $this->noOfRecordsPerPage;

        $params = [
                'wage_type' => $request->get('wage_type'),
                'id'        => $request->get('certificate_id'),
            ];
        
        return view('certificates.list', [
                'certificates'         => $this->certificateRepo->getCertificates($params, $noOfRecords),
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
        return view('certificates.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        CertificateRegistrationRequest $request,
        $id=null
    ) {
        $saveFlag            = false;
        $errorCode           = 0;
        $certificate            = null;

        //wrappin db transactions
        DB::beginTransaction();
        try {
            if(!empty($id)) {
                $certificate = $this->certificateRepo->getCertificate($id);
            }

            $certificateResponse = $this->certificateRepo->saveCertificate([
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

            return redirect(route('certificate.show', $certificateResponse['id']))->with("message","Certificate details saved successfully. Reference Number : ". $certificateResponse['id'])->with("alert-class", "success");
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
            $certificate = $this->certificateRepo->getCertificate($id);
        } catch (Exception $e) {
        if($e->getMessage() == "CustomError") {
            $errorCode = $e->getCode();
        } else {
            $errorCode = 2;
        }
        //throwing methodnotfound exception when no model is fetched
        throw new ModelNotFoundException("Certificate", $errorCode);
    }
        return view('certificates.details', [
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
            $certificate = $this->certificateRepo->getCertificate($id);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 3;
            }
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Certificate", $errorCode);
        }

        return view('certificates.edit', [
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
        CertificateRegistrationRequest $request,
        AccountRepository $accountRepo,
        TransactionRepository $transactionRepo,
        $id
    ) {
        $updateResponse = $this->store($request, $accountRepo, $transactionRepo, $id);

        if($updateResponse['flag']) {
            return redirect(route('certificate.show', $updateResponse['id']))->with("message","Certificate details updated successfully. Updated Record Number : ". $updateResponse['id'])->with("alert-class", "success");
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
