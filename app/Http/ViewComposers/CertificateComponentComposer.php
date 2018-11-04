<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\CertificateRepository;
use Exception;
//use App\Exceptions\AppCustomException;

class CertificateComponentComposer
{
    protected $certificates = [], $errorHead = null;

    /**
     * Create a new certificate partial composer.
     *
     * @param  CertificateRepository  $certificate
     * @return void
     */
    public function __construct(CertificateRepository $certificateRepo)
    {
        $errorCode          = 0;
        $this->errorHead    = config('settings.composer_code.CertificateComponentComposer');
        
        try {
            $this->certificates     = $certificateRepo->getCertificates([], null, true, false);
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
        $view->with(['certificatesCombo' => $this->certificates]);
    }
}