<?php

namespace Sisnanceiro\Services;

use App\Mail\NewUser as MailNewUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Models\Person;
use Sisnanceiro\Models\User;
use Sisnanceiro\Repositories\PersonRepository;
use Sisnanceiro\Repositories\UserGroupingRepository;
use Sisnanceiro\Repositories\UserRepository;

class UserService extends Service
{

    protected $rules = [
        'create' => [
            'id' => 'required|int',
        ],
        'update' => [

        ],
        'change_password' => [
            'current_password'          => 'required',
            'new_password'              => 'required|min:6',
            'new_password_confirmation' => 'required|same:new_password'
        ]
    ];

    protected $personRepository;

    public function __construct(
        Validator $validator,
        UserRepository $repository,
        PersonRepository $personRepository,
        UserGroupingRepository $userGroupingRepository
    ) {
        $this->validator              = $validator;
        $this->repository             = $repository;
        $this->personRepository       = $personRepository;
        $this->userGroupingRepository = $userGroupingRepository;
    }

    /**
     * Service create company, person and user for company
     * @param array data
     * @return array
     */
    public function create(Person $person)
    {
        $passwordGenerated = Str::random();

        $data = [
            'id'             => $person->id,
            'company_id'     => $person->company_id,
            'email'          => $person->email,
            'password'       => Hash::make($passwordGenerated),
            'remember_token' => Hash::make(Str::random()),
        ];
        $user = $this->repository->create($data);

        // add user to master group
        $this->userGroupingRepository->create([
            'user_id'       => $person->id,
            'user_group_id' => User::GROUP_MASTER,
        ]);

        // send mail
        Mail::to($user)->send(new MailNewUser($user, $passwordGenerated));

        return $user;
    }

    /**
     * change password of the user
     * @param int $userId
     * @oaram array $input
     * @return Sisnanceiro\Models\User
     */
    public function changePassword(int $userId, array $input) {
        $model = $this->repository->find($userId);

        $this->validator->validate($input, $this->rules['change_password']);
        if(!Hash::check($input['current_password'], $model->password)) {
            $this->validator->addError('password', 'current_password', 'A senha atual esta incorreta!');
        }
        if ($this->validator->getErrors()) {
            return $this->validator;
        }

        $model->update(['password' => Hash::make($input['new_password'])]);
        return $model;
    }

}
