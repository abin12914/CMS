<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\BatchRepository;
use App\Http\Requests\BatchRegistrationRequest;
use App\Http\Requests\BatchFilterRequest;
use Carbon\Carbon;
use DB;
use Exception;
use App\Exceptions\AppCustomException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BatchController extends Controller
{
    protected $batchRepo;
    public $errorHead = null, $noOfRecordsPerPage = null;

    public function __construct(BatchRepository $batchRepo)
    {
        $this->batchRepo          = $batchRepo;
        $this->noOfRecordsPerPage   = config('settings.no_of_record_per_page');
        $this->errorHead            = config('settings.controller_code.BatchController');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BatchFilterRequest $request)
    {
        $noOfRecords    = !empty($request->get('no_of_records')) ? $request->get('no_of_records') : $this->noOfRecordsPerPage;

        $params = [
            'batch_id'  =>  [
                                'paramName'     => 'batch_id',
                                'paramOperator' => '=',
                                'paramValue'    => $request->get('batch_id'),
                            ],
            'course_id' =>  [
                                'paramName'     => 'course_id',
                                'paramOperator' => '=',
                                'paramValue'    => $request->get('course_id'),
                            ],
            'from_year' =>  [
                                'paramName'     => 'from_year',
                                'paramOperator' => '=',
                                'paramValue'    => $request->get('from_year'),
                            ],
            'to_year'   =>  [
                                'paramName'     => 'to_year',
                                'paramOperator' => '=',
                                'paramValue'    => $request->get('to_year'),
                            ],
        ];

        $batches    = $this->batchRepo->getBatches($params, [], $noOfRecords);
        
        return view('batches.list', [
            'batches'       => $batches,
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
        return view('batches.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        BatchRegistrationRequest $request,
        $id=null
    ) {
        $saveFlag         = false;
        $errorCode        = 0;
        $batch            = null;
        $batchTransaction = null;

        //wrappin db transactions
        DB::beginTransaction();
        try {
            if(!empty($id)) {
                $batch = $this->batchRepo->getBatch($id);
            }

            //save to batch table
            $batchResponse = $this->batchRepo->saveBatch([
                'batch_name'    => $request->get('batch_name'),
                'course_id'     => $request->get('course_id'),
                'from_year'     => $request->get('from_year'),
                'to_year'       => $request->get('to_year'),
                'fee_amount'    => $request->get('fee_amount'),
                'fee_per_year'  => $request->get('fee_per_year'),
                'fee_per_sem'   => $request->get('fee_per_sem'),
                'fee_per_month' => $request->get('fee_per_month'),
            ], $batch);

            if(!$batchResponse['flag']) {
                throw new AppCustomException("CustomError", $batchResponse['errorCode']);
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
                    'id'    => $batchResponse['id']
                ];
            }

            return redirect(route('batch.index'))->with("message","Batch details saved successfully. Reference Number : ". $batchResponse['id'])->with("alert-class", "success");
        }
        
        if(!empty($id)) {
            return [
                'flag'          => false,
                'errorCode'    => $errorCode
            ];
        }
        return redirect()->back()->with("message","Failed to save the batch details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
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
        $batch    = [];

        try {
            $batch = $this->batchRepo->getBatch($id);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 2;
            }
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Batch", $errorCode);
        }

        return view('batches.details', [
            'batch' => $batch,
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
        $batch    = [];

        try {
            $batch = $this->batchRepo->getBatch($id);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 3;
            }
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Batch", $errorCode);
        }

        return view('batches.edit', [
            'batch' => $batch,
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
        BatchRegistrationRequest $request,
        $id
    ) {
        $updateResponse = $this->store($request, $id);

        if($updateResponse['flag']) {
            return redirect(route('batch.index'))->with("message","Batch details updated successfully. Updated Record Number : ". $updateResponse['id'])->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to update the batch details. Error Code : ". $this->errorHead. "/". $updateResponse['errorCode'])->with("alert-class", "error");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleteFlag = false;
        $errorCode  = 0;

        //wrapping db transactions
        DB::beginTransaction();
        try {
            $deleteResponse = $this->batchRepo->deleteBatch($id);
            
            if(!$deleteResponse['flag']) {
                throw new AppCustomException("CustomError", $deleteResponse['errorCode']);
            }
            
            DB::commit();
            $deleteFlag = true;
        } catch (Exception $e) {
            //roll back in case of exceptions
            DB::rollback();

            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 5;
            }
        }

        if($deleteFlag) {
            return redirect(route('batch.index'))->with("message","Batch details deleted successfully.")->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to delete the batch details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }
}
