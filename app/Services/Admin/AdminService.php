<?php

namespace App\Services\Admin;

use App\Models\Admin as ObjModel;
use App\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class AdminService extends BaseService
{
    protected string $folder = 'content/admin';
    protected string $route = 'admins';

    public function __construct(ObjModel $objModel)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
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

                            if ($user && $user->can($this->route . "_delete") && $obj->id != $user->id && $user->user_name != "admin") {
                                $buttons .= '
                                    <button type="button" class="btn btn-pill btn-danger-light delete-confirm"
                                        data-url="' . route($this->route . '.destroy', $obj->id) . '">
                                        <i class="fas fa-trash"></i>
                                    </button>';

                            }

                            return $buttons;
                        })
                        ->editColumn("created_at", function ($obj) {
                            return $obj->created_at ? $obj->created_at->format('Y-m-d H:i:s') : '';
                        })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => "",
                'route' => $this->route,
            ]);
        }
    }

    public function create()
    {
        return view("{$this->folder}/patials/create", [
            'storeRoute' => route("{$this->route}.store"),
        ]);
    }

    public function store($data)
    {
        try {
            $data['code'] = rand(100000, 999999); 
            $this->createData($data->only($this->model->getFillable()));

            if (request()->ajax()) {
                return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
            }

            return redirect()->route("{$this->route}.index")->with('success', 'تمت العملية بنجاح');

        } catch (\Exception $e) {

            if (request()->ajax()) {
                return response()->json([
                    'status' => 500,
                    'message' => 'حدث خطأ ما.' . $e->getMessage(),
                    'خطأ' => $e->getMessage()
                ]);
            }

            return redirect()->back()->with('error', 'حدث خطأ ما.' . $e->getMessage());
        }
    }





    public function edit($obj)
    {
        return view("{$this->folder}/patials/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $obj->id),
        ]);
    }

    public function update($data, $id)
    {
        $oldObj = $this->getById($id);

        try {
            if($data['password'] == null){
                $data = Arr::except($data, ['password']);
            } else {
                $data['password'] = bcrypt($data['password']);
            }

            $this->updateData($id, $data);

            if (request()->ajax()) {
                return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
            }

            return redirect()->route("{$this->route}.index")->with('success', 'تمت العملية بنجاح');

        } catch (\Exception $e) {

            if (request()->ajax()) {
                return response()->json([
                    'status' => 500,
                    'message' => 'حدث خطأ ما.' . $e->getMessage(),
                    'خطأ' => $e->getMessage()
                ]);
            }

            return redirect()->back()->with('error', 'حدث خطأ ما. ' . $e->getMessage());
        }
    }
    public function profile(){
        $admin = Auth::guard('admin')->user();
        return view("{$this->folder}/profile", [
            'admin' => $admin,
            'updateRoute' => route("{$this->route}.update", $admin->id),
        ]);
    }
}