<?php

namespace App\Http\Controllers\Hr;


use App\Models\Hr\Announcement;
use App\Models\Hr\AnnouncementRead;
use App\Models\Hr\Notification;
use App\Models\Hr\User;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $module = $this->abortUnlessModuleAllowed('announcements');
        $role = $this->currentRole();
        $user = $this->currentUser();

        $baseQuery = Announcement::whereNotNull('published_at')
            ->when($role !== 'super_admin', function ($q) use ($role) {
                $q->where(function ($w) use ($role) {
                    $w->where('audience_role', 'all')->orWhere('audience_role', $role);
                });
            });

        $totalPublished = (clone $baseQuery)->count();

        $readIds = AnnouncementRead::where('user_id', $user->id)->pluck('announcement_id')->all();

        $unreadTotal = (clone $baseQuery)->whereNotIn('id', $readIds)->count();

        $announcements = (clone $baseQuery)->with('createdBy')
            ->orderByDesc('published_at')
            ->paginate(10)
            ->withQueryString();

        // Viewing the list marks everything currently shown (this page) as read.
        $unreadNow = collect($announcements->items())->pluck('id')->diff($readIds);
        foreach ($unreadNow as $id) {
            AnnouncementRead::create(['announcement_id' => $id, 'user_id' => $user->id, 'read_at' => now()]);
        }

        return view('hr.modules.announcements', array_merge($this->baseViewData(), [
            'module' => $module,
            'moduleKey' => 'announcements',
            'announcements' => $announcements,
            'readIds' => $readIds,
            'totalPublished' => $totalPublished,
            'unreadTotal' => $unreadTotal,
        ]));
    }

    public function store(Request $request)
    {
        $this->abortUnlessModuleAllowed('announcements');
        abort_unless($this->isHrOrAbove(), 403);

        $data = $request->validate([
            'title' => 'required|string|max:200',
            'body' => 'required|string|max:4000',
            'audience_role' => 'required|in:all,employee,manager,hr_admin,super_admin',
        ]);

        $announcement = Announcement::create(array_merge($data, [
            'created_by' => $this->currentUser()->id,
            'published_at' => now(),
            'created_at' => now(),
        ]));

        $recipients = $data['audience_role'] === 'all'
            ? User::pluck('id')
            : User::where('role', $data['audience_role'])->pluck('id');

        foreach ($recipients as $userId) {
            if ($userId === $this->currentUser()->id) {
                continue;
            }
            Notification::notify($userId, 'announcement', 'New announcement: '.$announcement->title, '/modules/announcements');
        }

        return back()->with('status', 'Announcement published.');
    }
}
