<?php

    namespace App\Services;

    use App\Mail\UserPasswordMail;
    use App\Models\User;
    use App\Services\BaseService;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Mail;
    use Illuminate\Support\Facades\Password;
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

                // if ($request->filled('keys') && $request->filled('values')) {
                //     $query = $this->search(
                //         $query,
                //         $request->get('keys'),
                //         $request->get('values')
                //     );
                // }

                $results = $query->get();

                return DataTables::of($results)
                
                    ->editColumn('name', function ($obj) {
                        return $obj->name;
                    })
                    ->editColumn('email', function ($obj) {
                        return $obj->email;
                    })
                
                    ->editColumn('created_at', function ($obj) {
                        return $obj->created_at->format("Y-m-d");
                    })
                    ->editColumn('status', function ($obj) {
                        return $this->statusDatatable($obj);
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

    
    }