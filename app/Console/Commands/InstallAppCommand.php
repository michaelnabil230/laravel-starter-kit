<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;
use function Laravel\Prompts\warning;

final class InstallAppCommand extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install {name? : The project name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install and set up the application.';

    /**
     * Whether to initialize a new Git repository after cleanup.
     */
    protected bool $initializeGit = false;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        app()->detectEnvironment(fn (): string => 'local');

        info('Starting application installation...');

        $this->removeGitRepository();
        $this->installNodeDependencies();
        $this->configureEnvironment();
        $this->reloadEnvironment();
        $this->generateAppKey();
        $this->runDatabaseMigrations();
        $this->seedDatabase();
        $this->createAdmin();
        $this->runPostSetupTasks();
        $this->updateProjectSettings();
        $this->cleanupInstallationFiles();
        $this->initializeGitRepository();

        info('Application installation completed successfully!');
        info('👉 Run `php artisan solo` or `composer run dev` to start the local server.');
    }

    /**
     * Remove any existing Git repository.
     */
    protected function removeGitRepository(): void
    {
        info('Checking for an existing Git repository...');

        if (File::isDirectory(base_path('.git'))) {
            File::deleteDirectory(base_path('.git'));
            info('Existing Git repository removed.');
        }

        $this->initializeGit = confirm('Initialize a new Git repository after installation?', true);
    }

    /**
     * Initialize a new Git repository if requested.
     */
    protected function initializeGitRepository(): void
    {
        if ($this->initializeGit) {
            info('Initializing a new Git repository...');

            exec('git init');
            exec('git add .');
            exec('git commit -m "Initial commit"');
            info('New Git repository initialized and committed.');
        }
    }

    /**
     * Install Node.js dependencies if not already installed.
     */
    protected function installNodeDependencies(): void
    {
        if (! File::exists('node_modules')) {
            info('Installing Node.js dependencies...');
            exec('npm install');
        } else {
            warning('Node modules already installed. Skipping installation.');
        }
    }

    /**
     * Configure the application environment file.
     */
    protected function configureEnvironment(): void
    {
        info('Configuring the environment file...');

        if (! File::exists('.env')) {
            File::copy('.env.example', '.env');
            info('Environment file created.');
        } else {
            warning('Environment file already exists. Skipping creation.');
        }

        $this->updateEnvVariable('APP_ENV', 'local');
    }

    /**
     * Reload the application environment variables.
     */
    protected function reloadEnvironment(): void
    {
        app()->bootstrapWith([
            \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
        ]);
    }

    /**
     * Generate an application key if not already set.
     */
    protected function generateAppKey(): void
    {
        info('Checking for application key...');
        if (empty(env('APP_KEY'))) {
            $this->call('key:generate');
        } else {
            warning('Application key already exists.');
        }
    }

    /**
     * Run database migrations if confirmed by the user.
     */
    protected function runDatabaseMigrations(): void
    {
        if (confirm('Run database migrations?', true)) {
            info('Running database migrations...');
            $this->call('migrate', ['--force' => true]);
        }
    }

    /**
     * Seed the database if confirmed by the user.
     */
    protected function seedDatabase(): void
    {
        if (confirm('Run database seeders?', true)) {
            info('Seeding database...');
            $this->call('db:seed', ['--force' => true]);
        }
    }

    /**
     * Create a new admin account if confirmed by the user.
     */
    protected function createAdmin(): void
    {
        if (confirm('Do you want to create an admin account?', true)) {
            info('Creating admin...');

            $name = text('Enter your Name:', required: true);

            $email = text('Enter your email:', required: true);

            $password = password('Enter your Password:', required: true);

            Admin::factory()->superAdmin()->create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
            ]);
        }
    }

    /**
     * Run post-setup tasks such as linking storage and clearing cache.
     */
    protected function runPostSetupTasks(): void
    {
        $this->callSilent('storage:link');
        $this->callSilent('optimize:clear');
        $this->callSilent('schedule-monitor:sync');
    }

    /**
     * Update project settings such as name and URL.
     */
    protected function updateProjectSettings(): void
    {
        $projectName = text(
            'Enter your project name:',
            default: $this->argument('name') ?: basename(getcwd()),
            required: true,
        );
        $this->updateEnvVariable('APP_NAME', $projectName);

        $projectUrl = text(
            'Enter your project URL:',
            default: 'http://localhost:8000',
            required: true,
        );
        $this->updateEnvVariable('APP_URL', $projectUrl);
    }

    /**
     * Update a specific environment variable in the .env file.
     */
    protected function updateEnvVariable(string $key, string $value): void
    {
        $envPath = base_path('.env');
        if (File::exists($envPath)) {
            file_put_contents($envPath, preg_replace(
                "/^{$key}=.*/m",
                "{$key}=\"{$value}\"",
                file_get_contents($envPath)
            ));
        }
    }

    /**
     * Remove installation-related files if confirmed by the user.
     */
    protected function cleanupInstallationFiles(): void
    {
        if (confirm('Remove installation files?', true)) {
            info('Removing installation files...');
            File::delete(app_path('Console/Commands/InstallCommand.php'));
            File::delete(base_path('install.sh'));
            info('Installation files removed.');
        } else {
            info('Installation files retained.');
        }
    }
}
