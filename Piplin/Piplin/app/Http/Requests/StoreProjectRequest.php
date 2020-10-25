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
 * Request for validating projects.
 */
class StoreProjectRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'               => 'required|max:255',
            'branch'             => 'nullable|max:255',
            'targetable_id'      => 'nullable|integer',
            'key_id'             => 'nullable|integer|exists:keys,id',
            'builds_to_keep'     => 'nullable|integer|min:1|max:20',
            'deploy_path'        => 'required',
            'url'                => 'url|nullable',
            'build_url'          => 'url|nullable',
            'allow_other_branch' => 'boolean',
        ];

        // On editing add the repository rule
        if ($this->get('id')) {
            $rules['repository'] =  'required';
        }

        return $rules;
    }
}
