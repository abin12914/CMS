<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\BatchRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\AccountRepository;
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
        $fromDate       = !empty($request->get('from_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('from_date'))->format('Y-m-d') : "";
        $toDate         = !empty($request->get('to_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('to_date'))->format('Y-m-d') : "";
        $noOfRecords    = !empty($request->get('no_of_records')) ? $request->get('no_of_records') : $this->noOfRecordsPerPage;

        $params = [
            'from_date'     =>  [
                                    'paramName'     => 'date',
                                    'paramOperator' => '>=',
                                    'paramValue'    => $fromDate,
                                ],
            'to_date'       =>  [
                                    'paramName'     => 'date',
                                    'paramOperator' => '<=',
                                    'paramValue'    => $toDate,
                                ],
            'branch_id'     =>  [
                                    'paramName'     => 'branch_id',
                                    'paramOperator' => '=',
                                    'paramValue'    => $request->get('branch_id'),
                                ],
            'service_id'    =>  [
                                    'paramName'     => 'service_id',
                                    'paramOperator' => '=',
                                    'paramValue'    => $request->get('service_id'),
                                ],
        ];

        $relationalParams = [
            'supplier_account_id'   =>  [
                                            'relation'      => 'transaction',
                                            'paramName'     => 'credit_account_id',
                                            'paramValue'    => $request->get('supplier_account_id'),
                                        ]
        ];

        $batches       = $this->batchRepo->getBatches($params, $relationalParams, $noOfRecords);
        $totalBatch   = $this->batchRepo->getBatches($params, $relationalParams, null)->sum('bill_amount');

        //params passing for auto selection
        $params['from_date']['paramValue'] = $request->get('from_date');
        $params['to_date']['paramValue'] = $request->get('to_date');
        $params = array_merge($params, $relationalParams);
        
        return view('batches.list', [
            'batches'      => $batches,
            'totalBatch'  => $totalBatch,
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
        TransactionRepository $transactionRepo,
        AccountRepository $accountRepo,
        $id=null
    ) {
        $saveFlag           = false;
        $errorCode          = 0;
        $batch            = null;
        $batchTransaction = null;

        $batchAccountId   = config('constants.accountConstants.ServiceAndBatch.id');
        $transactionDate    = Carbon::createFromFormat('d-m-Y', $request->get('date'))->format('Y-m-d');
        $branchId           = $request->get('branch_id');
        $totalBill          = $request->get('bill_amount');

        //wrappin db transactions
        DB::beginTransaction();
        try {
            if(!empty($id)) {
                $batch = $this->batchRepo->getBatch($id);
                $batchTransaction = $transactionRepo->getTransaction($batch->transaction_id);
            }
            //confirming batch account exist-ency.
            $batchAccount = $accountRepo->getAccount($batchAccountId);

            //save batch transaction to table
            $transactionResponse   = $transactionRepo->saveTransaction([
                'debit_account_id'  => $batchAccountId, // debit the batch account
                'credit_account_id' => $request->get('supplier_account_id'), // credit the supplier
                'amount'            => $totalBill ,
                'transaction_date'  => $transactionDate,
                'particulars'       => $request->get('description')."[Purchase & Batch]",
                'branch_id'         => $branchId,
            ], $batchTransaction);

            if(!$transactionResponse['flag']) {
                throw new AppCustomException("CustomError", $transactionResponse['errorCode']);
            }

            //save to batch table
            $batchResponse = $this->batchRepo->saveBatch([
                'transaction_id' => $transactionResponse['id'],
                'date'           => $transactionDate,
                'service_id'     => $request->get('service_id'),
                'bill_amount'    => $totalBill,
                'branch_id'      => $branchId,
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

            return redirect(route('batch.index'))->with("message","Batch details saved successfully. Reference Number : ". $transactionResponse['id'])->with("alert-class", "success");
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
        TransactionRepository $transactionRepo,
        AccountRepository $accountRepo,
        $id
    ) {
        $updateResponse = $this->store($request, $transactionRepo, $accountRepo, $id);

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
