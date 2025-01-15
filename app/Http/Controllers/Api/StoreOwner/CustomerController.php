<?php

namespace App\Http\Controllers\Api\StoreOwner;

use App\Helpers\ApiResponse;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdRequest;
use App\Http\Requests\Api\ApiRequest;
use App\Http\Requests\Api\StoreOwner\StoreOwnerRegisterRequest;
use App\Http\Requests\Api\StoreOwner\StoreOwnerUpdateRequest;
use App\Http\Resources\AdResource;
use App\Http\Resources\StoreOwnerResource;
use App\Models\Ad;
use App\Models\StoreOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\UnauthorizedException;

class StoreOwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $StoreOwner = StoreOwner::latest()->paginate(10);
        if (request()->is('api/*')) {
            return PaginationHelper::paginateResponse($StoreOwner, StoreOwnerResource::class, StoreOwner::class);
        }
        return view('admin.auth.master', get_defined_vars());
    }

    public function latest()
    {
        $StoreOwner = StoreOwner::latest()->take(10)->get();
        if (request()->is('api/*')) {
            if (count($StoreOwner) > 0) {
                return ApiResponse::sendResponse(code: 200, msg: 'latest StoreOwners retrived successfully', data: StoreOwnerResource::collection($StoreOwner));
            }
            return ApiResponse::sendResponse(code: 200, msg: 'no StoreOwners found', data: []);
        }
        return view('admin.auth.master', get_defined_vars());
    }


    public function search(Request $request)
    {
        $word = $request->has('search') ? $request->input('search') : null;
        $ads = StoreOwner::when($word != null, function ($q) use ($word) {
            $q->where('title', 'like', '%' . $word . '%');
        })->latest()->paginate(1);
        if ($request->is('api/*')) {
            return  PaginationHelper::paginateResponse($ads, StoreOwnerResource::class, StoreOwner::class);
        }
        return view('admin.auth.master', get_defined_vars());
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $StoreOwner = StoreOwner::find($id);
        if ($id) {
            return ApiResponse::sendResponse(code: 404, msg: 'StoreOwner retrived Successfully', data: new StoreOwnerResource($StoreOwner));
        }
        return ApiResponse::sendResponse(code: 404, msg: 'StoreOwner Not Found', data: []);
    }

    public function info(Request $request)
    {
        return ApiResponse::sendResponse(code: 200, msg: 'Account Retrieved Successfully', data: new StoreOwnerResource($request->user()));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreOwnerUpdateRequest $request, $id)
    {
        // Authenticate and authorize the StoreOwner
        if (!$this->isAuthorized($request, $id)) {
            return ApiResponse::sendResponse(
                code: 403,
                msg: 'Unauthorized access. You can only update your own account.',
                data: []
            );
        }

        // Fetch the StoreOwner
        $StoreOwner = $this->findStoreOwnerById($id);
        if (!$StoreOwner) {
            return ApiResponse::sendResponse(
                code: 404,
                msg: 'StoreOwner not found',
                data: []
            );
        }

        // Process and validate the request
        $validatedData = $this->processValidatedData($request);

        // Update StoreOwner details
        return $this->updateStoreOwnerData($StoreOwner, $validatedData);
    }

    /**
     * Check if the request is authorized for the StoreOwner.
     */
    private function isAuthorized($request, $id)
    {
        $authStoreOwner = $request->user();
        $isAdmin = Auth::guard('admin')->check();

        return ($authStoreOwner && $authStoreOwner->id == $id) || $isAdmin;
    }

    /**
     * Find a StoreOwner by ID.
     */
    private function findStoreOwnerById($id)
    {
        return StoreOwner::find($id);
    }

    /**
     * Process validated data for special fields like password and photo.
     */
    private function processValidatedData($request)
    {
        $validatedData = $request->validated();

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        if (!empty($validatedData['photo'])) {
            $validatedData['photo'] = profileImagePath($request);
        }

        return $validatedData;
    }

    /**
     * Update StoreOwner data and return the appropriate response.
     */
    private function updateStoreOwnerData($StoreOwner, $validatedData)
    {
        $StoreOwner->update($validatedData);

        $changes = $StoreOwner->getChanges();
        if (!empty($changes)) {
            $changedKeys = array_keys($changes);
            $updatedFields = implode(', ', array_diff($changedKeys, ['updated_at']));

            return ApiResponse::sendResponse(
                code: 200,
                msg: $updatedFields . ' updated successfully',
                data: new StoreOwnerResource($StoreOwner)
            );
        }

        return ApiResponse::sendResponse(
            code: 200,
            msg: 'Nothing Changed',
            data: []
        );
    }

    // public function update(StoreOwnerUpdateRequest $request, $id)
    // {
    //     // Authenticate the StoreOwner

    //     $authStoreOwner = $request->user();
    //     if ((!$authStoreOwner || ($authStoreOwner->id != $id)) && !Auth::guard('admin')->check()) {
    //         return ApiResponse::sendResponse(code: 403, msg: 'Unauthorized access. You can only update your own account.', data: []);
    //     }

    //     // Find the StoreOwner by ID
    //     $StoreOwner = StoreOwner::find($id);

    //     if (!$StoreOwner) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'StoreOwner not found',
    //         ], 404);
    //     }

    //     // Validate the incoming request

    //     $validatedData = $request->validated();

    //     // Update the StoreOwner's attributes dynamically
    //     if (isset($validatedData['password'])) {
    //         $validatedData['password'] = Hash::make($validatedData['password']);
    //     }
    //     if (isset($validatedData['photo'])) {
    //         $validatedData['photo'] = profileImagePath($request);
    //     }

    //     // Save the updated StoreOwner
    //     $StoreOwner->update($validatedData);
    //     $changes = $StoreOwner->getChanges();
    //     $changedKeys = array_keys($changes);
    //     if (!empty($changes)) {
    //         $string = json_encode($changedKeys);
    //         $result = str_replace(array('[', ']', '\\', '"', "updated_at"), '', $string);
    //         return ApiResponse::sendResponse(code: 200, msg: $result . ' updated successfully', data: new StoreOwnerResource($StoreOwner));
    //     }
    //     return ApiResponse::sendResponse(code: 200, msg: 'Nothing Changed', data: []);
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $authStoreOwner = $request->user();
        return ApiResponse::sendResponse(code: 200, msg: $request->user());
        if (!$this->isAuthorized($request, $id)) {
            return ApiResponse::sendResponse(code: 403, msg: 'Unauthorized access. You can only Delete your own account.', data: []);
        }
        // Find the StoreOwner by ID
        $StoreOwner = StoreOwner::find($id);
        if (!$StoreOwner)
            return ApiResponse::sendResponse(code: 404, msg: 'Account not found', data: []);
        $StoreOwner->delete();
        return ApiResponse::sendResponse(code: 201, msg: 'Account Deleted Successfully', data: []);
    }
}
