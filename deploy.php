<?php

namespace Deployer;

require 'recipe/common.php';

host('anwinged.ru')
    ->user('deployer')
    ->stage('production')
    ->set('deploy_path', '/var/www/anwinged')
;

// Saved releases
set('keep_releases', 2);

// Excluded dirs for upload
set('upload_excluded_dirs', []);

// Upload app sources on remote host
task('upload', function () {
    $excluded = array_map(function ($dir) {
        return sprintf('--exclude "%s"', $dir);
    }, get('upload_excluded_dirs'));
    upload(__DIR__ . '/output_prod/', '{{release_path}}', [
        'options' => $excluded,
    ]);
});

// Deploy task
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'upload',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);

after('deploy', 'success');
