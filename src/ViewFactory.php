<?php

namespace SavvyWombat\Caxton;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\View;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;

class ViewFactory
{
    protected static ?ViewFactory $instance = null;
    protected Factory $viewFactory;

    protected function __construct()
    {
        // Yeah, this is messy... but it does the job.

        $container = App::getInstance();

        // we have to bind our app class to the interface
        // as the blade compiler needs the `getNamespace()` method to guess Blade component FQCNs
        $container->instance(Application::class, $container);

        // Dependencies
        $filesystem = new Filesystem;
        $eventDispatcher = new Dispatcher($container);

        // Create View Factory capable of rendering PHP and Blade templates
        $viewResolver = new EngineResolver;
        $bladeCompiler = new BladeCompiler($filesystem, Config::instance()->get('paths.cache'));

        $viewResolver->register('blade', function () use ($bladeCompiler) {
            return new CompilerEngine($bladeCompiler);
        });

        $viewFinder = new FileViewFinder($filesystem, [Config::instance()->get('paths.content')]);

        $this->viewFactory = new Factory($viewResolver, $viewFinder, $eventDispatcher);
        $this->viewFactory->setContainer($container);

        Facade::setFacadeApplication($container);
        $container->instance(\Illuminate\Contracts\View\Factory::class, $this->viewFactory);
        $container->alias(
            \Illuminate\Contracts\View\Factory::class,
            (new class extends View {
                public static function getFacadeAccessor()
                {
                    return parent::getFacadeAccessor();
                }
            })::getFacadeAccessor()
        );
        $container->instance(BladeCompiler::class, $bladeCompiler);
        $container->alias(
            BladeCompiler::class,
            (new class extends Blade {
                public static function getFacadeAccessor()
                {
                    return parent::getFacadeAccessor();
                }
            })::getFacadeAccessor()
        );
    }

    public function make(string $view, ?array $data = [], ?array $mergeData = []): \Illuminate\Contracts\View\View
    {
        return $this->viewFactory->make($view, $data, $mergeData);
    }

    public static function instance(): ViewFactory
    {
        if (! self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
