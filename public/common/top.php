<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <?php
    // Provide safe defaults to avoid "Undefined variable" notices when a page
    // forgets to set $page_title or $page_subtitle before including this template.
    $page_title = $page_title ?? 'FootPower';
    $page_subtitle = $page_subtitle ?? null;
    ?>
    <title>FootPower! | <?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></title>

    <!-- Preload critical assets -->
    <link rel="preload" href="../css/styles.css" as="style">
    <link rel="preload" href="https://code.jquery.com/jquery-3.7.1.min.js" as="script" crossorigin="anonymous">

    <!-- CSS -->
    <link rel="stylesheet" href="../css/styles.css" media="all">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-..." crossorigin="anonymous" media="all">

    <!-- SVG Sprite for icons -->
    <svg aria-hidden="true" style="position: absolute; width: 0; height: 0; overflow: hidden;" version="1.1"
         xmlns="http://www.w3.org/2000/svg">
        <defs>
            <symbol id="icon-edit" viewBox="0 0 24 24">
                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
            </symbol>
            <symbol id="icon-trash" viewBox="0 0 24 24">
                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
            </symbol>
        </defs>
    </svg>
</head>
<body class="loading" aria-busy="true">
<a href="#main-content" class="skip-link">Skip to main content</a>

<header role="banner" class="site-header">
    <div class="logo" aria-labelledby="logo-title">
        <svg width="120" height="60" viewBox="0 0 120 60" role="img">
            <title id="logo-title">FootPower! Activity Tracker</title>
            <text x="10" y="40" font-size="28" fill="currentColor">F</text>
            <g transform="translate(30,10)" class="logo-icon">
                <circle cx="10" cy="10" r="6"/>
                <ellipse cx="10" cy="25" rx="14" ry="10"/>
            </g>
            <text x="70" y="40" font-size="28" fill="currentColor">T</text>
            <text x="10" y="58" font-size="12" class="power-text">POWER!</text>
        </svg>
    </div>

    <nav aria-label="Main navigation" class="main-nav">
        <!-- Navigation items would go here -->
    </nav>
</header>

<main id="main-content" role="main" class="content-wrapper">
    <article class="content-article">
        <header class="article-header">
            <h1 class="page-title"><?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></h1>
            <?php if ($page_subtitle !== null && $page_subtitle !== ''): ?>
                <p class="page-subtitle"><?php echo htmlspecialchars($page_subtitle, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
        </header>

        <!-- The main content sections would go here -->
    </article>
</main>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
        crossorigin="anonymous"
        defer></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Feature detection example
        if (!('IntersectionObserver' in window)) {
            // Optional: load polyfill or fallback
        }

        // Lightweight module bootstrapper
        // Use window lookup to avoid ReferenceError from undefined identifiers.
        // Consumers can register modules like: window.activitiesModule = { init() { ... } }
        const currentPath = window.location.pathname.split('/').pop() || 'index.php';
        const moduleMap = {
            'index.php': 'activitiesModule',
            'create_data.php': 'createDataModule',
            'update_data.php': 'updateDataModule'
        };

        function init() {
            const key = moduleMap[currentPath];
            const mod = key ? window[key] : null;
            // Optional chaining ensures no crash if module is absent or lacks init()
            mod?.init?.();

            // Mark page ready for assistive tech and CSS
            document.body.classList.remove('loading');
            document.body.removeAttribute('aria-busy');
        }

        init();
    });
</script>
</body>
</html>
