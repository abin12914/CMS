<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AddressRepository;
use App\Repositories\AccountRepository;
use App\Repositories\TransactionRepository;
use App\Http\Requests\AddressRegistrationRequest;
use App\Http\Requests\AddressFilterRequest;
use \Carbon\Carbon;
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
        $noOfRecords    = !empty($request->get('no_of_records')) ? $request->get('no_of_records') : $this->noOfRecordsPerPage;

        $params = [
                'wage_type' => $request->get('wage_type'),
                'id'        => $request->get('address_id'),
            ];
        
        return view('addresses.list', [
                'addresses'         => $this->addressRepo->getAddresses($params, $noOfRecords),
                'wageTypes'         => config('constants.addressWageTypes'),
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
        return view('addresses.register', [
                'wageTypes' => config('constants.addressWageTypes'),
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        AddressRegistrationRequest $request,
        AccountRepository $accountRepo,
        TransactionRepository $transactionRepo,
        $id=null
    ) {
        $saveFlag            = false;
        $errorCode           = 0;
        $address            = null;
        $addressAccount     = null;
        $addressTransaction = null;

        $openingBalanceAccountId    = config('constants.accountConstants.AccountOpeningBalance.id');
        $accountRelations           = config('constants.accountRelationTypes');

        $destination    = '/images/accounts/'; // image file upload path
        $fileName       = "";

        //upload image
        if ($request->hasFile('image_file')) {
            $file       = $request->file('image_file');
            $extension  = $file->getClientOriginalExtension(); // getting image extension
            $fileName   = $name.'_'.time().'.'.$extension; // rename image
            $file->move(public_path().$destination, $fileName); // uploading file to given path
            $fileName   = $destination.$fileName;//file name for saving to db
        }

        $financialStatus    = $request->get('financial_status');
        $openingBalance     = $request->get('opening_balance');
        $name               = $request->get('name');

        //wrappin db transactions
        DB::beginTransaction();
        try {
            //confirming opening balance existency.
            $openingBalanceAccount = $accountRepo->getAccount($openingBalanceAccountId);

            if(!empty($id)) {
                $address = $this->addressRepo->getAddress($id);
                $addressAccount = $accountRepo->getAccount($address->account_id);

                if($addressAccount->financial_status == 2){
                    $searchTransaction = [
                        ['paramName' => 'debit_account_id', 'paramOperator' => '=', 'paramValue' => $addressAccount->id],
                        ['paramName' => 'credit_account_id', 'paramOperator' => '=', 'paramValue' => $openingBalanceAccountId],
                    ];
                } else {
                    $searchTransaction = [
                        ['paramName' => 'debit_account_id', 'paramOperator' => '=', 'paramValue' => $openingBalanceAccountId],
                        ['paramName' => 'credit_account_id', 'paramOperator' => '=', 'paramValue' => $addressAccount->id],
                    ];
                }

                $addressTransaction = $transactionRepo->getTransactions($searchTransaction)->first();
            }

            //save to account table
            $accountResponse = $accountRepo->saveAccount([
                'account_name'      => $request->get('account_name'),
                'description'       => $request->get('description'),
                'relation'          => array_search('Addresses', $accountRelations), //address //key=1
                'financial_status'  => $financialStatus,
                'opening_balance'   => $openingBalance,
                'name'              => $name,
                'phone'             => $request->get('phone'),
                'address'           => $request->get('address'),
                'gstin'             => null,
                'image'             => $fileName,
                'status'            => 1,
            ], $addressAccount);

            if(!$accountResponse['flag']) {
                throw new AppCustomException("CustomError", $accountResponse['errorCode']);
            }

            //opening balance transaction details
            if($financialStatus == 1) { //incoming [account holder gives cash to company] [Creditor]
                $debitAccountId     = $openingBalanceAccountId; //cash flow into the opening balance account
                $creditAccountId    = $accountResponse['id']; //newly created account id [flow out from new account]
                $particulars        = "Opening balance of ". $name . " - Debit [Creditor]";
            } else if($financialStatus == 2){ //outgoing [company gives cash to account holder] [Debitor]
                $debitAccountId     = $accountResponse['id']; //newly created account id [flow into new account]
                $creditAccountId    = $openingBalanceAccountId; //flow out from the opening balance account
                $particulars        = "Opening balance of ". $name . " - Credit [Debitor]";
            } else {
                $debitAccountId     = $openingBalanceAccountId;
                $creditAccountId    = $accountResponse['id']; //newly created account id
                $particulars        = "Opening balance of ". $name . " - None";
                $openingBalance     = 0;
            }

            //save to transaction table
            $transactionResponse = $transactionRepo->saveTransaction([
                'debit_account_id'  => $debitAccountId,
                'credit_account_id' => $creditAccountId,
                'amount'            => $openingBalance,
                'transaction_date'  => Carbon::now()->format('Y-m-d'),
                'particulars'       => $particulars,
                'branch_id'         => 0,
            ], $addressTransaction);

            if(!$transactionResponse['flag']) {
                throw new AppCustomException("CustomError", $transactionResponse['errorCode']);
            }

            $addressResponse = $this->addressRepo->saveAddress([
                'account_id'    => $accountResponse['id'], //newly created account id
                'wage_type'     => $request->get('wage_type'),
                'wage_rate'     => $request->get('wage'),
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

            return redirect(route('address.show', $addressResponse['id']))->with("message","Address details saved successfully. Reference Number : ". $addressResponse['id'])->with("alert-class", "success");
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
                'wageTypes' => config('constants.addressWageTypes'),
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
            'wageTypes' => config('constants.addressWageTypes'),
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
        AccountRepository $accountRepo,
        TransactionRepository $transactionRepo,
        $id
    ) {
        $updateResponse = $this->store($request, $accountRepo, $transactionRepo, $id);

        if($updateResponse['flag']) {
            return redirect(route('address.show', $updateResponse['id']))->with("message","Address details updated successfully. Updated Record Number : ". $updateResponse['id'])->with("alert-class", "success");
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
