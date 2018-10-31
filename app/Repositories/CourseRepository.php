<?php

namespace App\Repositories;

use App\Models\Course;
use Exception;
use App\Exceptions\AppCustomException;

class CourseRepository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.CourseRepository');
    }

    /**
     * Return courses.
     */
    public function getCourses($params=[], $noOfRecords=null)
    {
        $courses = [];

        try {
            $courses = Course::active();
            
            foreach ($params as $key => $value) {
                if(!empty($value)) {
                    $courses = $courses->where($key, $value);
                }
            }
            if(!empty($noOfRecords)) {
                $courses = $courses->paginate($noOfRecords);
            } else {
                $courses= $courses->get();
            }
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 1;
            }
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $courses;
    }

    /**
     * Action for saving course.
     */
    public function saveCourse($inputArray=[], $course=null)
    {
        $saveFlag = false;

        try {
            //employee saving
            if(empty($course)) {
                $course = new Course;
            }
            $course->name               = $inputArray['name'];
            $course->place              = $inputArray['place'];
            $course->address            = $inputArray['address'];
            $course->gstin              = $inputArray['gstin'];
            $course->primary_phone      = $inputArray['primary_phone'];
            $course->secondary_phone    = $inputArray['secondary_phone'];
            $course->level              = $inputArray['level'];
            $course->status             = 1;
            //course save
            $course->save();

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
                'id'    => $course->id,
            ];
        }

        return [
            'flag'      => false,
            'errorCode' => $repositoryCode + 3,
        ];
    }

    /**
     * return course.
     */
    public function getCourse($id)
    {
        $course = [];

        try {
            $course = Course::active()->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 4;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $course;
    }

    public function deleteCourse($id, $forceFlag=false)
    {
        return [
            'flag'          => false,
            'error_code'    => $this->repositoryCode + 6,
        ];
    }
}
