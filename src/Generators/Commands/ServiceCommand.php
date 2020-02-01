<?php
namespace NamTran\LaravelMakeRepositoryService\Generators\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use NamTran\LaravelMakeRepositoryService\Exceptions\FileAlreadyExistsException;
use NamTran\LaravelMakeRepositoryService\Generators\BindingsServiceGenerator;
use NamTran\LaravelMakeRepositoryService\Generators\ServiceEloquentGenerator;
use NamTran\LaravelMakeRepositoryService\Generators\ServiceInterfaceGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use File;

class ServiceCommand extends Command
{

    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'make:service';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new service.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';

    /**
     * Execute the command.
     *
     * @see fire()
     * @return void
     */
    public function handle(): void
    {
        $this->laravel->call([$this, 'fire'], func_get_args());
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire(): void
    {
        try {
            (new ServiceEloquentGenerator([
                'name'      => $this->argument('name'),
                'force'     => $this->option('force'),
            ]))->run();

            (new ServiceInterfaceGenerator([
                'name'  => $this->argument('name'),
                'force' => $this->option('force'),
            ]))->run();

            $this->info('Service created successfully.');

            /**
             * Binding Service to Service Provider
             */
            $bindingGenerator = new BindingsServiceGenerator([
                'name' => $this->argument('name'),
                'force' => $this->option('force'),
            ]);
            // generate repository service provider
            if (!file_exists($bindingGenerator->getPath())) {
                $this->call('make:provider', [
                    'name' => $bindingGenerator->getConfigGeneratorClassPath($bindingGenerator->getPathConfigNode()),
                ]);
                // placeholder to mark the place in file where to prepend repository bindings
                $provider = File::get($bindingGenerator->getPath());
                File::put($bindingGenerator->getPath(), vsprintf(str_replace('//', '%s', $provider), [
                    '//',
                    $bindingGenerator->bindPlaceholder
                ]));
            }
            $bindingGenerator->run();
            $this->info($this->type . ' created successfully.');
        } catch (FileAlreadyExistsException $e) {
            $this->error($this->type . ' already exists!');

            return;
        }
    }


    /**
     * The array of command arguments.
     *
     * @return array
     */
    public function getArguments(): array
    {
        return [
            [
                'name',
                InputArgument::REQUIRED,
                'The name of class being generated.',
                null
            ],
        ];
    }


    /**
     * The array of command options.
     *
     * @return array
     */
    public function getOptions(): array
    {
        return [
            [
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force the creation if file already exists.',
                null
            ],
        ];
    }
}
