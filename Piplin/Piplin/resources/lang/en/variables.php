<?php

/*
 * This file is part of Piplin.
 *
 * Copyright (C) 2016-2017 piplin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    'label'          => 'Variables',
    'create'         => 'Add Variable',
    'create_success' => 'Variable created.',
    'edit'           => 'Edit the variable',
    'edit_success'   => 'Variable updated.',
    'delete_success' => 'The variable has been deleted.',
    'name'           => 'Variable',
    'value'          => 'Value',
    'warning'        => 'The variable could not be saved, please check the form below.',
    'none'           => 'There are currently no variables setup',
    'description'    => 'Sometimes you may need certain environmental variables defined during a deployment ' .
                           'but you do not want to set them in the <code>~/.bashrc</code> file on the server.',
    'example'        => 'For example, you may want to set <code>COMPOSER_PROCESS_TIMEOUT</code> to allow composer ' .
                           'to run for longer, or <code>SYMFONY_ENV</code> if you are running a symfony project.',

];
