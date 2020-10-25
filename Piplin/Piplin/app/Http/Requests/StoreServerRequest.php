<?php

/*
 * This file is part of Piplin.
 *
 * Copyright (C) 2016-2017 piplin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Piplin\Http\Requests;

use Piplin\Http\Requests\Request;

/**
 * Request for validating servers.
 */
class StoreServerRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'            => 'required|max:255',
            'user'            => 'required|max:255',
            'ip_address'      => 'required|host',
            'enabled'         => 'boolean',
            'targetable_id'   => 'required',
            'targetable_type' => 'required|string',
        ];
    }
}
