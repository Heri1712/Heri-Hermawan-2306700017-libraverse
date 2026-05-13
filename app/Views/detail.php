<?php
function ambilLinkBacaDetail($book)
{
    if (!isset($book->formats)) {
        return '';
    }

    foreach ($book->formats as $type => $url) {
        if (strpos($type, 'text/html') !== false) {
            return $url;
        }
    }

    foreach ($book->formats as $type => $url) {
        if (strpos($type, 'text/plain') !== false) {
            return $url;
        }
    }

    return '';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Buku - Libraverse</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body{font-family:'Inter',sans-serif;background:#f8fafc;color:#1e293b}
        .navbar{background:#0f172a;padding:16px 0}
        .navbar-brand{color:white!important;font-weight:800;font-size:1.5rem}
        .logo-img{width:38px;height:38px}
        .detail-section{padding:60px 0}

        .btn-back,.btn-read{
            color:white;
            border-radius:50px;
            padding:12px 24px;
            font-weight:700;
            text-decoration:none;
            display:inline-block;
            margin-bottom:25px;
        }

        .btn-back{background:#0f172a}
        .btn-back:hover{background:#2563eb;color:white}
        .btn-read{background:#2563eb;margin-top:15px}
        .btn-read:hover{background:#1d4ed8;color:white}

        .detail-card{border:none;border-radius:28px;overflow:hidden;box-shadow:0 20px 45px rgba(15,23,42,.1)}
        .detail-header{background:linear-gradient(135deg,#0f172a,#1e3a8a);color:white;padding:45px}
        .detail-title{font-size:2.2rem;font-weight:800;line-height:1.3}
        .detail-body{background:white;padding:45px}
        .description{color:#475569;line-height:1.9;text-align:justify}
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

<section class="detail-section">
    <div class="container">

        <?php
    if (!empty($keyword)) {
        $backUrl = base_url('/?search=' . urlencode($keyword));
    } else {
        $backUrl = base_url('/');
    }

    if (!empty($book) && isset($book->id)) {
        if (!empty($keyword)) {
            $readInternalUrl = base_url('read/' . $book->id . '?search=' . urlencode($keyword));
        } else {
            $readInternalUrl = base_url('read/' . $book->id);
        }
    } else {
        $readInternalUrl = '#';
    }

    if (!empty($book)) {
        $readUrl = ambilLinkBacaDetail($book);
    } else {
        $readUrl = '';
    }
?>

        <a href="<?php echo $backUrl; ?>" class="btn-back">
            ← Kembali
        </a>

        <?php if (!empty($error)) : ?>

            <div class="alert alert-danger rounded-4 shadow-sm">
                <?php echo htmlspecialchars($error); ?>
            </div>

        <?php elseif (!empty($book)) : ?>

            <div class="card detail-card">
                <div class="detail-header">
                    <h1 class="detail-title">
                        <?php echo htmlspecialchars($book->title ?? 'Judul tidak tersedia'); ?>
                    </h1>
                </div>

                <div class="detail-body">
                    <h5 class="fw-bold mb-3">Informasi Buku</h5>

                    <p class="description">
                        <b>Penulis:</b>
                        <?php echo htmlspecialchars($book->authors[0]->name ?? 'Penulis tidak diketahui'); ?>
                        <br>

                        <b>Bahasa:</b>
                        <?php echo htmlspecialchars(strtoupper($book->languages[0] ?? 'Tidak tersedia')); ?>
                        <br>

                        <b>Total Download:</b>
                        <?php echo htmlspecialchars($book->download_count ?? '0'); ?>
                    </p>

                    <h5 class="fw-bold mt-4 mb-3">Kategori / Subjek</h5>

                    <p class="description">
                        <?php
                            if (!empty($book->subjects)) {
                                echo htmlspecialchars(implode(', ', array_slice($book->subjects, 0, 10)));
                            } else {
                                echo 'Subjek buku tidak tersedia.';
                            }
                        ?>
                    </p>

                    <?php if ($readUrl != '') : ?>
                        <a href="<?php echo $readInternalUrl; ?>" class="btn-read">
                            Baca Buku Sekarang
                        </a>
                    <?php endif; ?>
                </div>
            </div>

        <?php endif; ?>

    </div>
</section>

</body>
</html>