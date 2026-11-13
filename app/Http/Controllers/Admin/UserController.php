<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AuditAction;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
    ) {}

    public function index(): View
    {
        $users = User::query()
            ->orderBy('name')
            ->paginate((int) config('sdss.pagination.per_page', 15));

        return view('admin.users.index', [
            'users' => $users,
            'roles' => UserRole::cases(),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        /** @var User $actor */
        $actor = $request->user();
        $validated = $request->validated();

        $created = DB::transaction(function () use ($validated, $actor, $request): User {
            $user = User::create([
                'name' => $validated['name'],
                'email' => strtolower((string) $validated['email']),
                'password' => $validated['password'],
                'role' => $validated['role'],
                'is_active' => (bool) $validated['is_active'],
            ]);

            $this->auditLogService->record(
                $actor,
                AuditAction::Insert,
                'users',
                $user->id,
                "Menambahkan akun {$user->name} ({$user->email}) dengan peran {$user->role->value}.",
                $request->ip(),
            );

            return $user;
        });

        return redirect()
            ->route('admin.users.index')
            ->with('status', "Akun pengguna {$created->name} berhasil ditambahkan.");
    }

    /**
     * @throws ValidationException
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        /** @var User $actor */
        $actor = $request->user();
        $validated = $request->validated();

        if ($actor->is($user) && array_key_exists('is_active', $validated) && ! (bool) $validated['is_active']) {
            throw ValidationException::withMessages([
                'is_active' => 'Administrator tidak dapat menonaktifkan akunnya sendiri.',
            ]);
        }

        DB::transaction(function () use ($user, $validated, $actor, $request): void {
            $payload = [
                'name' => $validated['name'],
                'email' => strtolower((string) $validated['email']),
                'role' => $validated['role'],
            ];

            if (array_key_exists('is_active', $validated)) {
                $payload['is_active'] = (bool) $validated['is_active'];
            }

            if (! empty($validated['password'])) {
                $payload['password'] = $validated['password'];
            }

            $user->update($payload);

            $this->auditLogService->record(
                $actor,
                AuditAction::Update,
                'users',
                $user->id,
                "Memperbarui akun {$user->name} ({$user->email}, peran {$user->role->value}).",
                $request->ip(),
            );
        });

        return redirect()
            ->route('admin.users.index')
            ->with('status', "Akun pengguna {$user->name} berhasil diperbarui.");
    }

    /**
     * @throws ValidationException
     */
    public function deactivate(Request $request, User $user): RedirectResponse
    {
        /** @var User $actor */
        $actor = $request->user();

        if ($actor->is($user)) {
            throw ValidationException::withMessages([
                'user' => 'Administrator tidak dapat menonaktifkan akunnya sendiri.',
            ]);
        }

        DB::transaction(function () use ($user, $actor, $request): void {
            $user->update(['is_active' => false]);

            $this->auditLogService->record(
                $actor,
                AuditAction::Deactivate,
                'users',
                $user->id,
                "Menonaktifkan akun {$user->name} ({$user->email}).",
                $request->ip(),
            );
        });

        return redirect()
            ->route('admin.users.index')
            ->with('status', "Akun pengguna {$user->name} telah dinonaktifkan.");
    }

    public function auditLogs(Request $request): View
    {
        $selectedAction = $request->filled('aksi') ? $request->string('aksi')->toString() : null;
        $selectedTable = $request->filled('tabel_target') ? $request->string('tabel_target')->toString() : null;

        $logs = AuditLog::query()
            ->with('user')
            ->when($selectedAction !== null, fn ($query) => $query->where('aksi', $selectedAction))
            ->when($selectedTable !== null, fn ($query) => $query->where('tabel_target', $selectedTable))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate((int) config('sdss.pagination.per_page', 15))
            ->withQueryString();

        return view('admin.audit-log.index', [
            'logs' => $logs,
            'actions' => AuditAction::cases(),
            'selectedAction' => $selectedAction,
            'selectedTable' => $selectedTable,
        ]);
    }
}
