<?php

namespace App\Repositories;

use App\Models\Authority;
use Exception;
use App\Exceptions\AppCustomException;

class AuthorityRepository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.AuthorityRepository');
    }

    /**
     * Return accounts.
     */
    public function getAuthorities($params=[], $noOfRecords=null)
    {
        $authorities = [];

        try {
            $authorities = Authority::active();

            foreach ($params as $key => $value) {
                if(!empty($value)) {
                    $authorities = $authorities->where($key, $value);
                }
            }
            if(!empty($noOfRecords)) {
                $authorities = $authorities->paginate($noOfRecords);
            } else {
                $authorities= $authorities->get();
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

        return $authorities;
    }

    /**
     * Action for saving accounts.
     */
    public function saveAuthority($inputArray, $authority=null)
    {
        $saveFlag = false;

        try {
            if(empty($authority)) {
                $authority = new Authority;
            }

            //authority saving
            $authority->name          = $inputArray['name'];
            $authority->designation   = $inputArray['designation'];
            $authority->status        = 1;
            //authority save
            $authority->save();

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
                'id'    => $authority->id,
            ];
        }

        return [
            'flag'      => false,
            'errorCode' => $repositoryCode + 3,
        ];
    }

    /**
     * return authority.
     */
    public function getAuthority($id, $activeFlag=true)
    {
        $authority = [];

        try {
            $authority = Authority::query();

            if($activeFlag) {
                $authority = $authority->active();
            }

            $authority = $authority->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 4;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $authority;
    }

    public function deleteAuthority($id, $forceFlag=false)
    {
        $deleteFlag = false;

        try {
            //get authority record
            $authority   = $this->getAuthority($id);

            if($forceFlag) {
                //removing authority permanently
                $authority->forceDelete();
            } else {
                $authority->delete();
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
