<?php

namespace Kontenta\KontourSupport;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Kontenta\Kontour\Contracts\TeamRecentVisitsWidget as TeamRecentVisitsWidgetContract;
use Kontenta\Kontour\Contracts\AdminLink;

class TeamRecentVisitsWidget implements TeamRecentVisitsWidgetContract
{
    protected $repository;

    public function __construct(RecentVisitsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function toHtml()
    {
        return '<ul>'.$this->getVisits()->map(function ($visit) {
                return '<li>'.$visit->getLink()->toHtml().'</li>';
        })->implode("\n").'</ul>';
    }

    public function isAuthorized(Authorizable $user = null): bool
    {
        return true;
    }

    private function getVisits()
    {
        return $this->repository->getEditVisits()->filter(function($visit) {
            return !$visit->getUser()->is(Auth::guard(config('kontour.guard'))->user());
        })->unique(function ($visit) {
            return $visit->getLink()->getUrl();
        })->sortByDesc(function ($visit) {
            return $visit->getDateTime();
        });
    }
}
