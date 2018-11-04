<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\AddressRepository;
use Exception;
//use App\Exceptions\AppCustomException;

class AddressComponentComposer
{
    protected $addresses = [], $errorHead = null;

    /**
     * Create a new addresses partial composer.
     *
     * @param  AddressRepository  $addresses
     * @return void
     */
    public function __construct(AddressRepository $addressRepo)
    {
        $errorCode          = 0;

        try {
            $this->addresses = $addressRepo->getAddresses();
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
        $view->with(['addressesCombo' => $this->addresses]);
    }
}