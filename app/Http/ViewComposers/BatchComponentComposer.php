<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\BatchRepository;
use Exception;
//use App\Exceptions\AppCustomException;

class BatchComponentComposer
{
    protected $batches = [], $errorHead = null;

    /**
     * Create a new batches partial composer.
     *
     * @param  BatchRepository  $batches
     * @return void
     */
    public function __construct(BatchRepository $batchRepo)
    {
        $errorCode          = 0;
        $this->errorHead    = config('settings.composer_code.BatchComponentComposer');

        try {
            $this->batches = $batchRepo->getBatches();
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 1;
            }

            //throw new AppCustomException("CustomError", ($this->errorHead + $errorCode));
        }
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with(['batchesCombo' => $this->batches]);
    }
}