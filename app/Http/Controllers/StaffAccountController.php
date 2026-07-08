<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class StaffAccountController
{
    public function loginAdmin(Request $request): JsonResponse
    {
        return $this->login($request, ['Admin']);
    }

    public function loginCashier(Request $request): JsonResponse
    {
        return $this->login($request, ['Cashier', 'Staff']);
    }

    public function index(): JsonResponse
    {
        return response()->json(
            DB::table('staff_accounts')
                ->orderBy('id')
                ->get()
                ->map(fn ($account) => $this->formatAccount($account))
                ->values()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:staff_accounts,email'],
            'password' => ['required', 'string', 'min:1'],
            'role' => ['required', Rule::in(['Admin', 'Cashier', 'Staff'])],
            'area' => ['nullable', 'string', 'max:120'],
        ]);

        $values = [
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        if (Schema::hasColumn('staff_accounts', 'area')) {
            $values['area'] = $data['area'] ?? null;
        }

        $id = DB::table('staff_accounts')->insertGetId($values);

        return response()->json($this->formatAccount(DB::table('staff_accounts')->find($id)), 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $account = DB::table('staff_accounts')->find($id);
        if (! $account) {
            return response()->json(['message' => 'Staff account not found.'], 404);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('staff_accounts', 'email')->ignore($id)],
            'password' => ['nullable', 'string', 'min:1'],
            'role' => ['required', Rule::in(['Admin', 'Cashier', 'Staff'])],
            'area' => ['nullable', 'string', 'max:120'],
        ]);

        $values = [
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'role' => $data['role'],
            'updated_at' => now(),
        ];
        if (Schema::hasColumn('staff_accounts', 'area')) {
            $values['area'] = $data['area'] ?? null;
        }

        if (! empty($data['password'])) {
            $values['password'] = Hash::make($data['password']);
        }

        DB::table('staff_accounts')->where('id', $id)->update($values);

        return response()->json($this->formatAccount(DB::table('staff_accounts')->find($id)));
    }

    public function toggleStatus(int $id): JsonResponse
    {
        $account = DB::table('staff_accounts')->find($id);
        if (! $account) {
            return response()->json(['message' => 'Staff account not found.'], 404);
        }

        $nextStatus = $account->status === 'Active' ? 'Inactive' : 'Active';
        DB::table('staff_accounts')->where('id', $id)->update([
            'status' => $nextStatus,
            'updated_at' => now(),
        ]);

        return response()->json($this->formatAccount(DB::table('staff_accounts')->find($id)));
    }

    public function destroy(int $id): JsonResponse
    {
        $account = DB::table('staff_accounts')->find($id);
        if (! $account) {
            return response()->json(['message' => 'Staff account not found.'], 404);
        }

        $activeAdminCount = DB::table('staff_accounts')
            ->where('role', 'Admin')
            ->where('status', 'Active')
            ->count();

        if ($account->role === 'Admin' && $account->status === 'Active' && $activeAdminCount <= 1) {
            return response()->json(['message' => 'Keep at least one active admin account.'], 422);
        }

        DB::table('staff_accounts')->where('id', $id)->delete();

        return response()->json(['message' => 'Staff account deleted.']);
    }

    private function formatAccount(object $account): array
    {
        $formatted = [
            'id' => $account->id,
            'name' => $account->name,
            'email' => $account->email,
            'role' => $account->role,
            'status' => $account->status,
            'lastActive' => $account->last_active_at ? date('M j, Y', strtotime($account->last_active_at)) : '',
        ];
        if (property_exists($account, 'area')) {
            $formatted['area'] = $account->area;
        }

        return $formatted;
    }

    private function login(Request $request, array $allowedRoles): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $account = DB::table('staff_accounts')
            ->where('email', strtolower($data['email']))
            ->first();

        if (
            ! $account
            || ! in_array($account->role, $allowedRoles, true)
            || $account->status !== 'Active'
            || ! Hash::check($data['password'], $account->password)
        ) {
            return response()->json(['message' => 'Wrong email or password.'], 422);
        }

        DB::table('staff_accounts')->where('id', $account->id)->update([
            'last_active_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json($this->formatAccount(DB::table('staff_accounts')->find($account->id)));
    }
}
