<?php

namespace App\Services\Admin;

use App\Models\Admin;
use App\Services\BaseService;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as ObjModel;
use Yajra\DataTables\DataTables;



class RoleService extends BaseService
{
    protected string $folder = 'content/Role';
    protected string $route = 'Role';

    public function __construct(ObjModel $objModel, protected Permission $permission)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {

        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
            
                ->editColumn("permissions" , function ($obj) {
                    return $obj->permissions ? $obj->permissions->count() : '0';
                })
                ->addColumn('action', function ($obj) {
                    $buttons = '';


                    $buttons .= '
                            <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                                <i class="fa fa-edit"></i>
                            </button>';
                    $buttons .= '
                            <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                                data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . e($obj->name) . '">
                                <i class="fas fa-trash"></i>
                            </button>';
                    return $buttons;
                })->addColumn('permissions', function ($models) {
                    return $models->permissions->count() > 0 ? '<span class="badge badge-success">' .
                        $models->permissions->count() . ' ' . trns('permissions')
                        . '</span>' :
                        trns('No_permissions');
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => trns('roles'),
                "route" => $this->route,
            ]);
        }
    }

    public function create()
    {
        return view("{$this->folder}/partials/create", [
            'storeRoute' => route("{$this->route}.store"),
            'permissions' => $this->permission->all(),
            "route" => $this->route,
        ]);
    }

    public function store($data)
    {
        try {
            $role = $this->model->create(['name' => $data['role']]);
            $permissions = $this->permission->whereIn('id', $data['permission_id'])->select('name')->get()->pluck('name')->toArray();
            $role->syncPermissions($permissions);

            return redirect()->route("{$this->route}.index")->with(['success' => trns('The operation was successful.')]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => trns('An error occurred.') . ' ' . $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $role = $this->getById($id);
        return view($this->folder . '/partials/edit', [
            'obj' => $role,
            'permissions' => $this->permission->get(),
            'updateRoute' => route($this->route . '.update', $role->id),
        ]);
    }

    public function update($data, $id)
    {



        try {
            $oldObj = $this->getById($id);
            $permissions = $this->permission->whereIn('id', $data['permission_id'])->select('name')->get()->pluck('name')->toArray();
            $oldObj->syncPermissions($permissions);
            $oldObj->update([
                'name' => $data['role']
            ]);
            return response()->json(['status' => 200, 'message' => trns('Data updated successfully.')]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => trns('Something went wrong.'), trns('error') => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $role = $this->model->find($id);
        $hasUsers = Admin::role($role->name)->exists();


        if ($hasUsers) {

            return response()->json(['status' => 500, 'message' => trns('This role has users'), trns('error')]);
        }

        $role->delete();

        return response()->json(['status' => 200, 'message' => trns('Data deleted successfully.')]);
    }
}
