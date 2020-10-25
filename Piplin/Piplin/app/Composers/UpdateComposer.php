<?php

/*
 * This file is part of Piplin.
 *
 * Copyright (C) 2016-2017 piplin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Piplin\Composers;

use Illuminate\Contracts\View\View;
use Piplin\Services\Update\LatestReleaseInterface;

/**
 * Update composer for the update bar.
 */
class UpdateComposer
{
    private $release;

    /**
     * Class constructor.
     *
     * @param LatestReleaseInterface $release
     */
    public function __construct(LatestReleaseInterface $release)
    {
        $this->release = $release;
    }

    /**
     * Determines if the update prompt should show.
     *
     * @param  \Illuminate\Contracts\View\View $view
     * @return void
     */
    public function compose(View $view)
    {
        $latest_tag = str_replace(['V', 'v'], '', $this->release->latest());

        $is_outdated = version_compare($latest_tag, APP_VERSION, '>');

        $view->with('is_outdated', $is_outdated);
        $view->with('current_version', APP_VERSION);
        $view->with('latest_version', $latest_tag);
    }
}
