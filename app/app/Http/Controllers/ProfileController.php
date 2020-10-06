<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Sisnanceiro\Models\PersonAddress;
use Sisnanceiro\Models\PersonContact;
use Sisnanceiro\Services\PersonService;
use Sisnanceiro\Services\ProfileService;
use Sisnanceiro\Services\UserService;

class ProfileController extends Controller
{

 private $profileService;
 private $personService;
 private $userService;

 public function __construct(ProfileService $profileService, PersonService $personService, UserService $userService)
 {
  $this->profileService = $profileService;
  $this->personService  = $personService;
  $this->userService    = $userService;
 }

 public function index(Request $request)
 {
  $user  = \Auth::user();
  $id    = $user->id;
  $model = $this->profileService->find($id);
  if ($request->isMethod('post')) {
   if (!empty($request->new_password)) {
    $userData = [
     'current_password'          => $request->current_password,
     'new_password'              => $request->new_password,
     'new_password_confirmation' => $request->new_password_confirmation,
    ];
    $userModel = $this->userService->changePassword($id, $userData);
    if (method_exists($userModel, 'getErrors') && $userModel->getErrors()) {
     $request->session()->flash('error', ['message' => 'Erro na tentativa de alterar seu perfil.', 'errors' => $userModel->getErrors()]);
     return redirect('profile/');
    }
   }
   $personPost  = $request->all();
   $personModel = $this->profileService->store($personPost, 'update');
   if (method_exists($personModel, 'getErrors') && $personModel->getErrors()) {
    $request->session()->flash('error', ['message' => 'Erro na tentativa de alterar seu perfil.', 'errors' => $personModel->getErrors()]);
   } else {
    $request->session()->flash('success', ['message' => 'Perfil alterado com sucesso.']);
   }
   return redirect('profile/');
  } else {
   if (!empty($model->birthdate)) {
    $carbonBirthdate  = Carbon::createFromFormat('Y-m-d', $model->birthdate);
    $model->birthdate = $carbonBirthdate->format('d/m/Y');
   }
   $typeContacts  = $this->personService->getTypeContacts();
   $typeAddresses = $this->personService->getTypeAddresses();
   $addresses     = isset($model->addresses) ? $model->addresses()->get() : new PersonAddress();
   $contacts      = isset($model->contacts) ? $model->contacts()->get() : new PersonContact();
   return view('profile/index', compact('model', 'addresses', 'contacts', 'typeContacts', 'typeAddresses', 'user'));
  }
 }
}
