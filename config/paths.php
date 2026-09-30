<?php

return [
    /*
    |--------------------------------------------------------------------------
    | TalentSafe shared storage
    |--------------------------------------------------------------------------
    |
    | Única raíz física para los archivos administrados por TalentSafe.
    | La estructura interna es idéntica entre local, Cimeira y AWS.
    |
    */
    'storage_root' => env(
        'TALENTSAFE_STORAGE_PATH',
        storage_path('app/talentsafe')
    ),
];