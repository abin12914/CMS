<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CertificationRepository;
use App\Http\Requests\CertificationRegistrationRequest;
use App\Http\Requests\CertificationFilterRequest;
use \Carbon\Carbon;
use DB;
use Exception;
use App\Exceptions\AppCustomException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Auth;

class CertificationController extends Controller
{
    protected $certificationRepo;
    public $errorHead = null, $noOfRecordsPerPage = null;

    public function __construct(CertificationRepository $certificationRepo)
    {
        $this->certificationRepo      = $certificationRepo;
        $this->noOfRecordsPerPage   = config('settings.no_of_record_per_page');
        $this->errorHead            = config('settings.controller_code.CertificationController');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CertificationFilterRequest $request)
    {
        $noOfRecords    = !empty($request->get('no_of_records')) ? $request->get('no_of_records') : $this->noOfRecordsPerPage;

        $params = [
                'wage_type' => $request->get('wage_type'),
                'id'        => $request->get('certificate_id'),
            ];
        
        return view('certification.list', [
                'certifications'         => $this->certificationRepo->getCertifications($params, $noOfRecords),
                'params'            => $params,
                'noOfRecords'       => $noOfRecords,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('certification.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        CertificationRegistrationRequest $request,
        $id=null
    ) {
        $saveFlag            = false;
        $errorCode           = 0;
        $certification            = null;

        //wrappin db transactions
        DB::beginTransaction();
        try {
            if(!empty($id)) {
                $certification = $this->certificationRepo->getCertification($id);
            }

            $certificationResponse = $this->certificationRepo->saveCertification([
                'issue_date'     => Carbon::createFromFormat('d-m-Y', $request->get('certificate_date'))->format('Y-m-d'),
                'user_id'        => Auth::id(),
                'address_id'     => $request->get('address_id'),
                'certificate_id' => $request->get('certificate_id'),
                'student_id'     => $request->get('student_id'),
            ], $certification);

            if(!$certificationResponse['flag']) {
                throw new AppCustomException("CustomError", $certificationResponse['errorCode']);
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
                    'id'    => $certificationResponse['id']
                ];
            }

            return redirect(route('certification.show', $certificationResponse['id']))->with("message","Certification details saved successfully. Reference Number : ". $certificationResponse['id'])->with("alert-class", "success");
        }

        if(!empty($id)) {
            return [
                'flag'          => false,
                'errorCode'    => $errorCode
            ];
        }
        
        return redirect()->back()->with("message","Failed to save the certification details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $errorCode      = 0;
        $certification  = [];
        $placeHolders   = config('constants.certificatePlaceholders');

        try {
            $certification = $this->certificationRepo->getCertification($id);

            $certificationContent = $certification->certificate->certificate_content;

            if($certification->certificate->certificate_type == 1) {
                foreach($certification->students as $index => $student) {

                    if($student->gender == 1) {
                        $studentCertification[$student->id] = str_replace('She', 'He', $certificationContent);
                        $studentCertification[$student->id] = str_replace('Her', 'His', $studentCertification[$student->id]);
                        $studentCertification[$student->id] = str_replace('she', 'he', $studentCertification[$student->id]);
                        $studentCertification[$student->id] = str_replace('her', 'his', $studentCertification[$student->id]);
                    } else if($student->gender == 2) {
                        $studentCertification[$student->id] = preg_replace('/\bHe\b/', 'She', $certificationContent);
                        $studentCertification[$student->id] = str_replace('His', 'Her', $studentCertification[$student->id]);
                        $studentCertification[$student->id] = preg_replace('/\bhe\b/', 'she', $studentCertification[$student->id]);
                        $studentCertification[$student->id] = str_replace('his', 'her', $studentCertification[$student->id]);
                    } else {
                        $studentCertification[$student->id] = $certificationContent;
                    }

                    foreach($placeHolders as $holder => $value){
                        switch ($value) {
                            case 'batch->course->course_name':
                                $input = $student->batch->course->course_name;
                                break;
                            case 'batch->course->descriptive_name':
                                $input = $student->batch->course->descriptive_name;
                                break;
                            case 'batch->course->university->university_name':
                                $input = $student->batch->course->university->university_name;
                                break;
                            case 'batch->course->center_code':
                                $input = $student->batch->course->university->center_code;
                                break;
                            case 'batch->from_year':
                                $input = $student->batch->from_year;
                                break;
                            case 'batch->to_year':
                                $input = $student->batch->to_year;
                                break;
                            case 'batch->fee_amount':
                                $input = $student->batch->fee_amount;
                                break;
                            case 'batch->fee_per_year':
                                $input = $student->batch->fee_per_year;
                                break;
                            case 'batch->fee_per_sem':
                                $input = $student->batch->fee_per_sem;
                                break;
                            case 'batch->fee_per_month':
                                $input = $student->batch->fee_per_month;
                                break;
                            case 'name':
                                $input = $student->name;
                                break;
                            case 'address':
                                $input = $student->address;
                                break;
                            case 'registration_number':
                                $input = $student->registration_number;
                                break;
                            case 'batch->course->university->university_grade':
                                $input = $student->batch->course->university->university_grade;
                                break;
                            default:
                                $input = '';
                                break;
                        }
                        $studentCertification[$student->id] = str_replace($holder, $input, $studentCertification[$student->id]);
                    }
                }
            } else {
                $studentCertification[0] = $certificationContent;
                foreach($placeHolders as $holder => $value){
                    switch ($value) {
                        case 'batch->course->course_name':
                            $input = $certification->students[0]->batch->course->course_name;
                            break;
                        case 'batch->course->descriptive_name':
                            $input = $certification->students[0]->batch->course->descriptive_name;
                            break;
                        case 'batch->course->university->university_name':
                            $input = $certification->students[0]->batch->course->university->university_name;
                            break;
                        case 'batch->course->center_code':
                            $input = $certification->students[0]->batch->course->university->center_code;
                            break;
                        case 'batch->from_year':
                            $input = $certification->students[0]->batch->from_year;
                            break;
                        case 'batch->to_year':
                            $input = $certification->students[0]->batch->to_year;
                            break;
                        case 'batch->fee_amount':
                            $input = $certification->students[0]->batch->fee_amount;
                            break;
                        case 'batch->fee_per_year':
                            $input = $certification->students[0]->batch->fee_per_year;
                            break;
                        case 'batch->fee_per_sem':
                            $input = $certification->students[0]->batch->fee_per_sem;
                            break;
                        case 'batch->fee_per_month':
                            $input = $certification->students[0]->batch->fee_per_month;
                            break;
                        case 'name':
                            $input = '';
                            break;
                        case 'address':
                            $input = '';
                            break;
                        case 'registration_number':
                            $input = '';
                            break;
                        case 'batch->course->university->university_grade':
                            $input = $certification->students[0]->batch->course->university->university_grade;
                            break;
                        default:
                            $input = '';
                            break;
                    }
                    $studentCertification[0] = str_replace($holder, $input, $studentCertification[0]);
                }
            }
        } catch (Exception $e) {
        if($e->getMessage() == "CustomError") {
            $errorCode = $e->getCode();
        } else {
            $errorCode = 2;
        }dd($e);
        //throwing methodnotfound exception when no model is fetched
        throw new ModelNotFoundException("Certification", $errorCode);
    }
        return view('certification.details', compact('certification','studentCertification'));
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
        $certification   = [];

        try {
            $certification = $this->certificationRepo->getCertification($id);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 3;
            }
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Certification", $errorCode);
        }

        return view('certification.edit', [
            'certification'  => $certification,
            'wageTypes' => config('constants.certificationWageTypes'),
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
        CertificationRegistrationRequest $request,
        AccountRepository $accountRepo,
        TransactionRepository $transactionRepo,
        $id
    ) {
        $updateResponse = $this->store($request, $accountRepo, $transactionRepo, $id);

        if($updateResponse['flag']) {
            return redirect(route('certification.show', $updateResponse['id']))->with("message","Certification details updated successfully. Updated Record Number : ". $updateResponse['id'])->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to update the certification details. Error Code : ". $this->errorHead. "/". $updateResponse['errorCode'])->with("alert-class", "error");
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
