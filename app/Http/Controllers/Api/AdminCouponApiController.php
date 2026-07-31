<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class AdminCouponApiController extends Controller
{
    /**
     * List all coupons.
     */
    public function index(Request $request)
    {
        $query = Coupon::withCount('usages')->orderByDesc('id');

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $coupons = $query->get()->map(function ($coupon) {
            return [
                'id' => $coupon->id,
                'coupon_code' => $coupon->coupon_code,
                'discount_type' => $coupon->discount_type,
                'discount_value' => (float) $coupon->discount_value,
                'expires_at' => $coupon->expires_at ? $coupon->expires_at->format('Y-m-d H:i:s') : null,
                'is_expired' => $coupon->expires_at ? now()->greaterThan($coupon->expires_at) : false,
                'min_order_amount' => (float) $coupon->min_order_amount,
                'max_discount_amount' => $coupon->max_discount_amount ? (float) $coupon->max_discount_amount : null,
                'usage_limit_per_user' => (int) $coupon->usage_limit_per_user,
                'total_usage_limit' => $coupon->total_usage_limit ? (int) $coupon->total_usage_limit : null,
                'total_used_count' => (int) $coupon->total_used_count,
                'is_active' => (bool) $coupon->is_active,
                'description' => $coupon->description,
                'created_at' => $coupon->created_at ? $coupon->created_at->format('Y-m-d H:i:s') : null,
            ];
        });

        return response()->json([
            'success' => true,
            'count' => $coupons->count(),
            'data' => $coupons
        ], 200);
    }

    /**
     * Create a new coupon code.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required|string|max:50|unique:coupons,coupon_code',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'expires_at' => 'nullable|date',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'total_usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $coupon = Coupon::create([
            'coupon_code' => strtoupper(trim($request->coupon_code)),
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'expires_at' => $request->expires_at ? Carbon::parse($request->expires_at) : null,
            'min_order_amount' => $request->input('min_order_amount', 0.00),
            'max_discount_amount' => $request->input('max_discount_amount'),
            'usage_limit_per_user' => $request->input('usage_limit_per_user', 1),
            'total_usage_limit' => $request->input('total_usage_limit'),
            'total_used_count' => 0,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
            'description' => $request->input('description'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Coupon created successfully',
            'data' => $coupon
        ], 201);
    }

    /**
     * View coupon details and usages.
     */
    public function show($id)
    {
        $coupon = Coupon::with(['usages.user:id,name,email'])->find($id);

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $coupon
        ], 200);
    }

    /**
     * Update coupon details.
     */
    public function update(Request $request, $id)
    {
        $coupon = Coupon::find($id);

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required|string|max:50|unique:coupons,coupon_code,' . $id,
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'expires_at' => 'nullable|date',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'total_usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $coupon->update([
            'coupon_code' => strtoupper(trim($request->coupon_code)),
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'expires_at' => $request->expires_at ? Carbon::parse($request->expires_at) : null,
            'min_order_amount' => $request->input('min_order_amount', 0.00),
            'max_discount_amount' => $request->input('max_discount_amount'),
            'usage_limit_per_user' => $request->input('usage_limit_per_user', 1),
            'total_usage_limit' => $request->input('total_usage_limit'),
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : $coupon->is_active,
            'description' => $request->input('description'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Coupon updated successfully',
            'data' => $coupon
        ], 200);
    }

    /**
     * Delete coupon.
     */
    public function destroy($id)
    {
        $coupon = Coupon::find($id);

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon not found'
            ], 404);
        }

        $coupon->delete();

        return response()->json([
            'success' => true,
            'message' => 'Coupon deleted successfully'
        ], 200);
    }
}
