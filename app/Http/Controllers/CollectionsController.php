<?php


namespace App\Http\Controllers;


use App\FlowersRubrics;
use Illuminate\Http\Request;

class CollectionsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $collections = FlowersRubrics::orderBy('ordr', 'ASC')->get();
        return view('admin.collections.main', compact('collections'));
    }

    public function collectionEdit($id)
    {
        $collections = FlowersRubrics::orderBy('ordr', 'ASC')->get();
        $col = FlowersRubrics::find($id);


        return view('admin.collections.main', compact('collections', 'col'));
    }

    public function collectionUpdate($id)
    {
        $col = FlowersRubrics::find($id);
        if (isset($_GET['collection_name'], $_GET['collection_desc'], $_GET['vis'])) {
            $collections = FlowersRubrics::orderBy('ordr', 'ASC')->get();
            $col->name = $_GET['collection_name'];
            $col->text = $_GET['collection_desc'];
            $col->vis = $_GET['vis'];

            $col->save();
            $message = 'Successfully updated.';
            return view('admin.collections.main', compact('collections', 'col', 'message'));
        }

    }

    public function editRubrics(Request $request)
    {
        $res = [];
        if (isset($request->collection_ordr, $request->collection_id)) {
            $flowersRubrics = FlowersRubrics::where('id', $request->collection_id)->first();
            if (isset($flowersRubrics->id)) {
                $flowersRubrics->ordr = $request->collection_ordr;
                if ($flowersRubrics->save()) {
                    $res['success'] = 'successfully updated';
                } else {
                    $res['error'] = 'something went wrong';
                }
            } else {
                $res['error'] = 'collection not found';
            }
        } else {
            $res['error'] = 'courier id not send';
        }
        die(json_encode($res));
    }
    public function add_rubric()
    {
        $collections = FlowersRubrics::orderBy('ordr', 'ASC')->get();
        $new_rubric = new FlowersRubrics();
        $new_rubric->name = $_GET['rubric_name'];
        $new_rubric->text = $_GET['rubric_desc'];
        $new_rubric->vis = $_GET['vis'];
        $new_rubric->ordr = $collections[0]['ordr']-1;
        $new_rubric->cityId = 1;
        $new_rubric->save();
        $new_message = 'Successfully added.';
        return view('admin.collections.main', compact('collections', 'new_rubric', 'new_message'));
    }
}

