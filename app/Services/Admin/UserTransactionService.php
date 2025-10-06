<?php

namespace App\Services\Admin;

use App\Models\UserTransaction as ObjModel;
use App\Services\BaseService;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class UserTransactionService extends BaseService
{
    protected string $folder = 'content/user_transaction';
    protected string $route = 'user_transactions';

    public function __construct(ObjModel $objModel)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
            ->editColumn('user_id', function ($obj) {
                            return $obj->user ? $obj->user->name : '';
                        })
                ->addColumn('action', function ($obj) {
                            $user = Auth::guard('admin')->user();
                            $buttons = '';

                            if ($user && $user->can($this->route . "_edit")) {
                                $buttons .= '
                                    <a href="' . route($this->route . '.edit', $obj->id) . '" 
                                    class="btn btn-sm btn-primary me-1" 
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
        return view("{$this->folder}/partials/create", [
            'storeRoute' => route("{$this->route}.store"),
            "route" => $this->route,
        ]);
    }

    public function store($data)
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'UserTransaction');
        }

        try {
            $this->createData($data);
            return redirect()->route("{$this->route}.index")->with(['success' => trns('The operation was successful.')]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => trns('An error occurred.') . ' ' . $e->getMessage()])->withInput();

        }
    }

    public function edit($obj)
    {
        return view("{$this->folder}/partials/edit", [
            'obj' => $obj,
            "route" => $this->route,
            'updateRoute' => route("{$this->route}.update", (int)$obj->id),
        ]);
    }

    public function update($data, $id)
    {
        $oldObj = $this->getById($id);

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'UserTransaction');

            if ($oldObj->image) {
                $this->deleteFile($oldObj->image);
            }
        }

        try {
            $oldObj->update($data);
            return redirect()->route("{$this->route}.index")->with(['success' => trns('The operation was successful.')]);

        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => trns('An error occurred.') . ' ' . $e->getMessage()])->withInput();

        }
    }
}