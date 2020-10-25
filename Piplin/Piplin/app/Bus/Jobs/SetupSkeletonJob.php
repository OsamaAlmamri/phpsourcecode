<?php

/*
 * This file is part of Piplin.
 *
 * Copyright (C) 2016-2017 piplin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Piplin\Bus\Jobs;

use Piplin\Models\Command;
use Piplin\Models\ConfigFile;
use Piplin\Models\Project;
use Piplin\Models\SharedFile;
use Piplin\Models\Variable;

/**
 * A class to handle cloning project.
 */
class SetupSkeletonJob extends Job
{
    /**
     * @var mixed
     */
    private $target;

    /**
     * @var mixed
     */
    private $skeleton;

    /**
     * Create a new command instance.
     *
     * @param mixed $target
     * @param mixed $skeleton
     *
     * @return SetupSkeletonJob
     */
    public function __construct($target, $skeleton = null)
    {
        $this->target   = $target;
        $this->skeleton = $skeleton;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->skeleton || !$this->skeleton instanceof Project) {
            return;
        }

        if ($this->skeleton->buildPlan && $this->target->buildPlan) {
            $this->setupBuildPlan();
        }

        if ($this->skeleton->deployPlan && $this->target->deployPlan) {
            $this->setupDeployPlan();
        }
    }

    /**
     * Steup build plan.
     *
     * @return void
     */
    private function setupBuildPlan()
    {
        $mappings = [];

        foreach ($this->skeleton->buildPlan->commands as $command) {
            $data = $command->toArray();
            $new = $this->target->buildPlan->commands()->create($data);
            $mappings[$command->id] = $new->id;
        }

        foreach ($this->skeleton->buildPlan->servers as $server) {
            $data = $server->toArray();

            $this->target->buildPlan->servers()->create($data);
        }

        foreach ($this->skeleton->buildPlan->patterns as $pattern) {
            $data = $pattern->toArray();

            $new = $this->target->buildPlan->patterns()->create($data);

            foreach ($pattern->commands as $command) {
                $new_command_id = $mappings[$command->id];
                $new->commands()->attach([$new_command_id]);
            }
        }
    }

    /**
     * Steup deploy plan.
     *
     * @return void
     */
    private function setupDeployPlan()
    {
        foreach ($this->skeleton->deployPlan->commands as $command) {
            $data = $command->toArray();

            $this->target->deployPlan->commands()->create($data);
        }

        foreach ($this->skeleton->deployPlan->environments as $environment) {
            $data = $environment->toArray();

            $this->target->deployPlan->environments()->create($data);
        }

        foreach ($this->skeleton->deployPlan->variables as $variable) {
            $data = $variable->toArray();

            $this->target->deployPlan->variables()->create($data);
        }

        foreach ($this->skeleton->deployPlan->sharedFiles as $file) {
            $data = $file->toArray();

            $this->target->deployPlan->sharedFiles()->create($data);
        }

        foreach ($this->skeleton->deployPlan->configFiles as $file) {
            $data = $file->toArray();

            $this->target->deployPlan->configFiles()->create($data);
        }
    }
}
