<?php

namespace App\Http\Controllers\API;

use App\Contact;
use App\Http\Controllers\Controller;
use App\Http\Helpers\InputHelper;
use App\Http\Helpers\ResponseHelper;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PhoneBookController extends Controller
{
    /**
     * Register a new user
     * @param Request $request
     * @return JsonResponse
     */
    public function signUp(Request $request)
    {

        InputHelper::inputChecker(
            $request,
            [
                $request->Email,
                $request->Haslo
            ],
            function (Request $request) {
                User::create($request);

                return ResponseHelper::jsonResponse(null, Response::HTTP_OK, config('messages.success'))->send();
            });

    }

    /**
     * Login user and return a token
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $token = $this->guard($request->Email, $request->Haslo);


        if ($token) {
            return ResponseHelper::jsonResponse(null, Response::HTTP_OK, config('messages.success'))->header('Authorization', $token)->send();
        } else {
            return ResponseHelper::jsonResponse(null, Response::HTTP_BAD_REQUEST, config('messages.fail'))->send();
        }

    }

    // public function createContact(Request $request)
    // {

    //     InputHelper::inputChecker(
    //         $request,
    //         [
    //             $request->phone_number,
    //             $request->name
    //         ],
    //         function (Request $request) {
    //             Contact::create($request);

    //             return ResponseHelper::jsonResponse(null, Response::HTTP_OK, config('messages.success'))->send();
    //         });

    // }

    // public function updateContact(Request $request)
    // {

    //     InputHelper::inputChecker(
    //         $request,
    //         [
    //             $request->id,
    //             $request->phone_number,
    //             $request->name
    //         ],
    //         function (Request $request) {
    //             Contact::updateOne($request);

    //             return ResponseHelper::jsonResponse(null, Response::HTTP_OK, config('messages.success'))->send();
    //         });

    // }

    // public function deleteContact(Request $request)
    // {

    //     InputHelper::inputChecker(
    //         $request,
    //         [
    //             $request->id
    //         ],
    //         function (Request $request) {
    //             Contact::deleteOne($request);

    //             return ResponseHelper::jsonResponse(null, Response::HTTP_OK, config('messages.success'))->send();
    //         });

    // }

    // public function readContacts(Request $request)
    // {

    //     InputHelper::inputChecker(
    //         $request,null,
    //         function (Request $request) {
    //             $contacts = Contact::readAll($request);

    //             return ResponseHelper::jsonResponse(null, Response::HTTP_OK, $contacts)->send();
    //         });

    // }


    /**
     * Return auth guard
     * @param $Email
     * @param $Haslo
     * @return Auth
     */
    private function guard($Email, $Haslo)
    {
        return Auth::guard('user')->attempt(array('Email' => $Email, 'Haslo' => $Haslo));
    }

}