<?php
/**
 * JY TOUR and TRAVELS - Initial Administrative Setup Credentials
 * 
 * IMPORTANT: Store these credentials securely and update your password
 * from the Admin Panel under "Change Password" upon initial deployment.
 */

return [
    'portal_url' => '/admin/login.php',
    'superadmin' => [
        'username'      => 'admin',
        'email'         => 'jytourandtravels32@gmail.com',
        'password'      => 'Admin@JY2026#Secure',
        'role'          => 'superadmin',
        'assigned_name' => 'JY Administrator'
    ],
    'database' => [
        'default_db_name' => 'jy_tours_travels',
        'default_user'    => 'root',
        'default_pass'    => ''
    ]
];
