<?php

    namespace App\Services\Admin;

    use App\Models\User;
    use App\Services\BaseService;
    use Exception;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Mail;
    use Yajra\DataTables\DataTables;
    use Illuminate\Support\Str;


    class UserService extends BaseService
    {
        protected string $folder = 'content.users';
        protected string $route = 'users';

        public function __construct(protected User $objModel, protected Mail $mail , protected \App\Models\UserMail $userMail)
        {
            parent::__construct($objModel);
        }

        public function index($request)
        {
            if ($request->ajax()) {
                $query = $this->model->query();

                    return DataTables::of($query)
                        ->editColumn('name', fn($obj) => $obj->name)
                        ->editColumn('email', function($obj) {
                            return $obj->UserMail ? $obj->UserMail->email : '';
                        })
                        ->editColumn('created_at', fn($obj) => $obj->created_at->format("Y-m-d"))
                        ->editColumn('status', fn($obj) => $this->statusDatatable($obj))
                        ->addColumn('action', function ($obj) {
                            $user = Auth::guard('admin')->user();
                            $buttons = '';

                            if ($user && $user->can($this->route . "_edit")) {
                                $buttons .= '
                                    <a href="' . route($this->route . '.edit', $obj->id) . '" 
                                    class="btn btn-sm btn-info me-1" 
                                    title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>';
                            }

                            if ($user && $user->can($this->route . "_delete")) {
                                $buttons .= '
                                    <button type="button" 
                                            class="btn btn-sm btn-danger delete-confirm" 
                                            data-url="' . route($this->route . '.destroy', $obj->id) . '" 
                                            title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>';
                            }


                            return $buttons;
                        })
                        ->editColumn('image', function ($obj) {
                            return $this->iamgeFromStorage($obj->image);
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
            return view($this->folder . "/partials/create");
        }

        public function store($request){
            try{

                $request['code'] = rand(100000, 999999) . Str::random(5); 

                if ($request->hasFile('image')) {
                    $path = $this->handleFile($request->file('image'), $this->route, 'image');
                    $request['newImage'] = $path;
                }

               if($request->has("email")){
                    $mail = $this->userMail->create([
                        "email" => $request->email,
                        "otp" => rand(100000, 999999),
                        "verified_at" => now(),
                    ]);
                    $request->merge(['email_id' => $mail->id]);
                }   
                

                $request['status'] = 0;

                $this->objModel->create([
                    "name" => $request->name,
                    "email_id" => $request->email_id,
                    "password" => $request->password,
                    "code" => $request->code,
                    "email" => $request->email,
                    "status" => $request->status,
                    "image" => $request->newImage,
                ]);

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
                return view($this->folder . "/partials/edit", [
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
                $user = $this->objModel->findOrFail($id);

                if ($request->hasFile('image')) {
                    $path = $this->handleFile($request->file('image'), $this->route, 'image');
                    $request->merge(['newImage' => $path]);
                }

                $user->userMail()->update([
                    "email" => $request->email ?? $user->UserMail->email,
                ]);

                $user->update([
                    "name" => $request->name ?? $user->name,
                    "password" => $request->password ? bcrypt($request->password) : $user->password,
                    "code" => $request->code ?? $user->code,
                    "email" => $request->email ?? $user->email,
                    "status" => $request->status ?? $user->status,
                    "image" => $request->newImage ?? $user->image,
                ]);

                toastr()->success(trns("update success"));
                return redirect()->route("users.index");
            } catch (Exception $e) {
                toastr()->error(trns("update failed: " . $e->getMessage()));
                return redirect()->back()->withInput();
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