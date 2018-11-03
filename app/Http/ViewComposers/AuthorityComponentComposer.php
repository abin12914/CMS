<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\AuthorityRepository;
use Exception;
//use App\Exceptions\AppCustomException;

class AuthorityComponentComposer
{
    protected $authorities = [], $errorHead = null;

    /**
     * Create a new authorities partial composer.
     *
     * @param  AuthorityRepository  $authorities
     * @return void
     */
    public function __construct(AuthorityRepository $authorityRepo)
    {
        $errorCode          = 0;
        $this->errorHead    = config('settings.composer_code.AuthorityComponentComposer');

        try {
            $this->authorities = $authorityRepo->getAuthorities();
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
        $view->with(['authoritiesCombo' => $this->authorities]);
    }
}