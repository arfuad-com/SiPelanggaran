<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nip' => ['required', 'string', 'max:50', 'unique:users,nip'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'in:L,P'],
            'position' => ['nullable', 'string', 'max:100'],
            'domisili' => ['nullable', 'string', 'max:500'],
            'role' => ['required', 'in:admin,bk,guru'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'nip.unique' => 'NIP ini sudah terdaftar di sistem.',
            'email.unique' => 'Email ini sudah terdaftar.',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = 'avatar_'.time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('images/avatars'), $filename);
            $avatarPath = 'images/avatars/'.$filename;
        }

        User::create([
            'nip' => $validated['nip'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'position' => $validated['position'] ?? null,
            'domisili' => $validated['domisili'] ?? null,
            'avatar' => $avatarPath,
            'password' => Hash::make('password123'), // Default password
            'role' => $validated['role'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Akun untuk {$validated['name']} berhasil dibuat dengan password default: password123");
    }

    public function show(User $user): View
    {
        $user->load([
            'reportedViolations' => fn ($q) => $q->with(['student.schoolClass', 'category', 'validator'])->latest('violation_date'),
            'validatedViolations' => fn ($q) => $q->with(['student.schoolClass', 'category'])->latest('violation_date'),
            'counselingSessions' => fn ($q) => $q->with(['student.schoolClass'])->latest('counseling_date'),
        ]);

        $stats = [
            'total_reported' => $user->reportedViolations()->count(),
            'total_validated' => $user->validatedViolations()->count(),
            'total_counseling' => $user->counselingSessions()->count(),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'nip' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'in:L,P'],
            'position' => ['nullable', 'string', 'max:100'],
            'domisili' => ['nullable', 'string', 'max:500'],
            'role' => ['required', 'in:admin,bk,guru'],
            'is_active' => ['required', 'boolean'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = 'avatar_'.$user->id.'_'.time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('images/avatars'), $filename);
            $validated['avatar'] = 'images/avatars/'.$filename;
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', "Data pengguna {$user->name} berhasil diperbarui.");
    }

    public function resetPassword(User $user): RedirectResponse
    {
        $user->update([
            'password' => Hash::make('password123'),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Kata sandi untuk {$user->name} berhasil di-reset ke default: password123");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna {$user->name} berhasil dihapus.");
    }
}
