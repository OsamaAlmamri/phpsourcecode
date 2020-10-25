<?php

/*
 * This file is part of Piplin.
 *
 * Copyright (C) 2016-2017 piplin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Piplin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Piplin\Models\Traits\BroadcastChanges;

/**
 * Pattern model.
 */
class Pattern extends Model
{
    use SoftDeletes, BroadcastChanges;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'copy_pattern', 'build_plan_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Additional attributes to include in the JSON representation.
     *
     * @var array
     */
    protected $appends = ['command_names'];

    /**
     * Belongs to relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function buildPlan()
    {
        return $this->belongsTo(BuildPlan::class);
    }

    /**
     * Belongs to many relationship.
     *
     * @return Command
     */
    public function commands()
    {
        return $this->belongsToMany(Command::class);
    }

    /**
     * Gets the readable list of commands.
     *
     * @return string
     */
    public function getCommandNamesAttribute()
    {
        $commands = [];
        foreach ($this->commands as $command) {
            $commands[] = $command->name;
        }

        if (count($commands)) {
            return implode(', ', $commands);
        }

        return trans('app.none');
    }
}
