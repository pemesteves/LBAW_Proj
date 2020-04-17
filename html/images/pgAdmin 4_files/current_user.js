/////////////////////////////////////////////////////////////
//
// pgAdmin 4 - PostgreSQL Tools
//
// Copyright (C) 2013 - 2020, The pgAdmin Development Team
// This software is released under the PostgreSQL Licence
//
//////////////////////////////////////////////////////////////

define('pgadmin.user_management.current_user', [], function() {
    return {
        'id': 1,
        'email': 'postgres',
        'is_admin': true,
        'name': 'postgres',
        'allow_save_password': true,
        'allow_save_tunnel_password': false
    }
});