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

    'manage'            => 'Manage SSH keys',
    'label'             => 'SSH keys',
    'none'              => 'There are currently no SSH keys setup',
    'name'              => 'Name',
    'fingerprint'       => 'Fingerprint',
    'create'            => 'Add SSH key',
    'create_success'    => 'SSH key created.',
    'edit'              => 'Edit the SSH key',
    'edit_success'      => 'SSH key updated.',
    'delete_success'    => 'The SSH key has been deleted!',
    'warning'           => 'The SSH key could not be saved, please check the form below.',
    'view_ssh_key'      => 'View the SSH Key',
    'private_ssh_key'   => 'Private SSH Key',
    'public_ssh_key'    => 'Public SSH Key',
    'ssh_key'           => 'SSH key',
    'ssh_key_info'      => 'If you have a specific private key you wish to use you can paste it here. The key must ' .
                           'not have a passphrase.',
    'ssh_key_example'   => 'An SSH key will be generated automatically if you do not enter one, this is recommended.',
    'server_keys'       => 'This key <b>must</b> be added to the server\'s <code>~/.ssh/authorized_keys</code>. Permission of <code>.ssh/</code> should be 700, and <code>authorized_keys</code> should be 600.' .
                           'for <b>each</b> user you want to run commands as.',
    'git_keys'          => 'The key will also need to be added to the <strong>Deploy Keys</strong> ' .
                           'for your repository unless you are using a public/unautheticated URL.',
    'ssh_key_empty'     => 'SSH key can\'t be empty.',

];
