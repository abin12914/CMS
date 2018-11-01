<?php

namespace App\Repositories;

use App\Models\Batch;
use Exception;
use App\Exceptions\AppCustomException;

class BatchRepository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.BatchRepository');
    }

    /**
     * Return batches.
     */
    public function getBatches($params=[], $relationalParams=[], $noOfRecords=null)
    {
        $batches = [];

        try {
            $batches = Batch::with(['branch', 'transaction.debitAccount'])->active();

            foreach ($params as $param) {
                if(!empty($param) && !empty($param['paramValue'])) {
                    $batches = $batches->where($param['paramName'], $param['paramOperator'], $param['paramValue']);
                }
            }

            foreach ($relationalParams as $param) {
                if(!empty($param) && !empty($param['paramValue'])) {
                    $batches = $batches->whereHas($param['relation'], function($qry) use($param) {
                        $qry->where($param['paramName'], $param['paramValue']);
                    });
                }
            }

            if(!empty($noOfRecords) && $noOfRecords > 0) {
                $batches = $batches->paginate($noOfRecords);
            } else {
                $batches= $batches->get();
            }
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 1;
            }
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $batches;
    }

    /**
     * Action for batch save.
     */
    public function saveBatch($inputArray=[], $batch=null)
    {
        $saveFlag   = false;

        try {
            //batch saving
            if(empty($batch)) {
                $batch = new Batch;
            }
            $batch->transaction_id = $inputArray['transaction_id'];
            $batch->date           = $inputArray['date'];
            $batch->service_id     = $inputArray['service_id'];
            $batch->bill_amount    = $inputArray['bill_amount'];
            $batch->branch_id      = $inputArray['branch_id'];
            $batch->status         = 1;
            //batch save
            $batch->save();

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
                'id'    => $batch->id,
            ];
        }
        return [
            'flag'      => false,
            'errorCode' => $this->repositoryCode + 3,
        ];
    }

    /**
     * return batch.
     */
    public function getBatch($id)
    {
        $batch = [];

        try {
            $batch = Batch::active()->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 4;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $batch;
    }

    public function deleteBatch($id, $forceFlag=false)
    {
        $deleteFlag = false;

        try {
            //get batch
            $batch = $this->getBatch($id);

            //force delete or soft delete
            //related models will be deleted by deleting event handlers
            if($forceFlag) {
                $batch->forceDelete();
            } else {
                $batch->delete();
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
            'errorCode'    => $this->repositoryCode + 6,
        ];
    }
}
