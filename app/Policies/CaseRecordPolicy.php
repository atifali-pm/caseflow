<?php

namespace App\Policies;

use App\Models\CaseRecord;
use App\Models\User;

class CaseRecordPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isProvider();
    }

    public function view(User $user, CaseRecord $caseRecord): bool
    {
        if ($user->isAdmin()) return true;
        return $caseRecord->provider_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isProvider();
    }

    public function update(User $user, CaseRecord $caseRecord): bool
    {
        if ($user->isAdmin()) return true;
        return $caseRecord->provider_id === $user->id;
    }

    public function delete(User $user, CaseRecord $caseRecord): bool
    {
        if ($user->isAdmin()) return true;
        return $caseRecord->provider_id === $user->id;
    }
}
