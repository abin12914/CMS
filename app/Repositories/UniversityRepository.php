<?php

namespace App\Repositories;

use App\Models\University;
use Exception;
use App\Exceptions\AppCustomException;

class UniversityRepository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.UniversityRepository');
    }

    /**
     * Return accounts.
     */
    public function getUniversities($params=[], $noOfRecords=null)
    {
        $universities = [];

        try {
            $universities = University::active();

            foreach ($params as $key => $value) {
                if(!empty($value)) {
                    $universities = $universities->where($key, $value);
                }
            }
            if(!empty($noOfRecords)) {
                $universities = $universities->paginate($noOfRecords);
            } else {
                $universities= $universities->get();
            }
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 1;
            }

            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $universities;
    }

    /**
     * Action for saving accounts.
     */
    public function saveUniversity($inputArray, $university=null)
    {
        $saveFlag = false;

        try {
            if(empty($university)) {
                $university = new University;
            }

            //university saving
            $university->university_name  = $inputArray['university_name'];
            $university->center_code      = $inputArray['center_code'];
            $university->university_grade = $inputArray['university_grade'];
            $university->status           = 1;
            //university save
            $university->save();

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
                'id'    => $university->id,
            ];
        }

        return [
            'flag'      => false,
            'errorCode' => $repositoryCode + 3,
        ];
    }

    /**
     * return university.
     */
    public function getUniversity($id, $activeFlag=true)
    {
        $university = [];

        try {
            $university = University::query();

            if($activeFlag) {
                $university = $university->active();
            }

            $university = $university->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 4;
            }

            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $university;
    }

    public function deleteUniversity($id, $forceFlag=false)
    {
        $deleteFlag = false;

        try {
            //get university record
            $university   = $this->getUniversity($id);

            if($forceFlag) {
                //removing university permanently
                $university->forceDelete();
            } else {
                $university->delete();
            }

            $deleteFlag = true;
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 5;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }
        
        if($deleteFlag) {
            return [
                'flag'  => true,
                'force' => $forceFlag,
            ];
        }

        return [
            'flag'          => false,
            'error_code'    => $this->repositoryCode + 6,
        ];
    }
}
