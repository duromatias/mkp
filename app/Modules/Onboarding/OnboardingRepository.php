<?php

namespace App\Modules\Onboarding;

use App\Modules\Shared\Repositories\ModelRepository;

class OnboardingRepository extends ModelRepository
{

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
        $this->table = "{$this->getDatabaseName()}.{$this->table}";
    }
    
    public function getDatabaseName(): string {
        return config('onboarding.db_name');
    }
}