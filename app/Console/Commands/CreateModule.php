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
    protected $signature = 'make:module {name} {--api}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create model, controller, service , api , sidebar , permission, and request for an entity';

    public function handle()
    {
        $enumFile    = app_path("Enums/PermissionEnums.php");
        $name        = $this->argument('name');
        $isApi       = $this->option('api');
        $modelName   = Str::studly($name);
        $serviceName = "{$modelName}Service";
        $controller  = "{$modelName}Controller";
        $requestName = "{$modelName}Request";
        $folderName  = strtolower(Str::snake($modelName));

        if (!$isApi) {
            $this->createModel($modelName);
            $this->createMigration($name, $modelName);
            $this->createController($modelName, $serviceName);
            $this->createService($modelName);
            $this->createRequest($modelName);
            $this->createViews($modelName, $folderName);
            $this->addResourceRoute($modelName, $folderName);
            $this->updatePermissionEnum($modelName, $enumFile);
            $this->updateSidebar($modelName, $folderName);
            $this->createApiController($modelName, $serviceName, $controller);
            $this->addApiRoutes($modelName, $folderName);
        } else {
            $this->createApiController($modelName, $serviceName, $controller);
            $this->addApiRoutes($modelName, $folderName);
        }
    }

    private function createModel($modelName)
    {
        $path = app_path("Models/{$modelName}.php");

        if (File::exists($path)) {
            $this->warn("Model {$modelName} already exists! Skipping creation.");
            return;
        }

        File::put($path, $this->getModelStub($modelName));
        $this->info("Model {$modelName} created successfully, created by Kariem developer. (https://github.com/kariemibrahiem)");
    }

    private function createMigration($name, $modelName)
    {
        $tableName     = Str::snake(Str::pluralStudly($name));
        $migrationName = "create_{$tableName}_table";

        $exists = collect(File::files(database_path('migrations')))
            ->contains(fn($file) => Str::contains($file->getFilename(), $migrationName));

        if ($exists) {
            $this->warn("Migration for table '{$tableName}' already exists! Skipping creation.");
            return;
        }

        $this->call('make:migration', ['name' => $migrationName, '--create' => $tableName]);
        $this->info("Migration {$migrationName} created successfully, created by Kariem developer. (https://github.com/kariemibrahiem)");
    }

    private function createController($modelName, $serviceName)
    {
        $path = app_path("Http/Controllers/Admin/{$modelName}Controller.php");

        if (File::exists($path)) {
            $this->warn("Controller {$modelName} already exists! Skipping creation.");
            return;
        }

        File::ensureDirectoryExists(app_path('Http/Controllers/Admin'));
        File::put($path, $this->getControllerStub($modelName, $serviceName));
        $this->info("Controller {$modelName} created successfully, created by Kariem developer. (https://github.com/kariemibrahiem)");
    }

    private function createService($modelName)
    {
        $serviceName = "{$modelName}Service";
        $path        = app_path("Services/Admin/{$serviceName}.php");

        if (File::exists($path)) {
            $this->warn("Service {$serviceName} already exists! Skipping creation.");
            return;
        }

        File::ensureDirectoryExists(app_path('Services/Admin'));
        File::put($path, $this->getServiceStub($modelName));
        $this->info("Service {$serviceName} created successfully, created by Kariem developer. (https://github.com/kariemibrahiem)");
    }

    private function createRequest($modelName)
    {
        $requestName = "{$modelName}Request";
        $path        = app_path("Http/Requests/{$requestName}.php");

        if (File::exists($path)) {
            $this->warn("Request {$requestName} already exists! Skipping creation.");
            return;
        }

        File::ensureDirectoryExists(app_path('Http/Requests'));
        File::put($path, $this->getRequestStub($modelName));
        $this->info("Request {$requestName} created successfully, created by Kariem developer. (https://github.com/kariemibrahiem)");
    }

    private function createViews($modelName, $folderName)
    {
        $folderPath = resource_path("views/content/{$folderName}");

        if (File::exists($folderPath)) {
            $this->warn("Folder {$folderName} already exists! Skipping creation.");
            return;
        }

        File::ensureDirectoryExists(resource_path('views/content'));
        File::copyDirectory(resource_path('views/example-module'), $folderPath);
        $this->info("Folder {$folderName} created successfully, created by Kariem developer. (https://github.com/kariemibrahiem)");
    }

    private function updatePermissionEnum($modelName, $enumFile)
    {
        $upper = strtoupper($modelName) . "s";
        $lower = strtolower($modelName) . "s";
        $newLine = "    case {$upper} = \"{$lower}\";" . PHP_EOL;

        if (!File::exists($enumFile)) {
            $this->error("PermissionEnums.php not found.");
            return;
        }

        $content = File::get($enumFile);

        if (str_contains($content, "case {$upper} = \"{$lower}\";")) {
            $this->warn("Enum case {$upper} already exists! Skipping creation.");
            return;
        }

        $content = preg_replace(
            '/(\n\s*public function label\(\): string)/',
            PHP_EOL . $newLine . '$1',
            $content
        );

        File::put($enumFile, $content);
        $this->info("Enum case {$upper} added to PermissionEnums, created by Kariem developer. (https://github.com/kariemibrahiem)");
    }

    private function updateSidebar($modelName, $folderName)
    {
        $sidebarFile = resource_path("views/layouts/sections/menu/verticalMenu.php");

        if (!File::exists($sidebarFile)) {
            $this->error("Sidebar file not found: {$sidebarFile}");
            return;
        }

        $slugName  = Str::snake(Str::pluralStudly($modelName));
        $labelName = Str::headline($modelName);
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

        $content = File::get($sidebarFile);

        if (Str::contains($content, "'slug' => '{$slugName}'")) {
            $this->warn("Sidebar for {$slugName} already exists, created by Kariem developer. (https://github.com/kariemibrahiem)");
            return;
        }

        if (preg_match('/return\s*\[.*\];/s', $content)) {
            $content = preg_replace(
                '/(\];\s*)$/',
                ",\n    {$sidebarTag}\n]$1",
                $content
            );
            File::put($sidebarFile, $content);
            $this->info("Sidebar entry for {$slugName} added successfully, created by Kariem developer. (https://github.com/kariemibrahiem)");
        } else {
            File::append($sidebarFile, "\n" . $sidebarTag . ",\n");
            $this->info("Sidebar object for {$slugName} added successfully, created by Kariem developer. (https://github.com/kariemibrahiem)");
        }
    }

    private function createApiController($modelName, $serviceName, $controllerName)
    {
        $path = app_path("Http/Controllers/Api/V1/{$modelName}ApiController.php");

        if (File::exists($path)) {
            $this->warn("Api Controller {$controllerName} already exists! Skipping creation.");
            return;
        }

        File::ensureDirectoryExists(app_path('Http/Controllers/Api/V1'));
        File::put($path, $this->getApiControllerStub($modelName, $serviceName));
        $this->info("Api Controller {$controllerName} created successfully, created by Kariem developer. (https://github.com/kariemibrahiem)");
    }

    private function addApiRoutes($modelName, $folderName)
    {
        $routeFile = base_path("routes/api.php");

        if (!File::exists($routeFile)) {
            $this->error("The routes/api.php file was not found.");
            return;
        }

        $apiRoutes = <<<EOT

        Route::prefix('{$folderName}s')->group(function () {
            Route::get('get-data', [\\App\\Http\\Controllers\\Api\\V1\\{$modelName}ApiController::class, 'getData'])->name('{$folderName}s.index');
            Route::get('/{id}', [\\App\\Http\\Controllers\\Api\\V1\\{$modelName}ApiController::class, 'getById'])->name('{$folderName}s.show');
            Route::post('/', [\\App\\Http\\Controllers\\Api\\V1\\{$modelName}ApiController::class, 'store'])->name('{$folderName}s.store');
            Route::put('/{id}', [\\App\\Http\\Controllers\\Api\\V1\\{$modelName}ApiController::class, 'update'])->name('{$folderName}s.update');
            Route::delete('/{id}', [\\App\\Http\\Controllers\\Api\\V1\\{$modelName}ApiController::class, 'destroy'])->name('{$folderName}s.destroy');
        });
        EOT;

        $fileContent = File::get($routeFile);

        if (Str::contains($fileContent, "{$folderName}s")) {
            $this->warn("API routes for '{$folderName}s' already exist! Skipping creation.");
            return;
        }

        File::append($routeFile, "\n" . $apiRoutes . "\n");
        $this->info("API routes for '{$folderName}s' added successfully , created by Kariem developer. (https://github.com/kariemibrahiem)");
    }

    // ========================
    // Stubs
    // ========================

    private function getModelStub($modelName)
    {
        return <<<EOT
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class {$modelName} extends Model
{
    use HasFactory;

    protected \$guarded = [];
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


    private function getApiControllerStub($modelName, $serviceName)
    {
        return <<<EOT
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\\{$modelName}Request as ObjRequest;
use App\Models\\{$modelName} as ObjModel;
use Illuminate\Http\Request;

class {$modelName}ApiController extends Controller
{
    public function __construct(protected ObjModel \$objModel){}
    public function getData()
    {
        try{
            \$data = \$this->objModel->paginate();
            return \$this->successResponse(\$data, 200, "تمت العملية بنجاح");
        }catch(\Exception \$e){
            return \$this->errorResponse(\$e->getMessage(), 500, "حدث خطأ ما.");
        }
    }
    public function getById(\$id)
    {
        try{
            \$data = \$this->objModel->findOrFail(\$id);
            return \$this->successResponse(\$data, 200, "تمت العملية بنجاح");
        }catch(\Exception \$e){
            return \$this->errorResponse(\$e->getMessage(), 500, "حدث خطأ ما.");
        }
    }

    public function store(ObjRequest \$request)
    {
        try{
            \$data = \$request->validated();
            if (isset(\$data['file'])) {
                \$data['file'] = \$this->handleFile(\$data['file'], '{$modelName}');
            }
            \$obj = \$this->objModel->create(\$data);
            return \$this->successResponse(\$obj, 201, "تمت العملية بنجاح");
        }catch(\Exception \$e){
            return \$this->errorResponse(\$e->getMessage(), 500, "حدث خطأ ما.");
        }
    }

    public function update(ObjRequest \$request, \$id)
    {
        try{
            \$data = \$request->validated();
            \$obj = \$this->objModel->findOrFail(\$id);
            if (isset(\$data['file'])) {
                \$data['file'] = \$this->handleFile(\$data['file'], '{$modelName}');
                if (\$obj->file) {
                    \$this->deleteFile(\$obj->file);
                }
            }
            \$obj->update(\$data);
            return \$this->successResponse(\$obj, 200, "تمت العملية بنجاح");
        }catch(\Exception \$e){
            return \$this->errorResponse(\$e->getMessage(), 500, "حدث خطأ ما.");
        }
    }

    public function destroy(\$id)
    {
        try{
            \$obj = \$this->objModel->findOrFail(\$id);
            \$obj->delete();
            return \$this->successResponse([], 204, "تمت العملية بنجاح");
        }catch(\Exception \$e){
            return \$this->errorResponse(\$e->getMessage(), 500, "حدث خطأ ما.");
        }
    }

    private function successResponse(\$data =[] , \$status = 200, \$message = "تمت العملية بنجاح"){
        return response()->json(['status' => \$status, 'message' => \$message, 'data' => \$data]);
    }

    private function errorResponse(\$message = "حدث خطأ ما.", \$status = 500, \$error = null){
        return response()->json(['status' => \$status, 'message' => \$message, 'error' => \$error]);
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
            $this->info("Resource route + updateColumnSelected for '{$folderName}s' added inside auth:admin group successfully , created by Kariem developer. (https://github.com/kariemibrahiem)");
        } else {
            $this->error("Could not find auth:admin group in routes/web.php");
        }
    }



}
