<?php

namespace App\Repositories;

use App\Models\Address;
use Exception;
use App\Exceptions\AppCustomException;

class AddressRepository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.AddressRepository');
    }

    /**
     * Return accounts.
     */
    public function getAddresses($params=[], $noOfRecords=null)
    {
        $addresses = [];

        try {
            $addresses = Address::active();

            foreach ($params as $key => $value) {
                if(!empty($value)) {
                    $addresses = $addresses->where($key, $value);
                }
            }
            if(!empty($noOfRecords)) {
                $addresses = $addresses->paginate($noOfRecords);
            } else {
                $addresses= $addresses->get();
            }
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 1;
            }

            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $addresses;
    }

    /**
     * Action for saving accounts.
     */
    public function saveAddress($inputArray, $address=null)
    {
        $saveFlag = false;

        try {
            if(empty($address)) {
                $address = new Address;
            }

            //address saving
            $address->name          = $inputArray['name'];
            $address->designation   = $inputArray['designation'];
            $address->address       = $inputArray['address'];
            $address->title         = $inputArray['title'];
            $address->status        = 1;
            //address save
            $address->save();

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
                'id'    => $address->id,
            ];
        }

        return [
            'flag'      => false,
            'errorCode' => $repositoryCode + 3,
        ];
    }

    /**
     * return address.
     */
    public function getAddress($id, $activeFlag=true)
    {
        $address = [];

        try {
            $address = Address::query();

            if($activeFlag) {
                $address = $address->active();
            }

            $address = $address->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 4;
            }

            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $address;
    }

    public function deleteAddress($id, $forceFlag=false)
    {
        $deleteFlag = false;

        try {
            //get address record
            $address   = $this->getAddress($id);

            if($forceFlag) {
                //removing address permanently
                $address->forceDelete();
            } else {
                $address->delete();
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
