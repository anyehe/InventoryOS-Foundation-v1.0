<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
class ApiController extends Controller { protected function ok(mixed $data, array $meta=[]): JsonResponse { return response()->json(['data'=>$data,'meta'=>array_merge(['api_version'=>config('api.version'),'request_id'=>request()->attributes->get('requestId')],$meta)]); } protected function fail(string $message,int $status=422,array $errors=[]): JsonResponse { return response()->json(['message'=>$message,'errors'=>$errors],$status); } }
