<?php


namespace App\Http\Controllers;


use App\FlowersItems;
use App\FlowersRubrics;
use Illuminate\Http\Request;
use App\Services\FileService;

class FlowersController extends Controller
{
    /**
     * @var FileService
     */
    private $fileService;


    public function __construct(FileService $fileService)
    {
        parent::__construct();
        $this->middleware('auth');
        $this->fileService = $fileService;
    }

    public function index(Request $request)
    {
        $flowers = FlowersItems::select('flowersItems.*', 'flowersRubrics.name as rub_name')
            ->leftJoin('flowersRubrics', 'flowersRubrics.id', 'flowersItems.rubricId')
            ->where('parentId', '0')
            ->where('flowersRubrics.vis', '1');

        if(isset($request->status) && $request->status === 'inactive'){
            $flowers = $flowers->where('flowersItems.status', $request->status);
        } else {
            $flowers = $flowers->where('flowersItems.status', 'visible');
        }
        $flowers =   $flowers->get();
        $flowersItems = FlowersItems::where('status', 'visible')->get();
        $collections = FlowersRubrics::where('vis', '1')->get();

        return view('admin.flowers.list', compact('flowers', 'collections', 'flowersItems'));

    }

    public function edit(Request $request)
    {
        $flower = FlowersItems::where('id', $request->itemId)->first();
        $res = [];
        if (isset($request->price) && !empty($request->price)) {
            $flower->price = trim($request->price);
        } else {
            $res['error'] = 'price is required';
        }

        if (isset($request->name) && !empty($request->name)) {
            $flower->name = trim($request->name);
        } else {
            $res['error'] = 'name is required';
        }

        if (isset($request->uri) && !empty($request->uri)) {
            $flower->uri = trim($request->uri);
        } else {
            $res['error'] = 'uri is required';
        }
        if (isset($request->status) && !empty($request->status)) {
            $flower->status = trim($request->status);
        } else {
            $res['error'] = 'status is required';
        }
        if (isset($request->collection) && !empty($request->collection)) {
            $flower->rubricId = trim($request->collection);
        } else {
            $res['error'] = 'collection is required';
        }

        if (isset($request->text) && !empty($request->text)) {
            $flower->text = trim($request->text);
        } else {
            $res['error'] = 'text is required';
        }


        if ($request->hasFile('preview_file')) {
            if ($flower->preview_file) {
                $this->fileService->remove($flower->preview_file);
            }

            $mainImage = $this->fileService->upload($request->file('preview_file'));
            $flower->preview = $mainImage;
        }

        FlowersItems::where('parentId', $request->itemId)->update(['parentId' => 0]);
        if (isset($request->variants) && !empty($request->variants)) {
            FlowersItems::whereIn('id', $request->variants)->update(['parentId' => $request->itemId]);
        }
        if ($flower->save()) {
            $res['success'] = 'Successfully updated.';
        } else {
            $res['error'] = 'Something went wrong.';
        }
        die(json_encode($res));
    }

    public function add(Request $request)
    {
        $flower = new FlowersItems();
        $res = [];
        if (isset($request->price) && !empty($request->price)) {
            $flower->price = trim($request->price);
        } else {
            $res['error'] = 'price is required';
        }

        if (isset($request->name) && !empty($request->name)) {
            $flower->name = trim($request->name);
        } else {
            $res['error'] = 'name is required';
        }

        if (isset($request->uri) && !empty($request->uri)) {
            $flower->uri = trim($request->uri);
        } else {
            $res['error'] = 'uri is required';
        }
        if (isset($request->collection) && !empty($request->collection)) {
            $flower->rubricId = trim($request->collection);
        } else {
            $res['error'] = 'collection is required';
        }

        if (isset($request->text) && !empty($request->text)) {
            $flower->text = trim($request->text);
        } else {
            $res['error'] = 'text is required';
        }


        if ($request->hasFile('preview_file')) {
            if ($flower->preview_file) {
                $this->fileService->remove($flower->preview_file);
            }

            $mainImage = $this->fileService->upload($request->file('preview_file'));
            $flower->preview = $mainImage;
        } else {
            $res['error'] = 'image is required';
        }


        if ($flower->save()) {
            $res['success'] = 'Successfully updated.';

            if (isset($flower->id)) {
                FlowersItems::whereIn('id', $request->variants)->update(['parentId' => $flower->id]);
            }
        } else {
            $res['error'] = 'Something went wrong.';
        }
        die(json_encode($res));
    }

    public function view($id = false)
    {
        $res = [];
        $variants = [];
        $collections = FlowersRubrics::where('vis', '1')->get();
        $flowersItems = FlowersItems::where('status', 'visible')->get();
        if (is_numeric($id)) {
            $flower = FlowersItems::select('flowersItems.*', 'flowersRubrics.name as rub_name')
                ->leftJoin('flowersRubrics', 'flowersRubrics.id', 'flowersItems.rubricId')
                ->where('flowersItems.id', $id)
                ->first();
            if (isset($flower->id)) {
                $variants = FlowersItems::select('flowersItems.*', 'flowersRubrics.name as rub_name')
                    ->leftJoin('flowersRubrics', 'flowersRubrics.id', 'flowersItems.rubricId')
                    ->where('parentId', $flower->id)
                    ->get();
                $res['flower'] = $flower;
                $res['variants'] = $variants;
                $variantsIds = [];
                foreach ($variants as $key => $variant) {
                    $variantsIds[] = intval($variant->id);
                }
            } else {
                $res['error'] = ' flower not found';
            }
        } else {
            $res['error'] = ' invalid id';
        }

        return view('admin.flowers.view', compact(
            'res',
            'variantsIds',
            'collections',
            'flowersItems'));
    }
}