<?php

    namespace App\Services;

    use App\Models\User;
    use App\Services\BaseService;
    use Exception;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Mail;
    use Yajra\DataTables\DataTables;

    class UserService extends BaseService
    {
        protected string $folder = 'content.users';
        protected string $route = 'users';

        public function __construct(protected User $objModel, protected Mail $mail)
        {
            parent::__construct($objModel);
        }

        public function index($request)
        {
            if ($request->ajax()) {
                $query = $this->model->query();

                    return DataTables::of($query)
                        ->editColumn('name', fn($obj) => $obj->name)
                        ->editColumn('email', fn($obj) => $obj->email)
                        ->editColumn('created_at', fn($obj) => $obj->created_at->format("Y-m-d"))
                        ->editColumn('status', fn($obj) => $this->statusDatatable($obj))
                        ->addColumn('action', function ($obj) {
                            $user = Auth::guard('admin')->user();
                            $buttons = '';

                            if ($user && $user->can($this->route . "_edit")) {
                                $buttons .= '
                                    <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                                        <a href="' . route($this->route . '.edit', $obj->id) . '" class="text-decoration-none text-info">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </button>';
                            }

                            if ($user && $user->can($this->route . "_delete")) {
                                $buttons .= '
                                    <button type="button" class="btn btn-pill btn-danger-light delete-confirm"
                                        data-url="' . route($this->route . '.destroy', $obj->id) . '">
                                        <i class="fas fa-trash"></i>
                                    </button>';

                            }

                            return $buttons;
                        })
                        ->editColumn('image', function ($obj) {
                            if ($obj->image == null) {
                                $photo = getFileWithName($obj->name);
                                return '<a href="#" class="nav-link pl-2 pr-2 mt-2 leading-none d-flex">
                                            <span>
                                                <img src="' . $photo . '" alt="profile-user"
                                                    class="avatar ml-xl-3 profile-user brround cover-image">
                                            </span>
                                        </a>';
                            }
                            return $this->imageDataTable($obj->image);
                        })
                        ->addIndexColumn()
                        ->escapeColumns([])
                        ->make(true);
                }

            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => trns($this->route),
                'route' => $this->route,
            ]);
        }



        public function create(){
            return view($this->folder . "/patials/create");
        }

        public function store($request){
            try{

                if($request->has("image")){
                    $this->handleFiles($request->file("image") , $this->route);
                }

                $this->createData($request->only($this->objModel->getFillable()));

                toastr()->success(trns("createing success"));
                return redirect()->route("users.index");
            }catch(Exception $e){
                toastr()->error(trns("store field" . $e));
                return redirect()->back();
            }
        }

        public function edit($id){
            try {
                $user = $this->model->findOrFail($id);
                return view($this->folder . "/patials/edit", [
                    'user' => $user,
                    'route' => $this->route,
                ]);
            }
            catch (Exception $e) {
                toastr()->error(trns("edit field" . $e));
                return redirect()->back();
            }
        }

        public function update($request, $id){
            try {
                $user = $this->model->findOrFail($id);

                if ($request->has("image")) {
                    $this->handleFiles($request->file("image"), $this->route);
                }

                $user->update($request->only($this->objModel->getFillable()));

                toastr()->success(trns("update success"));
                return redirect()->route("users.index");
            } catch (Exception $e) {
                toastr()->error(trns("update field" . $e));
                return redirect()->back();
            }
        }

        public function destroy($id){
            try {
                $user = $this->model->findOrFail($id);
              
                $user->delete();
                toastr()->success(trns("delete success"));
                return redirect()->route("users.index");
            } catch (Exception $e) {
                toastr()->error(trns("delete field" . $e));
                return redirect()->back();
            }
        }

    
    }