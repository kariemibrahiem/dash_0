<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
class CreateModule extends Command
{
     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //test
    protected $signature = 'make:module {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create model, controller, service, and request for an entity';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $enumFile = app_path('Enums/PermissionEnums.php');
        $name = $this->argument('name');
        $namespace = 'App';
        $modelName = Str::studly($name);
        $serviceName = "{$modelName}Service";
        $controllerName = "{$modelName}Controller";
        $requestName = "{$modelName}Request";

        // Create Model
        $modelPath = app_path("Models/{$modelName}.php");

        if (File::exists($modelPath)) {
            $this->warn("Model {$modelName} already exists! Skipping creation.");
        } else {
            File::put($modelPath, $this->getModelStub($modelName));
            $this->info("Model {$modelName} created successfully, upgraded by Kariem developer.");
        }


        // Create Migration
        $tableName = Str::snake(Str::pluralStudly($name));
        $migrationName = "create_{$tableName}_table";

        // Check if a migration for this table already exists
        $existingMigration = collect(File::files(database_path('migrations')))
            ->contains(function ($file) use ($migrationName) {
                return Str::contains($file->getFilename(), $migrationName);
            });

        if ($existingMigration) {
            $this->warn("Migration for table '{$tableName}' already exists! Skipping creation.");
        } else {
            $this->call('make:migration', [
                'name' => $migrationName,
                '--create' => $tableName,
            ]);
            $this->info("Migration {$migrationName} created successfully, upgraded by Kariem developer.");
        }


        // Create Controller
        $controllerPath = app_path("Http/Controllers/Admin/{$controllerName}.php");

        if (File::exists($controllerPath)) {
            $this->warn("Controller {$controllerName} already exists! Skipping creation.");
        } else {
            File::ensureDirectoryExists(app_path('Http/Controllers/Admin'));
            File::put($controllerPath, $this->getControllerStub($modelName, $serviceName));
            $this->info("Controller {$controllerName} created successfully, upgraded by Kariem developer.");
        }


        // Create Service
        $servicePath = app_path("Services/Admin/{$serviceName}.php");

        if (File::exists($servicePath)) {
            $this->warn("Service {$serviceName} already exists! Skipping creation.");
        } else {
            File::ensureDirectoryExists(app_path('Services/Admin'));
            File::put($servicePath, $this->getServiceStub($modelName));
            $this->info("Service {$serviceName} created successfully, upgraded by Kariem developer.");
        }


        // Create Request
        $requestPath = app_path("Http/Requests/{$requestName}.php");

        if (File::exists($requestPath)) {
            $this->warn("Request {$requestName} already exists! Skipping creation.");
        } else {
            File::ensureDirectoryExists(app_path('Http/Requests'));
            File::put($requestPath, $this->getRequestStub($modelName));
            $this->info("Request {$requestName} created successfully, upgraded by Kariem developer.");
        }

       // Copy folder name example-module to name new model in views
        $folderName = strtolower(Str::snake($modelName)); 
        $folderPath = resource_path("views/content/{$folderName}");

        if (File::exists($folderPath)) {
            $this->warn("Folder {$folderName} already exists! Skipping creation.");
        } else {
            File::ensureDirectoryExists(resource_path('views/content'));
            File::copyDirectory(resource_path('views/example-module'), $folderPath);
            $this->info("Folder {$folderName} created successfully, upgraded by Kariem developer.");
        }


        // Create Routes
        $this->addResourceRoute($modelName, $folderName);

        // Create the enum
        $upper = strtoupper($modelName) . "s";
        $lower = strtolower($modelName) . "s";
        $newLine = "    case {$upper} = \"{$lower}\";" . PHP_EOL;

        if (File::exists($enumFile)) {
            $content = File::get($enumFile);

            if (!str_contains($content, "case {$upper} = \"{$lower}\";")) {
                $content = preg_replace(
                    '/(\n\s*public function label\(\): string)/',
                    PHP_EOL . $newLine . '$1',
                    $content
                );

                File::put($enumFile, $content);
                $this->info("Enum case {$upper} added to PermissionEnums, upgraded by Kariem developer.");
            } else {
                $this->warn("Enum case {$upper} already exists! Skipping creation.");
            }
        } else {
            $this->error("PermissionEnums.php not found.");
        }


        // create the sidebar tag
        $sidebarFile = resource_path("views/layouts/sections/menu/verticalMenu.php");

        $labelName = Str::headline($modelName);
        $slugName  = Str::snake(Str::pluralStudly($modelName));
        $menuHeader = Str::headline($modelName) . " Management";

        $sidebarTag = <<<PHP
        (object)[
            'menuHeader' => '{$menuHeader}',
        ],
        (object)[
            'name' => '{$labelName}',
            'icon' => 'bx bx-user',
            'url' => '{$slugName}.index',
            "permissions" => "{$slugName}_read",
            'slug' => '{$slugName}',
            'submenu' => [
                (object)[
                    'name' => 'All {$labelName}',
                    'url' => '{$slugName}',
                    "permissions" => "{$slugName}_read",
                    'slug' => '{$slugName}',
                ],
                (object)[
                    'name' => 'Create {$modelName}',
                    'url' => '{$slugName}/create',
                    "permissions" => "{$slugName}_create",
                    'slug' => '{$slugName}.create',
                ]
            ]
        ]
        PHP;

        if (File::exists($sidebarFile)) {
            $content = File::get($sidebarFile);

            // Check if already exists
            if (!Str::contains($content, "'slug' => '{$slugName}'")) {
                // Try to insert before the closing array bracket
                if (preg_match('/return\s*\[.*\];/s', $content)) {
                    $content = preg_replace(
                        '/(\];\s*)$/',
                        ",\n    {$sidebarTag}\n]$1",
                        $content
                    );
                    File::put($sidebarFile, $content);
                    $this->info("Sidebar entry for {$slugName} added successfully, upgraded by Kariem developer.");
                } else {
                    // If array format not found, append to file
                    File::append($sidebarFile, "\n" . $sidebarTag . ",\n");
                    $this->info("Sidebar object for {$slugName} added successfully, upgraded by Kariem developer.");
                }
            } else {
                $this->warn("Sidebar for {$slugName} already exists, upgraded by Kariem developer.");
            }
        } else {
            $this->error("Sidebar file not found: {$sidebarFile}");
        }


        
        }

        

    private function getModelStub($modelName)
    {
        return <<<EOT
<?php

namespace App\Models;

class {$modelName} extends BaseModel
{
    protected \$fillable = [];
    protected \$casts = [];

}
EOT;
    }

    private function getControllerStub($modelName, $serviceName)
    {
        return <<<EOT
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\\{$modelName}Request as ObjRequest;
use App\Models\\{$modelName} as ObjModel;
use App\Services\Admin\\{$serviceName} as ObjService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class {$modelName}Controller extends Controller
{
    public function __construct(protected ObjService \$objService) {}

    public function index(Request \$request)
    {
        return \$this->objService->index(\$request);
    }

    public function create()
    {
        return \$this->objService->create();
    }

    public function store(ObjRequest \$data)
    {
        \$data = \$data->validated();
        return \$this->objService->store(\$data);
    }

    public function edit(ObjModel \$model)
    {
        return \$this->objService->edit(\$model);
    }

    public function update(ObjRequest \$request, \$id)
    {
        \$data = \$request->validated();
        return \$this->objService->update(\$data, \$id);
    }

    public function destroy(\$id)
    {
        return \$this->objService->delete(\$id);
    }
        public function updateColumnSelected(Request \$request)
    {
        return \$this->objService->updateColumnSelected(\$request,'status');
    }

    public function deleteSelected(Request \$request){
        return \$this->objService->deleteSelected(\$request);
    }
}
EOT;
    }

    private function getServiceStub($modelName)
    {
        $folderName = strtolower(Str::snake($modelName)); // Derive folder name from model

        return <<<EOT
<?php

namespace App\Services\Admin;

use App\Models\\{$modelName} as ObjModel;
use App\Services\BaseService;
use Yajra\DataTables\DataTables;

class {$modelName}Service extends BaseService
{
    protected string \$folder = 'admin/{$folderName}';
    protected string \$route = '{$folderName}s';

    public function __construct(ObjModel \$objModel)
    {
        parent::__construct(\$objModel);
    }

    public function index(\$request)
    {
        if (\$request->ajax()) {
            \$obj = \$this->getDataTable();
            return DataTables::of(\$obj)
                ->addColumn('action', function (\$obj) {
                            \$user = Auth::guard('admin')->user();
                            \$buttons = '';

                            if (\$user && \$user->can(\$this->route . "_edit")) {
                                \$buttons .= '
                                    <button type="button" data-id="' . \$obj->id . '" class="btn btn-pill btn-info-light editBtn">
                                        <a href="' . route(\$this->route . '.edit', \$obj->id) . '" class="text-decoration-none text-info">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </button>';
                            }

                            if (\$user && \$user->can(\$this->route . "_delete")) {
                                \$buttons .= '
                                    <button type="button" class="btn btn-pill btn-danger-light delete-confirm"
                                        data-url="' . route(\$this->route . '.destroy', \$obj->id) . '">
                                        <i class="fas fa-trash"></i>
                                    </button>';

                            }

                            return \$buttons;
                        })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view(\$this->folder . '/index', [
                'createRoute' => route(\$this->route . '.create'),
                'bladeName' => "",
                'route' => \$this->route,
            ]);
        }
    }

    public function create()
    {
        return view("{\$this->folder}/parts/create", [
            'storeRoute' => route("{\$this->route}.store"),
        ]);
    }

    public function store(\$data): \Illuminate\Http\JsonResponse
    {
        if (isset(\$data['image'])) {
            \$data['image'] = \$this->handleFile(\$data['image'], '{$modelName}');
        }

        try {
            \$this->createData(\$data);
            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
        } catch (\Exception \$e) {
return response()->json(['status' => 500, 'message' => 'حدث خطأ ما.', 'خطأ' => \$e->getMessage()]);

        }
    }

    public function edit(\$obj)
    {
        return view("{\$this->folder}/parts/edit", [
            'obj' => \$obj,
            'updateRoute' => route("{\$this->route}.update", \$obj->id),
        ]);
    }

    public function update(\$data, \$id)
    {
        \$oldObj = \$this->getById(\$id);

        if (isset(\$data['image'])) {
            \$data['image'] = \$this->handleFile(\$data['image'], '{$modelName}');

            if (\$oldObj->image) {
                \$this->deleteFile(\$oldObj->image);
            }
        }

        try {
            \$oldObj->update(\$data);
            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);

        } catch (\Exception \$e) {
return response()->json(['status' => 500, 'message' => 'حدث خطأ ما.', 'خطأ' => \$e->getMessage()]);

        }
    }
}
EOT;
    }



    private function getRequestStub($modelName)
    {
        return <<<EOT
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class {$modelName}Request extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if (\$this->isMethod('put')) {
            return \$this->update();
        } else {
            return \$this->store();
        }
    }

    protected function store(): array
    {
        return [

        ];
    }

    protected function update(): array
    {
        return [

        ];
    }
}
EOT;
    }


    private function addResourceRoute($modelName, $folderName)
    {
        $routeFile = base_path('routes/web.php');

        if (!File::exists($routeFile)) {
            $this->error("The routes/web.php file was not found.");
            return;
        }

        $routePattern = "Route::resource('{$folderName}s'";
        $fileContent = file_get_contents($routeFile);

        if (strpos($fileContent, $routePattern) !== false) {
            $this->warn("Resource route for '{$folderName}s' already exists! Skipping creation.");
            return;
        }

        $searchPattern = '/(Route::group\(\s*\[.*?auth:admin.*?\],\s*function\s*\(\)\s*\{)(.*?)(\}\);)/s';

        if (preg_match($searchPattern, $fileContent, $matches)) {
            $newRoutes = <<<EOT

        Route::resource('{$folderName}s', \\App\\Http\\Controllers\\Admin\\{$modelName}Controller::class);
        Route::post('/{$folderName}s/updateColumnSelected', [\\App\\Http\\Controllers\\Admin\\{$modelName}Controller::class, 'updateColumnSelected'])
            ->name('{$folderName}s.updateColumnSelected');
    EOT;

            $updatedGroup = $matches[1] . $matches[2] . $newRoutes . "\n" . $matches[3];

            $fileContent = preg_replace($searchPattern, $updatedGroup, $fileContent);

            File::put($routeFile, $fileContent);
            $this->info("Resource route + updateColumnSelected for '{$folderName}s' added inside auth:admin group successfully.");
        } else {
            $this->error("Could not find auth:admin group in routes/web.php");
        }
    }



}
