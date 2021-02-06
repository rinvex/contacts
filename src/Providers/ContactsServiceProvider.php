<?php

declare(strict_types=1);

namespace Rinvex\Contacts\Providers;

use Rinvex\Contacts\Models\Contact;
use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;
use Rinvex\Contacts\Console\Commands\MigrateCommand;
use Rinvex\Contacts\Console\Commands\PublishCommand;
use Rinvex\Contacts\Console\Commands\RollbackCommand;

class ContactsServiceProvider extends ServiceProvider
{
    use ConsoleTools;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        MigrateCommand::class => 'command.rinvex.contacts.migrate',
        PublishCommand::class => 'command.rinvex.contacts.publish',
        RollbackCommand::class => 'command.rinvex.contacts.rollback',
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'rinvex.contacts');

        // Bind eloquent models to IoC container
        $this->registerModels([
            'rinvex.contacts.contact' => Contact::class,
        ]);

        // Register console commands
        $this->registerCommands($this->commands);
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        // Publish Resources
        $this->publishesConfig('rinvex/laravel-contacts');
        $this->publishesMigrations('rinvex/laravel-contacts');
        ! $this->autoloadMigrations('rinvex/laravel-contacts') || $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }
}
