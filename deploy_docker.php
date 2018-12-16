<?php

namespace Deployer;

require 'recipe/common.php';

host('192.168.50.10')
    ->user('homepage')
    ->stage('docker')
    ->set('deploy_path', '/home/homepage/app')
;

// Project name
set('application', 'Homepage');

// Project repository
set('repository', 'git@github.com:anwinged/homepage.git');

// Saved releases
set('keep_releases', 3);

// Upload app sources on remote host
task('upload', function () {
//    $excluded = array_map(function ($dir) {
//        return sprintf('--exclude "%s"', $dir);
//    }, get('upload_excluded_dirs'));
//    upload(__DIR__ . '/output_prod/', '{{release_path}}', [
//        'options' => $excluded,
//    ]);
});

task('build-docker-archive', function() {
    within('{{release_path}}', function () {
        run('make build-docker');
        run('tools/composer install --no-interaction');
        run('tools/npm install');
        run('make build-prod');
    });
});

task('build', function () {
    set('deploy_path', __DIR__.'/.build');
    invoke('deploy:prepare');
    invoke('deploy:release');
    invoke('deploy:update_code');
    invoke('build-docker-archive');
})->local();

task('release', [
    'deploy:prepare',
    'deploy:release',
    'upload',
    'deploy:shared',
    'deploy:writable',
    'deploy:symlink',
]);

task('deploy', [
    'build',
    'release',
    'cleanup',
    'success'
]);
