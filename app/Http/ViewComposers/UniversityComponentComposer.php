<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\UniversityRepository;
use Exception;
//use App\Exceptions\AppCustomException;

class UniversityComponentComposer
{
    protected $universities = [], $errorHead = null;

    /**
     * Create a new universities partial composer.
     *
     * @param  UniversityRepository  $universities
     * @return void
     */
    public function __construct(UniversityRepository $universityRepo)
    {
        $errorCode          = 0;

        try {
            $this->universities = $universityRepo->getUniversities();
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
        $view->with(['universitiesCombo' => $this->universities]);
    }
}