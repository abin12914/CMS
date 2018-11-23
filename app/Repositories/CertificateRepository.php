<?php

namespace App\Repositories;

use App\Models\Certificate;
use Exception;
use App\Exceptions\AppCustomException;

class CertificateRepository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.CertificateRepository');
    }

    /**
     * Return accounts.
     */
    public function getCertificates($params=[], $noOfRecords=null)
    {
        $certificates = [];

        try {
            $certificates = Certificate::active();

            foreach ($params as $key => $value) {
                if(!empty($value)) {
                    $certificates = $certificates->where($key, $value);
                }
            }
            if(!empty($noOfRecords)) {
                $certificates = $certificates->paginate($noOfRecords);
            } else {
                $certificates= $certificates->get();
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

        return $certificates;
    }

    /**
     * Action for saving accounts.
     */
    public function saveCertificate($inputArray, $certificate=null)
    {
        $saveFlag = false;

        try {
            if(empty($certificate)) {
                $certificate = new Certificate;
            }

            //certificate saving
            $certificate->name                  = $inputArray['name'];
            $certificate->description           = $inputArray['description'];
            $certificate->authority_id          = $inputArray['authority_id'];
            $certificate->certificate_type      = $inputArray['certificate_type'];
            $certificate->certificate_content   = $inputArray['certificate_content'];
            $certificate->status            = 1;
            //certificate save
            $certificate->save();

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
                'id'    => $certificate->id,
            ];
        }

        return [
            'flag'      => false,
            'errorCode' => $repositoryCode + 3,
        ];
    }

    /**
     * return certificate.
     */
    public function getCertificate($id, $activeFlag=true)
    {
        $certificate = [];

        try {
            $certificate = Certificate::query();

            if($activeFlag) {
                $certificate = $certificate->active();
            }

            $certificate = $certificate->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 4;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $certificate;
    }

    public function deleteCertificate($id, $forceFlag=false)
    {
        $deleteFlag = false;

        try {
            //get certificate record
            $certificate   = $this->getCertificate($id);

            if($forceFlag) {
                //removing certificate permanently
                $certificate->forceDelete();
            } else {
                $certificate->delete();
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
