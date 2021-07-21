<?php

namespace Uasoft\Badaso\Module\Post\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Uasoft\Badaso\Controllers\Controller;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Module\Post\Models\Category;
use Uasoft\Badaso\Rules\ExistsModel;
use Uasoft\Badaso\Rules\UniqueModel;

class CategoryController extends Controller
{
    public function browse()
    {
        try {
            $categories = Category::with('parent', 'children')->get();

            $data['categories'] = $categories->toArray();

            return ApiResponse::success(collect($data)->toArray());
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function add(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'title'      => 'required|string|max:255',
                'meta_title' => 'nullable|string|max:255',
                'slug'       => ['required', 'string', 'max:255', new UniqueModel(Category::class, 'slug')],
                'content'    => 'nullable|string',
                'parent_id'  => ['nullable', new ExistsModel(Category::class, 'id')],
            ]);

            $category = Category::create($request->all());
            $category = json_decode(json_encode($category));

            DB::commit();

            return ApiResponse::success($category);
        } catch (Exception $e) {
            DB::rollback();

            return ApiResponse::failed($e);
        }
    }

    public function read(Request $request)
    {
        try {
            $request->validate([
                'id'     => ['required', new ExistsModel(Category::class, 'id')],
                'except' => 'nullable|in:true,false',
            ]);

            $category = Category::with('parent', 'children')->where('id', $request->id)->first();

            $data['category'] = $category->toArray();

            if ($request->except === 'true') {
                $data['categories'] = Category::where('id', '!=', $request->id)->get()->toArray();
            }

            return ApiResponse::success($data);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function readBySlug(Request $request)
    {
        try {
            $request->validate([
                'slug'   => ['required', new ExistsModel(Category::class, 'slug')],
                'except' => 'nullable|in:true,false',
            ]);

            $category = Category::with('parent', 'children')->where('slug', $request->slug)->first();

            $data['category'] = $category->toArray();

            if ($request->except === 'true') {
                $data['categories'] = Category::where('id', '!=', $request->id)->get()->toArray();
            }

            return ApiResponse::success($data);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function edit(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'id'         => ['required', new ExistsModel(Category::class, 'id')],
                'title'      => 'required|string|max:255',
                'meta_title' => 'nullable|string|max:255',
                'slug'       => ['required', 'string', 'max:255', new ExistsModel(Category::class, 'slug')],
                'content'    => 'nullable|string',
                'parent_id'  => ['nullable', new ExistsModel(Category::class, 'id')],
            ]);

            $category = Category::findOrFail($request->id);
            $category->update($request->all());

            DB::commit();

            return ApiResponse::success($category);
        } catch (Exception $e) {
            DB::rollback();

            return ApiResponse::failed($e);
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'id' => ['required', new ExistsModel(Category::class, 'id')],
            ]);

            $category = Category::findOrFail($request->id);
            $category->delete();

            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollback();

            return ApiResponse::failed($e);
        }
    }

    public function deleteMultiple(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required',
            ]);

            $id_list = explode(',', $request->ids);

            DB::beginTransaction();

            foreach ($id_list as $key => $id) {
                $category = Category::findOrFail($id);
                $category->delete();
            }

            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollback();

            return ApiResponse::failed($e);
        }
    }
}
