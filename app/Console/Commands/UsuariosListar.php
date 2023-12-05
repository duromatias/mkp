<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Users\Models\User;
use App\Modules\Users\Resources\UserResource;

class UsuariosListar extends Command
{

    protected $signature = 'usuarios:listar';
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ret = new UserResource(User::getById(9, [], [
            'with_relation' => [
                'rol',
                'onboardingUser.userPersonalData',
                'onboardingUser.business'
            ]
        ]));
        print_r(json_decode($ret->toJson(), true));die();
        //die();
        $rs = User::listar(1, 50, [
            // 'onboarding_business_name' => 'federada',
            //'onboarding_user_name' => 'Tamara',
        ], [], [
            'with_relation' => [
                'onboardingUser',
                'onboardingUser.business',
                'onboardingUser.userPersonalData',
            ],
        ]);
        $ret = UserResource::collection($rs);
        
        print_r(json_decode($ret->toJson(), true));die();
    }
}
