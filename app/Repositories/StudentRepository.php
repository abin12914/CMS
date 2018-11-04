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
    public function getStudents($params=[], $noOfRecords=null, $typeFlag=true, $activeFlag=true, $relations=[])
    {
        $students = [];

        try {
            if(!empty($relations)) {
                $students = Student::with($relations);
            } else {
                $students = Student::query();
            }

            if($activeFlag) {
                $students = $students->active(); //status == 1
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
            dd($e);
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

        try {
            //student saving
            if(empty($student)) {
                $student = new Student;
            }
            $student->student_code  = $inputArray['student_code'];
            $student->name          = $inputArray['name'];
            $student->address       = $inputArray['address'];
            $student->phone         = $inputArray['phone'];
            $student->gender        = $inputArray['gender'];
            $student->title         = $inputArray['title'];
            $student->course_id     = $inputArray['course_id'];
            $student->from_year     = $inputArray['from_year'];
            $student->to_year       = $inputArray['to_year'];
            $student->fee_per_duration_unit  = $inputArray['fee'];
            $student->class_id      = $inputArray['class_id'];
            $student->status        = 1;
            //student save
            $student->save();

            $saveFlag = true;
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 2;
            }dd($e);

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
