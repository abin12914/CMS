<?php

namespace App\Repositories;

use App\Models\Student;
use Exception;
use App\Exceptions\AppCustomException;

class StudentRepository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.StudentRepository');
    }

    /**
     * Return students.
     */
    public function getStudents($params=[], $noOfRecords=null, $typeFlag=true, $activeFlag=true)
    {
        $students = [];

        try {
            $students = Student::query();

            if($activeFlag) {
                $students = $students->active(); //status == 1
            }

            if($typeFlag) {
                $typeId     = array_search('Personal', config('constants.studentTypes')); //type id=3 //personal student
                $students   = $students->where('type', $typeId);
            }

            foreach ($params as $key => $value) {
                if(!empty($value)) {
                    $students = $students->where($key, $value);
                }
            }

            if(!empty($noOfRecords) && $noOfRecords > 0) {
                $students = $students->paginate($noOfRecords);
            } else {
                $students= $students->get();
            }
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 1;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $students;
    }

    /**
     * Action for saving students.
     */
    public function saveStudent($inputArray, $student=null)
    {
        $saveFlag   = false;
        $typeId     = array_search('Personal', config('constants.studentTypes')); //type id=3 //personal student

        try {
            //student saving
            if(empty($student)) {
                $student = new Student;
            }
            $student->student_name      = $inputArray['student_name'];
            $student->description       = $inputArray['description'];
            $student->type              = $typeId; //type = personal student
            $student->relation          = $inputArray['relation'];
            $student->financial_status  = $inputArray['financial_status'];
            $student->opening_balance   = $inputArray['opening_balance'];
            $student->name              = $inputArray['name'];
            $student->phone             = $inputArray['phone'];
            $student->address           = $inputArray['address'];
            $student->image             = $inputArray['image'];
            $student->status            = $inputArray['status'];
            //student save
            $student->save();

            $saveFlag = true;
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 2;
            }

            throw new AppCustomException("CustomError", $this->errorCode);
        }

        if($saveFlag) {
            return [
                'flag'  => true,
                'id'    => $student->id,
            ];
        }
        return [
            'flag'      => false,
            'errorCode' => $this->repositoryCode + 3,
        ];
    }

    /**
     * return student.
     */
    public function getStudent($id, $activeFlag=true)
    {
        $student = [];

        try {
            $student = Student::query();
            
            if($activeFlag) {
                $student = $student->active();
            }

            $student = $student->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 4;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $student;
    }

    public function deleteStudent($id, $forceFlag=false)
    {
        return [
            'flag'          => false,
            'errorCode'    => $this->repositoryCode + 6,
        ];
    }
}
