<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CourseRepository;
use App\Http\Requests\CourseRegistrationRequest;
use DB;
use Exception;
use App\Exceptions\AppCustomException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CourseController extends Controller
{
    protected $courseRepo;
    public $errorHead = null, $noOfRecordsPerPage = null;

    public function __construct(CourseRepository $courseRepo)
    {
        $this->courseRepo           = $courseRepo;
        $this->noOfRecordsPerPage   = config('settings.no_of_record_per_page');
        $this->errorHead            = config('settings.controller_code.CourseController');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('courses.list', [
                'courses'      => $this->courseRepo->getCourses([], null),
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courseDurationTypes  = config('constants.courseDurationTypes');

        return view('courses.register', compact('courseDurationTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CourseRegistrationRequest $request, $id=null)
    {
        $saveFlag   = false;
        $errorCode  = 0;
        $course     = null;

        //wrappin db transactions
        DB::beginTransaction();
        try {
            if(!empty($id)) {
                $course = $this->courseRepo->getCourse($id);
            }

            $response   = $this->courseRepo->saveCourse([
                'name'              => $request->get('name'),
                'descriptive_name'  => $request->get('descriptive_name'),
                'university_id'     => $request->get('university_id'),
                'duration_type'     => $request->get('duration_type'),
                'duration'          => $request->get('duration'),
            ], $course);

            if(!$response['flag']) {
                throw new AppCustomException("CustomError", $response['errorCode']);
            }

            DB::commit();
            $saveFlag = true;
        } catch (Exception $e) {
            //roll back in case of exceptions
            DB::rollback();

            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 1;
            }
        }

        if($saveFlag) {
            if(!empty($id)) {
                return [
                    'flag'  => true,
                    'id'    => $response['id']
                ];
            }

            return redirect(route('course.index', $response['id']))->with("message","Course details saved successfully. Reference Number : ". $response['id'])->with("alert-class", "success");
        }

        if(!empty($id)) {
            return [
                'flag'          => false,
                'errorCode'    => $errorCode
            ];
        }
        
        return redirect()->back()->with("message","Failed to save the course details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('courses.details', [
                'course'       => $this->courseRepo->getCourse($id),
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
        $courseDurationTypes  = config('constants.courseDurationTypes');

        return view('courses.edit', [
                'course'                => $this->courseRepo->getCourse($id),
                'courseDurationTypes'   => $courseDurationTypes,
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(
        CourseRegistrationRequest $request,
        $id
    ) {
        $updateResponse = $this->store($request, $id);

        if($updateResponse['flag']) {
            return redirect(route('course.index', $updateResponse['id']))->with("message","Course details updated successfully. Updated Record Number : ". $updateResponse['id'])->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to update the course details. Error Code : ". $this->errorHead. "/". $updateResponse['errorCode'])->with("alert-class", "error");
        

        /*if($saveFlag) {
            return redirect(route('course.index'))->with("message","Course details updated successfully. Updated Record Number : ". $response['id'])->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to update the course details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return redirect()->back()->with("message", "Course deletion restricted.")->with("alert-class", "error");
    }
}
