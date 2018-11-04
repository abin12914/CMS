<?php

namespace App\Repositories;

use App\Models\Certification;
use Exception;
use App\Exceptions\AppCustomException;

class CertificationRepository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.CertificationRepository');
    }

    /**
     * Return accounts.
     */
    public function getCertifications($params=[], $noOfRecords=null)
    {
        $certifications = [];

        try {
            $certifications = Certification::active();

            foreach ($params as $key => $value) {
                if(!empty($value)) {
                    $certifications = $certifications->where($key, $value);
                }
            }
            if(!empty($noOfRecords)) {
                $certifications = $certifications->paginate($noOfRecords);
            } else {
                $certifications= $certifications->get();
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

        return $certifications;
    }

    /**
     * Action for saving accounts.
     */
    public function saveCertification($inputArray, $certification=null)
    {
        $saveFlag = false;

        try {
            if(empty($certification)) {
                $certification = new Certification;
            }

            //certification saving
            $certification->name                  = $inputArray['name'];
            $certification->description           = $inputArray['description'];
            $certification->authority_id          = $inputArray['authority_id'];
            $certification->certification_type      = $inputArray['certification_type'];
            $certification->certification_content   = $inputArray['certification_content'];
            $certification->status            = 1;
            //certification save
            $certification->save();

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
                'id'    => $certification->id,
            ];
        }

        return [
            'flag'      => false,
            'errorCode' => $repositoryCode + 3,
        ];
    }

    /**
     * return certification.
     */
    public function getCertification($id, $activeFlag=true)
    {
        $certification = [];

        try {
            $certification = Certification::with('account');

            if($activeFlag) {
                $certification = $certification->active();
            }

            $certification = $certification->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 4;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $certification;
    }

    public function deleteCertification($id, $forceFlag=false)
    {
        $deleteFlag = false;

        try {
            //get certification record
            $certification   = $this->getCertification($id);

            if($forceFlag) {
                //removing certification permanently
                $certification->forceDelete();
            } else {
                $certification->delete();
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
