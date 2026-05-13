<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reader - Libraverse</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body{font-family:'Inter',sans-serif;background:#f8fafc;color:#1e293b}
        .navbar{background:#0f172a;padding:16px 0}
        .navbar-brand{color:white!important;font-weight:800;font-size:1.5rem}
        .logo-img{width:38px;height:38px}
        .reader-section{padding:40px 0}

        .btn-back{
            background:#0f172a;
            color:white;
            border-radius:50px;
            padding:12px 24px;
            font-weight:700;
            text-decoration:none;
            display:inline-block;
            margin-bottom:25px;
        }

        .btn-back:hover{background:#2563eb;color:white}

        .reader-title{
            font-weight:800;
            color:#0f172a;
            margin-bottom:20px;
        }

        .reader-box{
            background:white;
            border-radius:24px;
            overflow:hidden;
            box-shadow:0 20px 45px rgba(15,23,42,.1);
            border:1px solid #e2e8f0;
        }

        iframe{
            width:100%;
            height:80vh;
            border:none;
            background:white;
        }

        .empty-reader{
            padding:60px;
            text-align:center;
        }
    </style>
</head>

<body>

<nav class="navbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo base_url('/'); ?>">
            <img src="https://cdn-icons-png.flaticon.com/512/2232/2232688.png" class="logo-img me-2" alt="Logo">
            <span>Libraverse</span>
        </a>
    </div>
</nav>

<section class="reader-section">
    <div class="container">

        <?php
            if (!empty($keyword)) {
                $backUrl = base_url('/?search=' . urlencode($keyword));
            } else {
                $backUrl = base_url('/');
            }
        ?>

        <a href="<?php echo $backUrl; ?>" class="btn-back">
            ← Kembali
        </a>

        <h3 class="reader-title">
            <?php
                if (!empty($book->title)) {
                    echo htmlspecialchars($book->title);
                } else {
                    echo 'Baca Buku';
                }
            ?>
        </h3>

        <div class="reader-box">

            <?php if (!empty($error)) : ?>

                <div class="empty-reader">
                    <h4><?php echo htmlspecialchars($error); ?></h4>
                </div>

            <?php elseif (!empty($readUrl)) : ?>

                <iframe src="<?php echo htmlspecialchars($readUrl); ?>"></iframe>

            <?php else : ?>

                <div class="empty-reader">
                    <h4>Buku tidak dapat dibaca langsung.</h4>
                    <p class="text-muted">Format bacaan tidak tersedia dari API.</p>
                </div>

            <?php endif; ?>

        </div>

    </div>
</section>

</body>
</html>