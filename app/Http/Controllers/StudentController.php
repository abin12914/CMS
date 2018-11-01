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
        $id=null
    ) {
        $saveFlag           = false;
        $errorCode          = 0;
        $student            = null;

        //wrappin db transactions
        DB::beginTransaction();
        try {
            if(!empty($id)) {
                $student = $this->studentRepo->getStudent($id);
            }

            //save to student table
            $studentResponse   = $this->studentRepo->saveStudent([
                'student_code'  => $request->get('student_code'),
                'name'          => $request->get('name'),
                'address'       => $request->get('address'),
                'phone'         => $request->get('phone'),
                'gender'        => $request->get('gender'),
                'title'         => $request->get('title'),
                'batch_id'      => $request->get('batch_id'),
                'status'        => 1,
            ], $student);

            if(!$studentResponse['flag']) {
                throw new AppCustomException("CustomError", $studentResponse['errorCode']);
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
        return redirect()->back()->with("message","Editing disabled!")->with("alert-class", "error");
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
