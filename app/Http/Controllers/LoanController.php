<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Applicant;

class LoanController extends Controller
{
    public function getEligibilityStatus(Request $request) {
        //dd($request);
        $validator=Validator::make($request->all(),
        [
        'email' => 'required|email',
        'salary' => 'required',
        'amount' => 'required',
        'tenure' => 'required',
        ]);

        if($validator ->fails()){
            return('bad request');
        }

        $user_id = 5;
        $user = new Applicant;

        $salary=$request->input('salary');
        //dd($salary);
        $Amount=$request->input('amount');
        $tenure=$request->input('tenure');
        $email=$request->input('email');


        $last_loanCollectedDate=DB::table('applications')
        ->where('user_id','=',$user_id)
        ->where('status', '=','PAID')
        ->latest('application_id')
        ->value('end_date');
        $user->$request->all();
        //dd($data);
        $user->save();
        dd($user);
// TO CHECK IF THE USER CAN APPLY FOR A NEW LOAN, ONLY IF THE INITIAL WAS CONCLUDED.
        $max_amount= $salary*$tenure*0.3;
        //dd($max_amount);
        if( Carbon::parse($last_loanCollectedDate)->addDays(90)->isPast()) {
           //max_amount per month payable
            return($Amount < $max_amount) ? $this->SuccessfulPage() : $this->Unsuccessful();
        }
//save the request into the database
        return('you have successfully applied');git 
    }

    public function SuccessfulPage(){
        return view('successful');
    }

    public function Unsuccessful(){
        return view('unsucessful');
    }

}
