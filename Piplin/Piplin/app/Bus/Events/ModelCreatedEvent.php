<?php

/*
 * This file is part of Piplin.
 *
 * Copyright (C) 2016-2017 piplin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Piplin\Bus\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Event which fires when the server status has changed.
 */
class ModelCreatedEvent extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $model;

    protected $channel;

    /**
     * Create a new event instance.
     *
     * @param Model  $model
     * @param string $channel
     */
    public function __construct($model, $channel)
    {
        $this->model   = $model;
        $this->channel = $channel;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [$this->channel];
    }
}
