<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\StudentRepository;
use App\Repositories\TransactionRepository;
use App\Http\Requests\StudentRegistrationRequest;
use App\Http\Requests\StudentFilterRequest;
use \Carbon\Carbon;
use DB;
use Exception;
use App\Exceptions\AppCustomException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StudentController extends Controller
{
    protected $studentRepo;
    public $errorHead = null, $noOfRecordsPerPage = null;

    public function __construct(StudentRepository $studentRepo)
    {
        $this->studentRepo          = $studentRepo;
        $this->noOfRecordsPerPage   = config('settings.no_of_record_per_page');
        $this->errorHead            = config('settings.controller_code.StudentController');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StudentFilterRequest $request)
    {
        $noOfRecords    = !empty($request->get('no_of_records')) ? $request->get('no_of_records') : $this->noOfRecordsPerPage;

        $params = [
                'relation'      => $request->get('relation_type'),
                'id'            => $request->get('student_id'),
            ];
        
        return view('students.list', [
            'students'      => $this->studentRepo->getStudents($params, $noOfRecords, true, false),
            'relationTypes' => config('constants.studentRelationTypes'),
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
        return view('students.register', [
                'genderTypes' => config('constants.genderTypes'),
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        StudentRegistrationRequest $request,
        TransactionRepository $transactionRepo,
        $id=null
    ) {
        $saveFlag           = false;
        $errorCode          = 0;
        $student            = null;
        $studentTransaction = null;

        $openingBalanceStudentId = config('constants.studentConstants.StudentOpeningBalance.id');

        $financialStatus    = $request->get('financial_status');
        $openingBalance     = $request->get('opening_balance');
        $name               = $request->get('name');

        $destination    = '/images/students/'; // image file upload path
        $fileName       = "";

        //upload image
        if ($request->hasFile('image_file')) {
            $file       = $request->file('image_file');
            $extension  = $file->getClientOriginalExtension(); // getting image extension
            $fileName   = $name.'_'.time().'.'.$extension; // renaming image
            $file->move(public_path().$destination, $fileName); // uploading file to given path
            $fileName   = $destination.$fileName;//file name for saving to db
        }

        //wrappin db transactions
        DB::beginTransaction();
        try {
            //confirming opening balance existency.
            $openingBalanceStudent = $this->studentRepo->getStudent($openingBalanceStudentId);

            if(!empty($id)) {
                $student = $this->studentRepo->getStudent($id);

                if($student->financial_status == 2){
                    $searchTransaction = [
                        ['paramName' => 'debit_student_id', 'paramOperator' => '=', 'paramValue' => $student->id],
                        ['paramName' => 'credit_student_id', 'paramOperator' => '=', 'paramValue' => $openingBalanceStudentId],
                    ];
                } else {
                    $searchTransaction = [
                        ['paramName' => 'debit_student_id', 'paramOperator' => '=', 'paramValue' => $openingBalanceStudentId],
                        ['paramName' => 'credit_student_id', 'paramOperator' => '=', 'paramValue' => $student->id],
                    ];
                }

                $studentTransaction = $transactionRepo->getTransactions($searchTransaction)->first();
            }

            //save to student table
            $studentResponse   = $this->studentRepo->saveStudent([
                'student_name'      => $request->get('student_name'),
                'description'       => $request->get('description'),
                'relation'          => $request->get('relation_type'),
                'financial_status'  => $financialStatus,
                'opening_balance'   => $openingBalance,
                'name'              => $name,
                'phone'             => $request->get('phone'),
                'address'           => $request->get('address'),
                'image'             => $fileName,
                'status'            => 1,
            ], $student);

            if($studentResponse['flag']) {
                //opening balance transaction details
                if($financialStatus == 1) { //incoming [student holder gives cash to company] [Creditor]
                    $debitStudentId     = $openingBalanceStudentId; //cash flow into the opening balance student
                    $creditStudentId    = $studentResponse['id']; //newly created student id [flow out from new student]
                    $particulars        = "Opening balance of ". $name . " - Debit [Creditor]";
                } else if($financialStatus == 2){ //outgoing [company gives cash to student holder] [Debitor]
                    $debitStudentId     = $studentResponse['id']; //newly created student id [flow into new student]
                    $creditStudentId    = $openingBalanceStudentId; //flow out from the opening balance student
                    $particulars        = "Opening balance of ". $name . " - Credit [Debitor]";
                } else {
                    $debitStudentId     = $openingBalanceStudentId;
                    $creditStudentId    = $studentResponse['id']; //newly created student id
                    $particulars        = "Opening balance of ". $name . " - None";
                    $openingBalance     = 0;
                }
            } else {
                throw new AppCustomException("CustomError", $studentResponse['errorCode']);
            }

            //save to transaction table
            $transactionResponse   = $transactionRepo->saveTransaction([
                'debit_student_id'  => $debitStudentId,
                'credit_student_id' => $creditStudentId,
                'amount'            => $openingBalance,
                'transaction_date'  => Carbon::now()->format('Y-m-d'),
                'particulars'       => $particulars,
                'branch_id'         => 0,
            ], $studentTransaction);

            if(!$transactionResponse['flag']) {
                throw new AppCustomException("CustomError", $transactionResponse['errorCode']);
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
                    'id'    => $studentResponse['id']
                ];
            }
            return redirect(route('student.show', $studentResponse['id']))->with("message","Student details saved successfully. Reference Number : ". $studentResponse['id'])->with("alert-class", "success");
        }

        if(!empty($id)) {
            return [
                'flag'          => false,
                'errorCode'    => $errorCode
            ];
        }
        
        return redirect()->back()->with("message","Failed to save the student details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
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
        $student    = [];

        try {
            $student = $this->studentRepo->getStudent($id, false);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 2;
            }
            //throwing model not found exception when no model is fetched
            throw new ModelNotFoundException("Student", $errorCode);
        }

        return view('students.details', [
            'student'       => $student,
            'relationTypes' => config('constants.studentRelationTypes'),
            'studentTypes'  => config('constants.$studentTypes'),
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
        $student    = [];

        $relationTypes = config('constants.studentRelationTypes');
        //excluding the relationtype 'employee'[index = 1] for student update
        unset($relationTypes[1]);

        try {
            $student = $this->studentRepo->getStudent($id, false);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 3;
            }
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Student", $errorCode);
        }

        return view('students.edit', [
            'student'       => $student,
            'relationTypes' => $relationTypes,
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
        StudentRegistrationRequest $request,
        TransactionRepository $transactionRepo,
        $id)
    {
        $updateResponse = $this->store($request, $transactionRepo, $id);

        if($updateResponse['flag']) {
            return redirect(route('student.show', $updateResponse['id']))->with("message","Student details updated successfully. Updated Record Number : ". $updateResponse['id'])->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to update the student details. Error Code : ". $this->errorHead. "/". $updateResponse['errorCode'])->with("alert-class", "error");
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

    /**
     * return the specified resource.
     *
     * @param  int  $id
     * @return json
     */
    public function getDetails($id=null, TransactionRepository $transactionRepo)
    {
        $oldBalance['debit']    = 0;
        $oldBalance['credit']   = 0;

        if(empty($id)) {
            return [
                'flag'      => false,
                'message'   => "Invalid param",
            ];
        }
        $errorCode  = 0;
        $student    = [];

        try {
            $student    = $this->studentRepo->getStudent($id,false);
            $oldBalance = $transactionRepo->getOldBalance($id, null, null);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 2;
            }
            
            return [
                'flag'      => false,
                'message'   => "Record not found".$errorCode,
            ];
        }

        return [
            'flag'      => true,
            'student'   => [
                'name'      => $student->name,
                'phone'     => $student->phone,
                'address'   => $student->address,
                'type'      => $student->type,
            ],
            'oldBalance' => [
                'oldDebit'  => $oldBalance['debit'] ?: 0,
                'oldCredit' => $oldBalance['credit'] ?: 0,
            ],
        ];
    }
}
