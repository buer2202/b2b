<?php

namespace App\Http\Controllers\Admin\GoodsPirce;

use App\Exceptions\CustomException;
use App\Extensions\ImageUpload;
use App\Imports\Admin\RegionServerImport;
use App\Models\Template;
use App\Repositories\Admin\CategoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $dataList = CategoryRepository::getList($request->name);
        $templates = Template::all();
        $type = config('order.fop_order_type');

        return view('admin.goods.category.index', compact('dataList', 'templates', 'type'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'no'                => 'bail|required|string',
            'name'              => 'bail|required|string',
            'sortord'           => 'bail|required|integer',
            'status'            => 'bail|required|in:0,1',
            'type'              => 'bail|required|in:3,4,5,6',
            'user_max_quantity' => 'bail|required|integer',
            'user_max_parvalue' => 'bail|required|integer',
            'show_quick_choose' => 'required|in:0,1',
            'desc'              => 'nullable|max:60000',
            'pay_warning'       => 'nullable|max:60000',
        ]);

        try {
            CategoryRepository::store(
                $request->no,
                $request->name,
                $request->sortord,
                $request->status,
                $request->type,
                $request->user_max_quantity,
                $request->user_max_parvalue,
                $request->show_quick_choose,
                $request->desc,
                $request->pay_warning
            );
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    public function show($id)
    {
        try {
            $data = CategoryRepository::find($id);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1, 'success', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'no'                => 'bail|required|string',
            'name'              => 'bail|required|string',
            'sortord'           => 'bail|required|integer',
            'status'            => 'bail|required|in:0,1',
            'type'              => 'bail|required|in:3,4,5,6',
            'user_max_quantity' => 'bail|required|integer',
            'user_max_parvalue' => 'bail|required|integer',
            'show_quick_choose' => 'required|in:0,1',
            'desc'              => 'nullable|max:60000',
            'pay_warning'       => 'nullable|max:60000',
        ]);

        try {
            CategoryRepository::update(
                $id,
                $request->no,
                $request->name,
                $request->sortord,
                $request->status,
                $request->type,
                $request->user_max_quantity,
                $request->user_max_parvalue,
                $request->show_quick_choose,
                $request->desc,
                $request->pay_warning
            );
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    // 更新模板
    public function updateTemplates(Request $request, $id)
    {
        try {
            CategoryRepository::updateTemplates($id, $request->template_ids);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    // 编辑区服
    public function regionServer($id)
    {
        try {
            $category = CategoryRepository::find($id);
            $dataList = CategoryRepository::regionsServers($id);
        } catch (CustomException $e) {
            abort(404);
        }

        return view('admin.goods.category.region-server', compact('category', 'dataList'));
    }

    // 导入充值区
    public function importRegions($id)
    {
        try {
            $GLOBALS['categoryId'] = $id; // 这个导入包不能传参，只能这么用了。。
            Excel::import(new RegionServerImport, request()->file('excel'));
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    // 图标管理
    public function icons($id)
    {
        try {
            $category = CategoryRepository::find($id);
        } catch (CustomException $e) {
            return abort(404);
        }

        return view('admin.goods.category.icons', compact('category'));
    }

    // 图片上传
    public function uploadIcon($id, Request $request)
    {
        $userAttachmentCategory = config('user.attachment_category');
        if (!in_array($request->icon_type, ['icon_sales', 'icon_order', 'icon_goods'])) {
            return response()->ajax(0, '图标类型错误');
        }

        if (!$request->file('icon')->isValid()) {
            return response()->ajax(0, '上传失败');
        }

        $imageUpload = new ImageUpload($request->file('icon'), 'uploads/category', uniqid($request->icon_type . '_'));

        try {
            $url = CategoryRepository::updateIcon($id, $request->icon_type, $imageUpload->getSavePath());
        }
        catch (CustomException $e) {
            $imageUpload->delete();
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1, 'success', $url);
    }
}
