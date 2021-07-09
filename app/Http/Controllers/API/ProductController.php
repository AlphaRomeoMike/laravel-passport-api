<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Index Function, 
     * Retrieves all Products
     *
     * @author Abdul Rafay Modi
     * @category CRUD
     * @return Response::json
     * @method GET
     */
    public function Index()
    {
        $data        = Product::all();
        return response()->json([
            'data'          =>  $data,
            'msg'           =>  'Product(s) was/were retrieved',
            'success'       =>  true,
            'count'         =>  count($product)
        ], 200);
    }

    /**
     * Register Function
     *
     * @author Abdul Rafay Modi
     * @category CRUD
     * @param Request $request
     * @return Response::json
     * @method POST
     */
    public function Store(Request $request)
    {
        $data   = $request->validate([
            'name'          =>  ['required', 'string'],
            'quantity'      =>  ['required', 'numeric'],
            'price'         =>  ['required', 'numeric'],
            'slug'          =>  ['required', 'string'],
            'description'   =>  ['required', 'string'],
            'status'        =>  ['required', 'boolean']
        ]);

        try
        {
            $data    = Product::Create($data);

            return response()->json([
                'data'          =>  $data,
                'msg'           =>  'Product was created',
                'success'       =>  true,
                'count'         =>  count($data)
            ]);
        }
        catch (Throwable $e)
        {
            report($e);
        }
    }

    /**
     * Update Function,
     * Will be used to retrieve
     * product data
     *
     * @author Abdul Rafay Modi
     * @category CRUD
     * @param Request $request
     * @param int $id
     * @return Response::json
     */
    public function Update(Request $request, $id)
    {
        $pro   = $request->validate([
            'name'          =>  ['required', 'string'],
            'quantity'      =>  ['required', 'numeric'],
            'price'         =>  ['required', 'numeric'],
            'slug'          =>  ['required', 'string'],
            'description'   =>  ['required', 'string'],
            'status'        =>  ['required', 'boolean']
        ]);
        
        $data       = Product::find($id)->first();

        try 
        {
            if($data != null)
            {
                $product = $data->save($pro);

                if($product != null)
                {
                    return response()->json([
                        'data'          =>  $product,
                        'msg'           =>  'Product was updated',
                        'success'       =>  true,
                        'count'         =>  count($product)
                    ], 201);
                }
                else
                {
                    return response()->json([
                        'data'          =>  [],
                        'msg'           =>  'Product was not updated',
                        'success'       =>  false,
                        'count'         =>  0
                    ], 304);
                }
            }
            else
            {
                return response()->json([
                    'data'          =>  [],
                    'msg'           =>  'Product was not found',
                    'success'       =>  false,
                    'count'         =>  0
                ], 404);
            }
        } 
        catch (Throwable $e) 
        {
            report($e);
        }
    }

    /**
     * Find Function,
     * This function will be used
     * to Find a single product
     *
     * @author Abdul Rafay Modi
     * @category CRUD
     * @param int $id
     * @return Resonse::json
     */
    public function Find($id)
    {
        try 
        {
            $product = Product::find($id)->first();

            return response()->json([
                'data'          =>  $product,
                'msg'           =>  'Product was updated',
                'success'       =>  true,
                'count'         =>  count($product)
            ], 200);
        } 
        catch (Throwable $e) {
            report($e);
        }
    }

    /**
     * Delete Function,
     * This function will be used
     * to delete a single product
     *
     * @author Abdul Rafay Modi
     * @category CRUD
     * @param int $id
     * @return Resonse::json
     */
    public function Destroy($id)
    {
        try
        {
            $data   = Product::destroy($id)->first();

            return response()->json([
                'data'          =>  $data,
                'msg'           =>  'Product was deleted',
                'success'       =>  true,
                'count'         =>  count($product)
            ], 200);
        }
        catch (Throwable $e)
        {
            report($e);
        }
    }
}
