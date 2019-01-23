<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UniversityRepository;
use App\Http\Requests\UniversityRegistrationRequest;
use DB;
use Exception;
use App\Exceptions\AppCustomException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UniversityController extends Controller
{
    protected $universityRepo;
    public $errorHead = null, $noOfRecordsPerPage = null;

    public function __construct(UniversityRepository $universityRepo)
    {
        $this->universityRepo       = $universityRepo;
        $this->noOfRecordsPerPage   = config('settings.no_of_record_per_page');
        $this->errorHead            = config('settings.controller_code.UniversityController');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('universities.list', [
                'universities'   => $this->universityRepo->getUniversities([], null),
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('universities.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
    UniversityRegistrationRequest $request,
    $id=null
    ) {
        $errorCode  = 0;
        $university  = null;

        //wrappin db transactions
        DB::beginTransaction();
        try {
            if(!empty($id)) {
                $university = $this->universityRepo->getUniversity($id);
            }

            $universityResponse = $this->universityRepo->saveUniversity([
                'university_name'  => $request->get('university_name'),
                'center_code'      => $request->get('center_code'),
                'university_grade' => $request->get('university_grade'),
            ], $university);

            if(!$universityResponse['flag']) {
                throw new AppCustomException("CustomError", $universityResponse['errorCode']);
            }

            DB::commit();
            
            if(!empty($id)) {
                return [
                    'flag'  => true,
                    'id'    => $universityResponse['id']
                ];
            }

            return redirect(route('university.index'))->with("message","University details saved successfully. Reference Number : ". $universityResponse['id'])->with("alert-class", "success");
        } catch (Exception $e) {
            //roll back in case of exceptions
            DB::rollback();

            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 1;
            }
        }

        if(!empty($id)) {
            return [
                'flag'          => false,
                'errorCode'    => $errorCode
            ];
        }
        
        return redirect()->back()->with("message","Failed to save the university details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $errorCode  = 0;
        $university   = [];

        try {
            $university = $this->universityRepo->getUniversity($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 2;
            }
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("University", $errorCode);
        }
        return view('universities.details', [
                'university'  => $university,
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $errorCode  = 0;
        $university   = [];

        try {
            $university = $this->universityRepo->getUniversity($id);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 3;
            }
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("University", $errorCode);
        }

        return view('universities.edit', [
            'university'  => $university,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UniversityRegistrationRequest $request, $id)
    {
        $updateResponse = $this->store($request, $id);

        if($updateResponse['flag']) {
            return redirect(route('university.index'))->with("message","University details updated successfully. Updated Record Number : ". $updateResponse['id'])->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to update the university details. Error Code : ". $this->errorHead. "/". $updateResponse['errorCode'])->with("alert-class", "error");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return redirect()->back()->with("message", "Deletion restricted.")->with("alert-class", "error");
    }
}
