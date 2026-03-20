<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= lamsach($moTaTrang ?? '') ?>">
    <title><?= lamsach($tieuDeTrang ?? 'Trang web') ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header>
    <div class="container">
        <h1><a href="/" style="text-decoration: none; color: #333;">Logo</a></h1>
        <nav class="main-nav">
            <ul>
                <li><a href="/">Trang chủ</a></li>
                <li class="has-submenu">
                    <a href="/san-pham.php">Giải pháp</a>
                    <ul class="submenu">
                        <?php if (!empty($danhMucList)): ?>
                            <?php foreach ($danhMucList as $dm): ?>
                                <li><a href="/san-pham.php?danh_muc_slug=<?= $dm['slug'] ?>"><?= lamsach($dm['ten']) ?></a></li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li><a href="/san-pham.php?danh_muc_slug=switch">Switch</a></li>
                            <li><a href="/san-pham.php?danh_muc_slug=router">Router</a></li>
                            <li><a href="/san-pham.php?danh_muc_slug=firewall">Firewall</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <li><a href="/blog.php">Blog</a></li>
                <li><a href="/lien-he.php">Liên hệ</a></li>
            </ul>
        </nav>
    </div>
</header>
<main>
