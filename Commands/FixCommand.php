<?php

namespace Statamic\Addons\Permissions\Commands;

use Statamic\Extend\Command;
use Statamic\API\Path;

class FixCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix file permissions!';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get the Configuration or Defaults
        $site_root = escapeshellarg(Path::makeFull('/'));
        $shell_path = getenv('PERMISSIONS_SHELL_PATH') ? getenv('PERMISSIONS_SHELL_PATH') : '/usr/bin';
        $user = getenv('PERMISSIONS_USER') ? getenv('PERMISSIONS_USER') : 'www-data';
        $group = getenv('PERMISSIONS_GROUP') ? getenv('PERMISSIONS_GROUP') : 'www-data';
        $folder_permissions = getenv('PERMISSIONS_FOLDERS') ? getenv('PERMISSIONS_FOLDERS') : '775';
        $file_permissions = getenv('PERMISSIONS_FILES') ? getenv('PERMISSIONS_FILES') : '664';
        $lock_statamic = getenv('PERMISSIONS_LOCK_STATAMIC') ? getenv('PERMISSIONS_LOCK_STATAMIC') : false;
        $locked_user = getenv('PERMISSIONS_LOCKED_USER') ? getenv('PERMISSIONS_LOCKED_USER') : 'root';
        $locked_group = getenv('PERMISSIONS_LOCKED_GROUP') ? getenv('PERMISSIONS_LOCKED_GROUP') : 'root';

        // Set Up Enviroment for the Shell
        putenv('SHELL=' . $shell_path);

        // Fix File Ownership
        $this->info('Fixing ownership, this may take a while ...');
        shell_exec('chown -R ' . $user . ':' . $group .' ' . $site_root);

        // Fix Directory Permissions
        $this->info('Fixing directory permissions, this may take a while ...');
        shell_exec('find ' . $site_root . ' -type d -exec chmod ' . $folder_permissions . ' {} \;');

        // Fix File Permissions
        $this->info('Fixing file permissions, this may take a while ...');
        shell_exec('find ' . $site_root . ' -type f -exec chmod ' . $file_permissions . ' {} \;');

        // Lock Statamic
        if ($lock_statamic) {
            $this->info('Securing git folder, this may take a while ...');
            shell_exec('chown -R ' . $locked_user . ':' . $locked_group .' ' . $site_root . '.git');
            shell_exec('chmod -R 770 ' . $site_root . '.git');

            $this->info('Securing Statamic directory, this may take a while ...');
            shell_exec('chown -R ' . $locked_user . ':' . $group .' ' . $site_root . 'statamic');
            shell_exec('find ' . $site_root . 'statamic -type d -exec chmod 755 {} \;');
            shell_exec('find ' . $site_root . 'statamic -type f -exec chmod 640 {} \;');
            shell_exec('find ' . $site_root . 'statamic/resources/dist -type f -exec chmod 644 {} \;');

            $this->info('Securing addons directory, this may take a while ...');
            shell_exec('chown -R ' . $locked_user . ':' . $group .' ' . $site_root . 'site/addons');
            shell_exec('find ' . $site_root . 'site/addons -type d -exec chmod 755 {} \;');
            shell_exec('find ' . $site_root . 'site/addons -type f -exec chmod 640 {} \;');
            shell_exec('find ' . $site_root . 'site/addons/*/resources/assets -type f -exec chmod 644 {} \;');

            $this->info('Securing content directory, this may take a while ...');
            shell_exec('chown -R ' . $locked_user . ':' . $group .' ' . $site_root . 'site/content');
            shell_exec('find ' . $site_root . 'site/content -type d -exec chmod 770 {} \;');
            shell_exec('find ' . $site_root . 'site/content -type f -exec chmod 660 {} \;');

            $this->info('Securing database directory, this may take a while ...');
            shell_exec('chown -R ' . $locked_user . ':' . $locked_group .' ' . $site_root . 'site/database');
            shell_exec('find ' . $site_root . 'site/database -type d -exec chmod 770 {} \;');
            shell_exec('find ' . $site_root . 'site/database -type f -exec chmod 660 {} \;');

            $this->info('Securing settings directory, this may take a while ...');
            shell_exec('chown -R ' . $locked_user . ':' . $group .' ' . $site_root . 'site/settings');
            shell_exec('find ' . $site_root . 'site/settings -type d -exec chmod 770 {} \;');
            shell_exec('find ' . $site_root . 'site/settings -type f -exec chmod 660 {} \;');

            $this->info('Securing storage directory, this may take a while ...');
            shell_exec('chown -R ' . $locked_user . ':' . $group .' ' . $site_root . 'site/storage');
            shell_exec('find ' . $site_root . 'site/storage -type d -exec chmod 770 {} \;');
            shell_exec('find ' . $site_root . 'site/storage -type f -exec chmod 660 {} \;');

            $this->info('Securing tests directory, this may take a while ...');
            shell_exec('chown -R ' . $locked_user . ':' . $locked_group .' ' . $site_root . 'site/tests');
            shell_exec('find ' . $site_root . 'site/tests -type d -exec chmod 770 {} \;');
            shell_exec('find ' . $site_root . 'site/tests -type f -exec chmod 660 {} \;');

            $this->info('Securing themes directory, this may take a while ...');
            shell_exec('chown -R ' . $locked_user . ':' . $group .' ' . $site_root . 'site/themes');
            shell_exec('find ' . $site_root . 'site/themes -type d -exec chmod 755 {} \;');
            shell_exec('find ' . $site_root . 'site/themes -type f -exec chmod 640 {} \;');
            shell_exec('find ' . $site_root . 'site/themes/*/assets -type f -exec chmod 644 {} \;');
            shell_exec('find ' . $site_root . 'site/themes/*/dist -type f -exec chmod 644 {} \;');
            shell_exec('find ' . $site_root . 'site/themes/*/css -type f -exec chmod 644 {} \;');
            shell_exec('find ' . $site_root . 'site/themes/*/js -type f -exec chmod 644 {} \;');
            shell_exec('find ' . $site_root . 'site/themes/*/vendor -type f -exec chmod 644 {} \;');

            $this->info('Securing users directory, this may take a while ...');
            shell_exec('chown -R ' . $locked_user . ':' . $group .' ' . $site_root . 'site/users');
            shell_exec('find ' . $site_root . 'site/users -type d -exec chmod 770 {} \;');
            shell_exec('find ' . $site_root . 'site/users -type f -exec chmod 660 {} \;');

            $this->info('Securing local directory, this may take a while ...');
            shell_exec('find ' . $site_root . 'local -type d -exec chmod 770 {} \;');
            shell_exec('find ' . $site_root . 'local -type f -exec chmod 660 {} \;');

            $this->info('Securing base files, this may take a while ...');
            shell_exec('chown ' . $locked_user . ':' . $group .' ' . $site_root . '.env');
            shell_exec('chmod 640 ' . $site_root . '.env');
            shell_exec('chown ' . $locked_user . ':' . $locked_group .' ' . $site_root . '.gitignore');
            shell_exec('chmod 660 ' . $site_root . '.gitignore');
            shell_exec('chown ' . $locked_user . ':' . $group .' ' . $site_root . '.htaccess');
            shell_exec('chmod 754 ' . $site_root . '.htaccess');
            shell_exec('chown ' . $locked_user . ':' . $locked_group .' ' . $site_root . 'favicon.ico');
            shell_exec('chown ' . $locked_user . ':' . $group .' ' . $site_root . 'index.php');
            shell_exec('chmod 754 ' . $site_root . 'index.php');
            shell_exec('chown ' . $locked_user . ':' . $locked_group .' ' . $site_root . 'phpunit.xml');
            shell_exec('chmod 660 ' . $site_root . 'phpunit.xml');
            shell_exec('chown ' . $locked_user . ':' . $locked_group .' ' . $site_root . 'please');
            shell_exec('chmod 660 ' . $site_root . 'please');
            shell_exec('chown ' . $locked_user . ':' . $locked_group .' ' . $site_root . 'robots.txt');
        }

        // Display an All Clear Message :)
        $this->info('All done! If the script reported any "Operation not permitted" errors, try running it again with sudo.');
    }
}
