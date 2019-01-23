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
            $batches = Batch::with(['course'])->active();

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
            }dd($e);
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $batches;
    }

    /**
     * Action for batch save.
     */
    public function saveBatch($inputArray=[], $batch=null)
    {
        try {
            //batch saving
            if(empty($batch)) {
                $batch = new Batch;
            }
            $batch->batch_name       = $inputArray['batch_name'];
            $batch->course_id        = $inputArray['course_id'];
            $batch->from_year        = $inputArray['from_year'];
            $batch->to_year          = $inputArray['to_year'];
            $batch->fee_amount       = $inputArray['fee_amount'];
            $batch->fee_per_year     = $inputArray['fee_per_year'];
            $batch->fee_per_sem      = $inputArray['fee_per_sem'];
            $batch->fee_per_month    = $inputArray['fee_per_month'];
            $batch->class_start_date = $inputArray['class_start_date'];
            $batch->status           = 1;
            //batch save
            $batch->save();

            return [
                'flag'  => true,
                'id'    => $batch->id,
            ];
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 2;
            }
            throw new AppCustomException("CustomError", $this->errorCode);
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
