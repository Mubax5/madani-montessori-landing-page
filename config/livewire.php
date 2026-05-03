<?php

$temporaryUploadDisk = env(
    'LIVEWIRE_TEMPORARY_FILE_UPLOAD_DISK',
    env('FILESYSTEM_DISK') === 's3' ? 's3' : null,
);

return [
    'temporary_file_upload' => [
        'disk' => $temporaryUploadDisk,
        'rules' => ['required', 'file', 'max:5120'],
        'directory' => env('LIVEWIRE_TEMPORARY_FILE_UPLOAD_DIRECTORY', 'livewire-tmp'),
        'middleware' => 'throttle:60,1',
        'preview_mimes' => ['png', 'jpg', 'jpeg', 'webp'],
        'max_upload_time' => 5,
        'cleanup' => true,
    ],
];
