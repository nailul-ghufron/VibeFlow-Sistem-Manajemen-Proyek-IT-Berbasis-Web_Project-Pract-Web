<?php
$current_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$role = $_SESSION['user_role'] ?? '';

$nav_items = [
    ['url' => '/dashboard', 'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>', 'label' => 'Dashboard'],
    ['url' => '/projects', 'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>', 'label' => 'Projects'],
];
?>
<div id="sidebar-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 hidden lg:hidden opacity-0 transition-opacity duration-300"></div>
<aside id="sidebar" class="w-64 fixed h-screen bg-[#0f172a] border-r border-white/10 flex flex-col z-40 -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow-2xl lg:shadow-none">
    <div class="h-16 flex items-center px-6 border-b border-white/10">
        <span class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500 drop-shadow-[0_0_8px_rgba(34,211,238,0.6)]">VibeFlow</span>
    </div>
    
    <nav class="flex-1 py-6 px-4 space-y-2">
        <?php foreach ($nav_items as $item): ?>
            <?php 
                $isActive = ($current_uri === $item['url'] || strpos($current_uri, $item['url'] . '/') === 0);
                $activeClass = $isActive ? 'bg-cyan-500/10 text-cyan-400 border-l-2 border-cyan-400' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200 border-l-2 border-transparent';
            ?>
            <a href="<?= $item['url'] ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-r-lg transition-colors <?= $activeClass ?>">
                <?= $item['icon'] ?>
                <span class="font-medium"><?= $item['label'] ?></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="p-4 border-t border-white/10">
        <a href="/auth/logout" class="flex items-center gap-3 px-3 py-2.5 text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            <span class="font-medium">Logout</span>
        </a>
    </div>
</aside>
