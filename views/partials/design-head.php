<?php
/** Shared head assets — tokens from DESIGN.md */
$pageTitle = $pageTitle ?? ($title ?? 'Maner Private ITI');
$pageDescription = $pageDescription ?? ($settings['seo_description'] ?? 'Official website of Maner Private ITI, Patna.');
$extraCss = $extraCss ?? [];
?>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0, viewport-fit=cover" name="viewport"/>
<title><?= e($pageTitle) ?></title>
<meta name="description" content="<?= e($pageDescription) ?>">
<!-- PWA Meta Tags -->
<meta name="theme-color" content="#131b2e">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="Maner ITI">
<meta name="mobile-web-app-capable" content="yes">
<meta name="application-name" content="Maner ITI">
<meta name="msapplication-TileColor" content="#131b2e">
<link rel="manifest" href="<?= site_url('manifest.json') ?>">
<link rel="apple-touch-icon" href="<?= asset('icons/icon.svg') ?>">
<link rel="icon" type="image/svg+xml" href="<?= asset('icons/icon.svg') ?>">
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="<?= asset('css/design.css') ?>">
<?php foreach ($extraCss as $css): ?>
<link rel="stylesheet" href="<?= asset('css/' . $css) ?>">
<?php endforeach; ?>
<script id="tailwind-config">
tailwind.config = {
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        "on-tertiary-fixed-variant": "#004b73",
        "secondary-fixed-dim": "#ffb95f",
        "secondary-fixed": "#ffddb8",
        "on-background": "#191c1e",
        "surface-bright": "#f7f9fb",
        "inverse-primary": "#bec6e0",
        "on-primary": "#ffffff",
        "on-primary-fixed": "#131b2e",
        "on-surface-variant": "#45464d",
        "on-tertiary": "#ffffff",
        "tertiary": "#000000",
        "on-tertiary-fixed": "#001d31",
        "on-error-container": "#93000a",
        "on-secondary-container": "#684000",
        "secondary-container": "#fea619",
        "on-primary-container": "#7c839b",
        "surface-tint": "#565e74",
        "tertiary-fixed-dim": "#93ccff",
        "primary-fixed-dim": "#bec6e0",
        "error": "#ba1a1a",
        "on-primary-fixed-variant": "#3f465c",
        "inverse-surface": "#2d3133",
        "secondary": "#855300",
        "on-tertiary-container": "#188ace",
        "surface-container-high": "#e6e8ea",
        "surface": "#f7f9fb",
        "on-secondary-fixed": "#2a1700",
        "background": "#f7f9fb",
        "on-error": "#ffffff",
        "on-secondary": "#ffffff",
        "surface-dim": "#d8dadc",
        "primary": "#000000",
        "primary-container": "#131b2e",
        "tertiary-container": "#001d31",
        "outline-variant": "#c6c6cd",
        "on-surface": "#191c1e",
        "surface-container": "#eceef0",
        "inverse-on-surface": "#eff1f3",
        "surface-variant": "#e0e3e5",
        "surface-container-lowest": "#ffffff",
        "surface-container-low": "#f2f4f6",
        "outline": "#76777d",
        "on-secondary-fixed-variant": "#653e00",
        "error-container": "#ffdad6",
        "surface-container-highest": "#e0e3e5",
        "tertiary-fixed": "#cce5ff",
        "primary-fixed": "#dae2fd",
        "success": "#16a34a"
      },
      borderRadius: {
        DEFAULT: "0.125rem",
        lg: "0.25rem",
        xl: "0.5rem",
        full: "0.75rem"
      },
      spacing: {
        "margin-mobile": "16px",
        base: "8px",
        "section-gap": "80px",
        "container-max": "1280px",
        gutter: "24px"
      },
      fontFamily: {
        display: ["Hanken Grotesk"],
        "body-lg": ["Inter"],
        "headline-lg": ["Hanken Grotesk"],
        "label-sm": ["JetBrains Mono"],
        "headline-md": ["Hanken Grotesk"],
        "headline-lg-mobile": ["Hanken Grotesk"],
        "body-md": ["Inter"]
      },
      fontSize: {
        display: ["48px", { lineHeight: "56px", letterSpacing: "-0.02em", fontWeight: "800" }],
        "body-lg": ["18px", { lineHeight: "28px", fontWeight: "400" }],
        "headline-lg": ["32px", { lineHeight: "40px", fontWeight: "700" }],
        "label-sm": ["12px", { lineHeight: "16px", letterSpacing: "0.05em", fontWeight: "500" }],
        "headline-md": ["24px", { lineHeight: "32px", fontWeight: "600" }],
        "headline-lg-mobile": ["28px", { lineHeight: "36px", fontWeight: "700" }],
        "body-md": ["16px", { lineHeight: "24px", fontWeight: "400" }]
      }
    }
  }
};
</script>
