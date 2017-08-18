<?php
namespace Deployer;

require 'recipe/symfony3.php';

// Configuration
set('repository', 'https://github.com/rjcorflo/pronosticapp-backend');
set('git_tty', false); // [Optional] Allocate tty for git on first deployment

// Environment vars
set('env_vars', 'APP_ENV={{env}}');

add('shared_files', []);
add('shared_dirs', ['var/data', 'public/uploads']);
set('writable_dirs', []);

// Clear paths
set('clear_paths', []);

// Assets
set('assets', ['public/css', 'public/images', 'public/js']);

/**
 * Install assets from public dir of bundles
 */
task('deploy:assets:install', function () {
    run('{{env_vars}} {{bin/php}} {{bin/console}} assets:install {{console_options}} {{release_path}}/public');
})->desc('Install bundle assets');

// Hosts
host('solus-dev')
    ->hostname('solus')
    ->stage('development')
    ->roles('app')
    ->set('deploy_path', '~/applications/pronosticapp/production')
    ->set('branch', 'dev')
    ->configFile('~/.ssh/config');


// Tasks

desc('Restart PHP-FPM service');
task('php-fpm:restart', function () {
    // The user must have rights for restart service
    // /etc/sudoers: username ALL=NOPASSWD:/bin/systemctl restart php-fpm.service
    run('sudo systemctl restart php-fpm.service');
});
//after('deploy:symlink', 'php-fpm:restart');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'database:migrate');
