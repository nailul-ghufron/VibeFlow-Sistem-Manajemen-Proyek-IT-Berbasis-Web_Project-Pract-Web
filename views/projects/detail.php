<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="flex items-center gap-4 mb-6">
    <a href="/projects" class="p-2 text-slate-400 hover:text-white bg-white/5 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <div class="flex items-center gap-4">
        <h1 class="text-4xl font-bold tracking-tight text-slate-100"><?= htmlspecialchars($project['title']) ?></h1>
        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border border-cyan-500/30 bg-cyan-500/10 text-cyan-400">
            <?= htmlspecialchars($project['status']) ?>
        </span>
    </div>
    <div class="ml-auto flex gap-3">
        <?php if (in_array($_SESSION['user_role'], ['pm', 'client', 'super_admin'])): ?>
            <a href="/reports/project/<?= $project['id'] ?>" class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl text-sm font-medium transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                View Report
            </a>
        <?php endif; ?>
        <?php if ($_SESSION['user_role'] === 'super_admin' || ($_SESSION['user_role'] === 'pm' && $project['pm_id'] == $_SESSION['user_id'])): ?>
            <a href="/projects/edit/<?= $project['id'] ?>" class="p-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl text-slate-400 hover:text-white transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Main Content -->
    <div class="lg:col-span-3 space-y-8">
        <!-- Description Card -->
        <div class="glass-card rounded-[2rem] p-8 border border-white/5">
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Project Description</h3>
            <p class="text-slate-300 leading-relaxed text-lg"><?= htmlspecialchars($project['description'] ?: 'No description provided for this project.') ?></p>
        </div>

        <!-- Task Board -->
        <?php if (in_array($_SESSION['user_role'], ['pm', 'programmer', 'super_admin'])): ?>
            <div class="space-y-6">
                <?php include __DIR__ . '/../kanban/board.php'; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="space-y-8">
        <!-- Details Card -->
        <div class="glass-card rounded-[2rem] p-8 border border-white/5">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-[0.2em] mb-8">Project Details</h3>
            <div class="space-y-6">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Client</label>
                    <p class="text-lg font-bold text-slate-200"><?= htmlspecialchars($project['client_name']) ?></p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Project Manager</label>
                    <p class="text-lg font-bold text-slate-200"><?= htmlspecialchars($project['pm_name']) ?></p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Deadline</label>
                    <p class="text-lg font-bold text-rose-500"><?= date('M d, Y', strtotime($project['deadline'])) ?></p>
                </div>
                <div class="pt-4 border-t border-white/5">
                    <div class="flex justify-between items-center mb-4">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Progress</label>
                        <span class="text-sm font-bold text-cyan-400"><?= $project['progress'] ?>%</span>
                    </div>
                    <div class="w-full bg-slate-800/50 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-cyan-400 h-full transition-all duration-700" style="width: <?= $project['progress'] ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Card -->
        <div class="glass-card rounded-[2rem] p-8 border border-white/5">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-[0.2em] mb-8">Documents</h3>
            
            <?php if (in_array($_SESSION['user_role'], ['pm', 'programmer', 'super_admin'])): ?>
                <form action="/documents/upload" method="POST" enctype="multipart/form-data" class="mb-8">
                    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                    <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                    <div class="flex items-center gap-2 p-1 bg-slate-900/50 border border-white/5 rounded-xl">
                        <input type="file" name="file" required class="flex-1 text-[10px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-cyan-500/10 file:text-cyan-400 cursor-pointer outline-none">
                        <button type="submit" class="p-2 bg-cyan-600 hover:bg-cyan-500 text-white rounded-lg transition-all shadow-lg shadow-cyan-500/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        </button>
                    </div>
                </form>
            <?php endif; ?>

            <div class="space-y-3 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                <?php if (empty($documents)): ?>
                    <p class="text-xs text-slate-600 text-center py-4">No documents available.</p>
                <?php else: ?>
                    <?php foreach ($documents as $doc): ?>
                        <a href="/documents/download/<?= $doc['id'] ?>" class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/5 transition-all group">
                            <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center text-slate-500 group-hover:text-cyan-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-slate-300 truncate tracking-tight"><?= htmlspecialchars($doc['file_name']) ?></p>
                                <p class="text-[10px] text-slate-600 mt-0.5"><?= date('M d, Y', strtotime($doc['uploaded_at'])) ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>window.csrf_token = "<?= generate_csrf_token() ?>";</script>
<script src="/assets/js/kanban.js?v=<?= time() ?>"></script>

        </div>
    </main>
</body>
</html>
