<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view {view}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new blade template.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $view = $this->argument('view');
        $path = $this->viewPath($view);
        $this->createDir($path);

        if (File::exists($path))
        {
            $this->error("View {$path} already exists!");
            return;
        }

        File::put($path, $path);

        $this->info("View {$path} created.");
    }

    private function viewPath($view)
    {
        $view = str_replace('.', '/', $view).'.blade.php';
        return "resources/views/{$view}";
    }

    private function createDir($path)
    {
        $dir = dirname($path);

        if (!file_exists($dir))
        {
            mkdir($dir, 0777, true);
        }
    }
}
