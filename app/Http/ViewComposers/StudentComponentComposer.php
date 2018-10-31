<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\StudentRepository;
use Exception;
//use App\Exceptions\AppCustomException;

class StudentComponentComposer
{
    protected $students = [], $cashStudent, $errorHead = null;

    /**
     * Create a new student partial composer.
     *
     * @param  StudentRepository  $student
     * @return void
     */
    public function __construct(StudentRepository $studentRepo)
    {
        $errorCode          = 0;
        $this->errorHead    = config('settings.composer_code.StudentComponentComposer');
        $cashStudentId      = config('constants.studentConstants.Cash.id');
        
        try {
            $this->students     = $studentRepo->getStudents([], null, true, false);
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
        $view->with(['studentsCombo' => $this->students]);
    }
}