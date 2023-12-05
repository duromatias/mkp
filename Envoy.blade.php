@setup
    $baseDir = $remote_path;
    $releaseDir = $baseDir . '/releases';
    $currentDir = $baseDir . '/current';
    $sharedDir = $baseDir . '/shared';
    $release = date('YmdHis');
    $currentReleaseDir = $releaseDir . '/' . $release;

    function logMessage($message) {
        return "echo '\033[32m" .$message. "\033[0m';\n";
    }

    $connection = $user.'@'.$host;
    if (isset($identity)) {
        $identity = trim($identity);
        $connection = '-i '.$identity.' '.$connection;
    }
@endsetup

@servers(['testing' => $connection])

@task('rollback', ['confirm' => true])
    {{ logMessage("Rolling back...") }}
    cd {{ $releaseDir }}
    ln -nfs {{ $releaseDir }}/$(find . -maxdepth 1 -name "20*" | sort  | tail -n 2 | head -n1) {{ $baseDir }}/current
    {{ logMessage("Rolled back!") }}

    {{ logMessage("Rebuilding cache") }}
    php {{ $currentDir }}/artisan route:cache

    php {{ $currentDir }}/artisan config:cache

    php {{ $currentDir }}/artisan view:cache
    {{ logMessage("Rebuilding cache completed") }}

    echo "Rolled back to $(find . -maxdepth 1 -name "20*" | sort  | tail -n 2 | head -n1)"
@endtask

@task('init', [])
    if [ ! -d {{ $sharedDir }} ]; then
        cd {{ $baseDir }}

        mkdir {{$sharedDir}}
        mkdir {{$sharedDir}}/storage
        {{ logMessage("Shared folder created") }}

        {{--chown -R -f {{ $user }}:www-data {{ $sharedDir }}/storage
        chmod -R -f ug+rwx {{ $sharedDir }}/storage--}}

        {{--mkdir {{ $releaseDir }}
        {{ logMessage("Releases folder created") }}--}}

        git clone {{ $repository }} --branch={{ $branch }} --depth=1 -q _temp
        {{ logMessage("Repository cloned") }}

        mv _temp/storage/ {{ $sharedDir }}/
        {{--chown -R {{ $user }}:www-data {{ $sharedDir }}/storage
        chmod -R ug+rwx {{ $sharedDir }}/storage--}}
        {{ logMessage("Storage directory set up") }}

        cp _temp/.env.example {{ $sharedDir }}/.env
        {{ logMessage("Environment file set up") }}

        rm -rf _temp
        {{ logMessage("Deployment path initialised. Run 'envoy run deploy' now.") }}
    else
        {{ logMessage("Deployment path already initialised (current symlink exists)!") }}
    fi
@endtask

@story('deploy')
    init
    git
    composer
    update_symlinks
    {{--set_permissions--}}
    migrate_release
    {{--passport_install--}}
    {{--reload_services--}}
    enable_current_release
    cache
    clean_old_releases
@endstory

@task('git')
    {{ logMessage("Cloning repository") }}
    git clone {{ $repository }} --branch={{ $branch }} --depth=1 -q {{ $currentReleaseDir }}
@endtask

@task('passport_install')
    {{ logMessage("Install passport") }}

    cd {{ $currentReleaseDir }}

    php artisan passport:keys -q -n
@endtask

@task('composer')
    {{ logMessage("Running composer") }}

    cd {{ $currentReleaseDir }}

    composer install --no-interaction --quiet --no-dev --prefer-dist --optimize-autoloader --ignore-platform-reqs
@endtask

@task('update_symlinks')
    {{ logMessage("Updating symlinks") }}

    # Remove the storage directory and replace with persistent data
    {{ logMessage("Linking storage directory") }}
    rm -rf {{ $currentReleaseDir }}/storage;
    cd {{ $currentReleaseDir }};
    ln -nfs {{ $sharedDir }}/storage {{ $currentReleaseDir }}/storage;
    {{-- ln -nfs {{ $sharedDir }}/storage/app/public {{ $currentReleaseDir }}/public/storage --}}

    # Import the environment config
    {{ logMessage("Linking .env file") }}
    cd {{ $currentReleaseDir }};
    ln -nfs {{ $sharedDir }}/.env .env;

    php {{ $currentReleaseDir }}/artisan storage:link
@endtask

@task('enable_current_release')
    # Symlink the latest release to the current directory
    {{ logMessage("Linking current release") }}
    ln -nfs {{ $currentReleaseDir }} {{ $currentDir }};
@endtask

@task('set_permissions')
    # Set dir permissions
    {{ logMessage("Set permissions") }}

    chown -R {{ $user }}:www-data {{$currentReleaseDir}}/bootstrap/cache
    chmod -R ug+rwx {{$currentReleaseDir}}/bootstrap/cache
@endtask

@task('cache')
    {{ logMessage("Building cache") }}

    php {{ $currentDir }}/artisan config:clear
    php {{ $currentDir }}/artisan config:cache

    php {{ $currentDir }}/artisan view:cache
@endtask

@task('clean_old_releases')
    # Delete all but the 3 most recent releases
    {{ logMessage("Cleaning old releases") }}
    cd {{ $releaseDir }}
    ls -dt {{ $releaseDir }}/* | tail -n +4 | xargs -d "\n" rm -rf;
@endtask

@task('migrate_release', ['confirm' => false])
    {{ logMessage("Running migrations") }}

    php {{ $currentReleaseDir }}/artisan migrate

    php {{ $currentReleaseDir }}/artisan db:seed --force

@endtask

@task('npm')
    {{ logMessage("Installing npm packages") }}
    cd {{ $currentReleaseDir }}

    npm install

    {{ logMessage("Compiling assets") }}

    npm run prod
@endtask

{{--@task('migrate', ['on' => 'prod', 'confirm' => true])--}}
    {{--{{ logMessage("Running migrations") }}--}}

    {{--php {{ $currentDir }}/artisan migrate --force--}}
{{--@endtask--}}

{{--@task('migrate_rollback', ['on' => 'prod', 'confirm' => true])--}}
    {{--{{ logMessage("Rolling back migrations") }}--}}

    {{--php {{ $currentDir }}/artisan migrate:rollback --force--}}
{{--@endtask--}}

{{--@task('migrate_status', ['on' => 'prod'])--}}
    {{--php {{ $currentDir }}/artisan migrate:status--}}
{{--@endtask--}}

{{--@task('reload_services', ['on' => 'prod'])--}}
    {{--# Reload Services--}}
    {{--{{ logMessage("Restarting service supervisor") }}--}}
    {{--sudo supervisorctl restart all--}}

    {{--{{ logMessage("Reloading php") }}--}}
    {{--sudo systemctl reload php7.3-fpm--}}
{{--@endtask--}}

@finished
    echo "Envoy deployment script finished.\r\n";
@endfinished
