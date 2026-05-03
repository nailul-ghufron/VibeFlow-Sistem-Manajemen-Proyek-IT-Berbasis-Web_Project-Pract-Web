<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold mb-2">Projects</h1>
        <p class="text-slate-400">Manage and monitor all your projects.</p>
    </div>
    <?php if (in_array($_SESSION['user_role'], ['pm', 'super_admin'])): ?>
    <a href="/projects/create" class="px-4 py-2 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-semibold rounded-lg shadow-[0_0_15px_rgba(34,211,238,0.4)] transition-all">
        + New Project
    </a>
    <?php endif; ?>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    <?php if (empty($projects)): ?>
        <div class="col-span-full py-12 text-center text-slate-500 glass-card rounded-xl">
            No projects found.
        </div>
    <?php else: ?>
        <?php foreach ($projects as $project): ?>
            <div class="glass-card rounded-xl p-6 flex flex-col transition-all hover:-translate-y-1">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-bold text-slate-100 truncate pr-4" title="<?= htmlspecialchars($project['title']) ?>">
                        <?= htmlspecialchars($project['title']) ?>
                    </h3>
                    <?php 
                        $statusColors = [
                            'planning' => 'bg-slate-500/20 text-slate-300 border-slate-500/30',
                            'active' => 'bg-cyan-500/20 text-cyan-400 border-cyan-500/30',
                            'completed' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                            'archived' => 'bg-rose-500/20 text-rose-400 border-rose-500/30'
                        ];
                        $statusClass = $statusColors[$project['status']] ?? $statusColors['planning'];
                    ?>
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium border <?= $statusClass ?> uppercase tracking-wider">
                        <?= $project['status'] ?>
                    </span>
                </div>
                
                <p class="text-sm text-slate-400 mb-6 flex-1 line-clamp-2">
                    <?= htmlspecialchars($project['description'] ?? 'No description provided.') ?>
                </p>
                
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Progress</span>
                        <span class="font-medium text-cyan-400"><?= $project['progress'] ?>%</span>
                    </div>
                    <div class="w-full bg-slate-700/50 rounded-full h-2">
                        <div class="bg-gradient-to-r from-cyan-400 to-blue-500 h-2 rounded-full transition-all duration-500" style="width: <?= $project['progress'] ?>%"></div>
                    </div>
                </div>
                
                <div class="flex justify-between items-center pt-4 border-t border-slate-700/50 text-sm">
                    <div class="flex items-center gap-2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <?= date('M d, Y', strtotime($project['deadline'])) ?>
                    </div>
                    <div class="flex items-center gap-3">
                        <?php if ($_SESSION['user_role'] === 'super_admin' || ($_SESSION['user_role'] === 'pm' && $project['pm_id'] == $_SESSION['user_id'])): ?>
                            <a href="/projects/edit/<?= $project['id'] ?>" class="text-slate-500 hover:text-cyan-400" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                        <?php endif; ?>
                        <a href="/projects/detail/<?= $project['id'] ?>" class="text-cyan-400 hover:text-cyan-300 font-medium group flex items-center gap-1">
                            Details 
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

        </div>
    </main>
</body>
</html>
