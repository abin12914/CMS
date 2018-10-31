<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\CourseRepository;
use Exception;
//use App\Exceptions\AppCustomException;

class CourseComponentComposer
{
    protected $courses = [], $errorHead = null;

    /**
     * Create a new courses partial composer.
     *
     * @param  CourseRepository  $courses
     * @return void
     */
    public function __construct(CourseRepository $courseRepo)
    {
        $errorCode          = 0;
        $this->errorHead    = config('settings.composer_code.CourseComponentComposer');

        try {
            $this->courses = $courseRepo->getCourses();
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
        $view->with(['coursesCombo' => $this->courses]);
    }
}