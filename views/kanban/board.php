<?php
// Extracted Kanban board component to be included in detail.php
$col_todo = array_filter($tasks, fn($t) => $t['status'] === 'todo');
$col_ip = array_filter($tasks, fn($t) => $t['status'] === 'in_progress');
$col_done = array_filter($tasks, fn($t) => $t['status'] === 'done');

function render_task_card($task) {
    $priority_colors = [
        'low' => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
        'medium' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
        'high' => 'bg-rose-500/10 text-rose-400 border-rose-500/20'
    ];
    $pcolor = $priority_colors[$task['priority']] ?? $priority_colors['medium'];
    
    echo '<div class="task-card bg-[#1e293b]/50 p-5 rounded-2xl border border-white/5 cursor-grab active:cursor-grabbing hover:border-cyan-500/30 transition-all group relative mb-4" draggable="true" data-id="'.$task['id'].'">';
    
    // Action Buttons
    if (in_array($_SESSION['user_role'], ['pm', 'super_admin'])) {
        echo '<div class="absolute top-4 right-4 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">';
        echo '<button onclick="openEditTaskModal('.htmlspecialchars(json_encode($task)).')" class="p-1.5 bg-slate-800 hover:bg-cyan-600 rounded-lg text-slate-400 hover:text-white transition-colors" title="Edit">';
        echo '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>';
        echo '</button>';
        echo '<button onclick="confirmDeleteTask('.$task['id'].')" class="p-1.5 bg-slate-800 hover:bg-rose-600 rounded-lg text-slate-400 hover:text-white transition-colors" title="Delete">';
        echo '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>';
        echo '</button>';
        echo '</div>';
    }

    echo '<div class="flex justify-between items-start mb-4">';
    echo '<span class="text-[10px] font-bold px-2 py-0.5 rounded border uppercase '.$pcolor.' tracking-widest">'.$task['priority'].'</span>';
    echo '</div>';
    
    echo '<h4 class="font-bold text-slate-200 text-sm mb-4 leading-relaxed">'.htmlspecialchars($task['title']).'</h4>';
    
    echo '<div class="flex justify-between items-center pt-4 border-t border-white/5">';
    echo '<div class="flex items-center gap-2">';
    echo '<div class="w-7 h-7 rounded-full bg-gradient-to-tr from-cyan-600 to-blue-600 flex items-center justify-center text-[10px] font-bold text-white shadow-lg" title="'.htmlspecialchars($task['programmer_name']).'">'.substr($task['programmer_name'], 0, 1).'</div>';
    echo '</div>';
    if ($task['due_date']) {
        echo '<div class="flex items-center text-[10px] font-bold text-slate-500 uppercase tracking-tighter"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'.date('M d', strtotime($task['due_date'])).'</div>';
    }
    echo '</div>';
    echo '</div>';
}
?>

<div class="mb-8 flex justify-between items-center">
    <h3 class="text-2xl font-bold text-slate-100">Task Board</h3>
    <?php if (in_array($_SESSION['user_role'], ['pm', 'super_admin'])): ?>
        <button onclick="document.getElementById('newTaskModal').classList.remove('hidden')" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-xs font-bold uppercase tracking-widest rounded-xl transition-all border border-white/10 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Task
        </button>
    <?php endif; ?>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <!-- TODO Column -->
    <div class="flex flex-col min-h-[600px]">
        <div class="flex justify-between items-center mb-6 px-2">
            <h4 class="font-bold text-xs uppercase tracking-[0.2em] flex items-center gap-3 text-slate-500">
                <div class="w-2.5 h-2.5 rounded-full bg-slate-500 shadow-lg shadow-slate-500/20"></div> To Do
            </h4>
            <span class="text-[10px] font-bold bg-slate-800 text-slate-400 px-2 py-0.5 rounded-full border border-white/5"><?= count($col_todo) ?></span>
        </div>
        <div class="flex-1 kanban-col space-y-4" data-status="todo" id="col-todo">
            <?php foreach($col_todo as $task) render_task_card($task); ?>
        </div>
    </div>

    <!-- IN PROGRESS Column -->
    <div class="flex flex-col min-h-[600px]">
        <div class="flex justify-between items-center mb-6 px-2">
            <h4 class="font-bold text-xs uppercase tracking-[0.2em] flex items-center gap-3 text-yellow-500">
                <div class="w-2.5 h-2.5 rounded-full bg-yellow-500 shadow-lg shadow-yellow-500/20"></div> In Progress
            </h4>
            <span class="text-[10px] font-bold bg-slate-800 text-slate-400 px-2 py-0.5 rounded-full border border-white/5"><?= count($col_ip) ?></span>
        </div>
        <div class="flex-1 kanban-col space-y-4" data-status="in_progress" id="col-ip">
            <?php foreach($col_ip as $task) render_task_card($task); ?>
        </div>
    </div>

    <!-- DONE Column -->
    <div class="flex flex-col min-h-[600px]">
        <div class="flex justify-between items-center mb-6 px-2">
            <h4 class="font-bold text-xs uppercase tracking-[0.2em] flex items-center gap-3 text-emerald-500">
                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-lg shadow-emerald-500/20"></div> Done
            </h4>
            <span class="text-[10px] font-bold bg-slate-800 text-slate-400 px-2 py-0.5 rounded-full border border-white/5"><?= count($col_done) ?></span>
        </div>
        <div class="flex-1 kanban-col space-y-4" data-status="done" id="col-done">
            <?php foreach($col_done as $task) render_task_card($task); ?>
        </div>
    </div>
</div>

<!-- Add Task Modal -->
<?php if (in_array($_SESSION['user_role'], ['pm', 'super_admin'])): ?>
<div id="newTaskModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('newTaskModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-700">
            <div class="px-6 py-4 border-b border-slate-800">
                <h3 class="text-lg font-medium leading-6 text-slate-100" id="modal-title">Add New Task</h3>
            </div>
            <form action="/tasks/create" method="POST">
                <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Task Title</label>
                        <input type="text" name="title" required class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg focus:ring-2 focus:ring-cyan-500 text-slate-100 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Description</label>
                        <textarea name="description" rows="3" class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg focus:ring-2 focus:ring-cyan-500 text-slate-100 outline-none"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">Assign To</label>
                            <select name="programmer_id" required class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-slate-100 outline-none appearance-none">
                                <?php foreach ($programmers as $prog): ?>
                                    <option value="<?= $prog['id'] ?>"><?= htmlspecialchars($prog['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">Priority</label>
                            <select name="priority" class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-slate-100 outline-none appearance-none">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-800/50 flex justify-end gap-3 rounded-b-2xl">
                    <button type="button" onclick="document.getElementById('newTaskModal').classList.add('hidden')" class="px-4 py-2 bg-transparent text-slate-300 hover:text-white rounded-lg transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-400 text-white rounded-lg transition-colors shadow-[0_0_10px_rgba(34,211,238,0.3)]">Create Task</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Edit Task Modal -->
<?php if (in_array($_SESSION['user_role'], ['pm', 'super_admin'])): ?>
<div id="editTaskModal" class="hidden fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('editTaskModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-700">
            <div class="px-6 py-4 border-b border-slate-800">
                <h3 class="text-lg font-medium leading-6 text-slate-100">Edit Task</h3>
            </div>
            <form id="editTaskForm" action="" method="POST">
                <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Task Title</label>
                        <input type="text" name="title" id="edit_task_title" required class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg focus:ring-2 focus:ring-cyan-500 text-slate-100 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Description</label>
                        <textarea name="description" id="edit_task_description" rows="3" class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg focus:ring-2 focus:ring-cyan-500 text-slate-100 outline-none"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">Assign To</label>
                            <select name="programmer_id" id="edit_task_programmer" required class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-slate-100 outline-none appearance-none">
                                <?php foreach ($programmers as $prog): ?>
                                    <option value="<?= $prog['id'] ?>"><?= htmlspecialchars($prog['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">Priority</label>
                            <select name="priority" id="edit_task_priority" class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-slate-100 outline-none appearance-none">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Status</label>
                        <select name="status" id="edit_task_status" class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-slate-100 outline-none appearance-none">
                            <option value="todo">To Do</option>
                            <option value="in_progress">In Progress</option>
                            <option value="done">Done</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Due Date</label>
                        <input type="date" name="due_date" id="edit_task_due_date" class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg focus:ring-2 focus:ring-cyan-500 text-slate-100 outline-none">
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-800/50 flex justify-between items-center rounded-b-2xl">
                    <button type="button" id="deleteTaskBtn" class="px-4 py-2 text-rose-500 hover:text-rose-400 text-sm font-medium transition-colors">Delete Task</button>
                    <div class="flex gap-3">
                        <button type="button" onclick="document.getElementById('editTaskModal').classList.add('hidden')" class="px-4 py-2 bg-transparent text-slate-300 hover:text-white rounded-lg transition-colors">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-400 text-white rounded-lg transition-colors">Update Task</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditTaskModal(task) {
    document.getElementById('editTaskForm').action = '/tasks/edit/' + task.id;
    document.getElementById('edit_task_title').value = task.title;
    document.getElementById('edit_task_description').value = task.description || '';
    document.getElementById('edit_task_programmer').value = task.programmer_id;
    document.getElementById('edit_task_priority').value = task.priority;
    document.getElementById('edit_task_status').value = task.status;
    document.getElementById('edit_task_due_date').value = task.due_date || '';
    
    // Set delete button action
    document.getElementById('deleteTaskBtn').onclick = () => confirmDeleteTask(task.id);
    
    document.getElementById('editTaskModal').classList.remove('hidden');
}

function confirmDeleteTask(id) {
    if (confirm('Are you sure you want to delete this task?')) {
        window.location.href = '/tasks/delete/' + id;
    }
}
</script>
<?php endif; ?>
