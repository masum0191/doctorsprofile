<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponStoreRequest;
use App\Http\Requests\CouponUpdateRequest;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $q = Coupon::query();

        if ($request->filled('status') && $request->status !== 'all') {
            $q->where('is_active', $request->status === 'active');
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $q->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->where(function($qq) use ($s) {
                $qq->where('code', 'like', "%{$s}%")
                   ->orWhere('note', 'like', "%{$s}%");
            });
        }

        if ($request->filled('valid') && $request->valid !== 'all') {
            $now = now();
            if ($request->valid === 'valid_now') {
                $q->where('is_active', true)
                  ->where(function($qq) use ($now) {
                      $qq->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
                  })
                  ->where(function($qq) use ($now) {
                      $qq->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
                  });
            } elseif ($request->valid === 'expired') {
                $q->whereNotNull('expires_at')->where('expires_at', '<', $now);
            } elseif ($request->valid === 'scheduled') {
                $q->whereNotNull('starts_at')->where('starts_at', '>', $now);
            }
        }

        $coupons = $q->orderByDesc('id')->paginate(15)->withQueryString();

        return view('coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('coupons.create');
    }

    public function store(CouponStoreRequest $request)
    {
        $data = $request->validated();
        Coupon::create($data);

        return redirect()
            ->route('coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    public function show(Coupon $coupon)
    {
        return view('coupons.show', compact('coupon'));
    }

    public function edit(Coupon $coupon)
    {
        return view('coupons.edit', compact('coupon'));
    }

    public function update(CouponUpdateRequest $request, Coupon $coupon)
    {
        $coupon->update($request->validated());

        return redirect()
            ->route('coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()
            ->route('coupons.index')
            ->with('success', 'Coupon deleted successfully.');
    }

    // Quick toggle active/inactive
    public function toggle(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);

        return back()->with('success', 'Coupon status updated.');
    }
    public function available(Request $request)
    {
        $amount = $request->query('amount');

        $coupons = Coupon::active()
            ->when($amount !== null, function ($query) use ($amount) {
                $query->where('min_amount', '<=', (float) $amount);
            })
            ->where(function($query) {
                $query->whereColumn('usage_limit', '>', 'used_count')
                      ->orWhereNull('usage_limit');
            })
            ->where(function($query) {
                $query->where('starts_at', '<=', now())
                      ->orWhereNull('starts_at');
            })
            ->where(function($query) {
                $query->where('expires_at', '>=', now())
                      ->orWhereNull('expires_at');
            })
            ->get()
            ->map(function ($coupon) {
                $coupon->description = $coupon->description ?: $coupon->note;
                return $coupon;
            });

        return response()->json($coupons);
    }

    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0'
        ]);

        $coupon = Coupon::where('code', strtoupper(trim($request->code)))->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid coupon code'
            ]);
        }

        if (!$coupon->isCurrentlyValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'Coupon is no longer valid'
            ]);
        }

        if ($coupon->min_amount > $request->amount) {
            return response()->json([
                'valid' => false,
                'message' => 'Minimum purchase amount not met'
            ]);
        }

        $discount = $coupon->calculateDiscount((float) $request->amount);

        return response()->json([
            'valid' => true,
            'message' => 'Coupon applied successfully!',
            'coupon' => $coupon,
            'discount_amount' => $discount
        ]);
    }
}
