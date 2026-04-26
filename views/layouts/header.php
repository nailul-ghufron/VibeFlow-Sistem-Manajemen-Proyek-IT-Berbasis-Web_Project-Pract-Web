<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VibeFlow Dashboard</title>
    <link rel="icon" type="image/svg+xml" href="/assets/VibeFlow.svg">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0f172a; color: #f1f5f9; }
        .glass-card { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .glass-card:hover { border-color: rgba(34, 211, 238, 0.2); }
        /* Custom Scrollbar for Kanban */
        .kanban-col::-webkit-scrollbar { width: 6px; }
        .kanban-col::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); border-radius: 4px; }
        .kanban-col::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }
        .kanban-col::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }
    </style>
</head>
<body class="flex min-h-screen relative">
    
    <?php include __DIR__ . '/sidebar.php'; ?>

    <main class="flex-1 lg:ml-64 flex flex-col min-h-screen w-full transition-all duration-300">
        <!-- Top Header -->
        <header class="h-16 glass-card sticky top-0 z-10 flex items-center justify-between px-4 md:px-8">
            <div class="flex items-center gap-4">
                <button id="sidebar-toggle" class="lg:hidden p-2 text-slate-400 hover:text-white hover:bg-white/10 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div class="flex items-center text-slate-400">
                    <span class="text-sm font-medium uppercase tracking-wider hidden md:block"><?= htmlspecialchars($_SESSION['user_role'] ?? 'User') ?> Portal</span>
                    <span class="text-sm font-medium uppercase tracking-wider md:hidden">VibeFlow</span>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-sm font-medium text-slate-200"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></p>
                </div>
                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center font-bold shadow-[0_0_10px_rgba(34,211,238,0.3)]">
                    <?= substr($_SESSION['user_name'] ?? 'U', 0, 1) ?>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <div class="p-4 md:p-8 flex-1">

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const sidebarToggle = document.getElementById('sidebar-toggle');

        function toggleSidebar() {
            const isOpen = !sidebar.classList.contains('-translate-x-full');
            
            if (isOpen) {
                // Close sidebar
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('opacity-0');
                setTimeout(() => {
                    sidebarOverlay.classList.add('hidden');
                }, 300);
            } else {
                // Open sidebar
                sidebarOverlay.classList.remove('hidden');
                setTimeout(() => {
                    sidebarOverlay.classList.remove('opacity-0');
                }, 10);
                sidebar.classList.remove('-translate-x-full');
            }
        }

        sidebarToggle.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);

        // Close sidebar on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
                toggleSidebar();
            }
        });
    </script>
