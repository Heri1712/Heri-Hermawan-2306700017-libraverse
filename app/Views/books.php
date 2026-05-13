<?php
function ambilLinkBaca($book)
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
    <title>Libraverse</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body{font-family:'Inter',sans-serif;background:#f8fafc;color:#1e293b}
        .navbar{background:#0f172a;padding:16px 0}
        .navbar-brand{color:white!important;font-weight:800;font-size:1.5rem}
        .logo-img{width:38px;height:38px}

        .hero{
            position:relative;
            height:500px;
            overflow:hidden;
            display:flex;
            align-items:center;
            justify-content:center;
            text-align:center;
            color:white;
        }

        .hero::before{
            content:'';
            position:absolute;
            inset:0;
            background:
                linear-gradient(rgba(15,23,42,.68),rgba(15,23,42,.68)),
                url("https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=1600&q=80");
            background-size:cover;
            background-position:center;
            animation:zoomBackground 18s ease-in-out infinite alternate;
            z-index:-1;
        }

        @keyframes zoomBackground{
            from{transform:scale(1)}
            to{transform:scale(1.12)}
        }

        .hero h1{font-size:3rem;font-weight:800;margin-bottom:30px}
        .search-box{max-width:650px;margin:auto;position:relative}
        .search-input{height:60px;border-radius:50px;border:none;padding:0 140px 0 25px;box-shadow:0 10px 30px rgba(0,0,0,.25)}
        .btn-search{position:absolute;right:8px;top:8px;bottom:8px;border:none;border-radius:50px;padding:0 30px;background:#0f172a;color:white;font-weight:700}

        .book-item{opacity:0;transform:translateY(45px);transition:opacity .8s ease,transform .8s ease}
        .book-item.show{opacity:1;transform:translateY(0)}

        .book-card{border:none;border-radius:24px;overflow:hidden;background:white;box-shadow:0 8px 25px rgba(15,23,42,.08);transition:.3s}
        .book-card:hover{transform:translateY(-10px);box-shadow:0 18px 35px rgba(15,23,42,.15)}
        .cover-wrap{padding:16px}
        .book-img{width:100%;height:330px;object-fit:cover;border-radius:18px;background:#e2e8f0}
        .card-body{padding:0 22px 22px}
        .year-badge{display:inline-block;background:#eff6ff;color:#1d4ed8;padding:6px 13px;border-radius:50px;font-size:.78rem;font-weight:700;margin-bottom:12px}
        .book-title{font-size:1.05rem;font-weight:800;color:#0f172a;line-height:1.4;min-height:50px}
        .author{color:#64748b;font-size:.9rem;margin-top:8px}

        .btn-detail,.btn-read{
            color:white;
            border-radius:14px;
            padding:12px;
            font-weight:700;
            text-decoration:none;
            display:block;
            text-align:center;
        }

        .btn-detail{background:#0f172a}
        .btn-detail:hover{background:#2563eb;color:white}
        .btn-read{background:#2563eb;margin-top:10px}
        .btn-read:hover{background:#1d4ed8;color:white}

        .empty-box{background:white;border-radius:25px;padding:45px;box-shadow:0 10px 30px rgba(15,23,42,.08)}
    </style>
</head>

<body>

<nav class="navbar sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo base_url('/'); ?>">
            <img src="https://cdn-icons-png.flaticon.com/512/2232/2232688.png" class="logo-img me-2" alt="Logo">
            <span>Libraverse</span>
        </a>
    </div>
</nav>

<section class="hero">
    <div class="container">
        <h1>Temukan Buku Favoritmu</h1>

        <div class="search-box">
            <form method="GET" action="<?php echo base_url('/'); ?>">
                <input
                    type="text"
                    name="search"
                    class="form-control search-input"
                    placeholder="Cari judul buku atau nama penulis..."
                    value=""
                >

                <button type="submit" class="btn-search">
                    Cari
                </button>
            </form>
        </div>
    </div>
</section>

<main class="container py-5">

    <?php if (!empty($error)) : ?>
        <div class="alert alert-danger text-center rounded-4 shadow-sm">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($keyword)) : ?>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <h4 class="fw-bold mb-0">
                Hasil Pencarian: <?php echo htmlspecialchars($keyword); ?>
            </h4>

            <a href="<?php echo base_url('/'); ?>" class="btn btn-dark rounded-pill px-4">
                Kembali ke Halaman Beranda
            </a>
        </div>
    <?php else : ?>
        <div class="mb-4">
            <h4 class="fw-bold">Daftar Buku Pilihan</h4>
        </div>
    <?php endif; ?>

    <div class="row g-4">

        <?php if (!empty($books)) : ?>

            <?php foreach ($books as $book) : ?>

                <?php
                    $cover = isset($book->formats->{'image/jpeg'})
                        ? $book->formats->{'image/jpeg'}
                        : 'https://via.placeholder.com/300x450?text=No+Cover';

                    $id = $book->id ?? '';
                    $judul = $book->title ?? 'Judul tidak tersedia';

                    $penulis = isset($book->authors[0]->name)
                        ? $book->authors[0]->name
                        : 'Penulis tidak diketahui';

                    $bahasa = isset($book->languages[0])
                        ? strtoupper($book->languages[0])
                        : 'N/A';

                    if (!empty($keyword)) {
                        $detailUrl = base_url('detail/' . $id . '?search=' . urlencode($keyword));
                        $readInternalUrl = base_url('read/' . $id . '?search=' . urlencode($keyword));
                    } else {
                        $detailUrl = base_url('detail/' . $id);
                        $readInternalUrl = base_url('read/' . $id);
                    }

                    $readUrl = ambilLinkBaca($book);
                ?>

                <div class="col-xl-3 col-lg-4 col-md-6 book-item">
                    <div class="card book-card h-100">

                        <div class="cover-wrap">
                            <img src="<?php echo htmlspecialchars($cover); ?>" class="book-img" alt="Cover Buku">
                        </div>

                        <div class="card-body d-flex flex-column">
                            <span class="year-badge">
                                Bahasa: <?php echo htmlspecialchars($bahasa); ?>
                            </span>

                            <div class="book-title">
                                <?php echo htmlspecialchars($judul); ?>
                            </div>

                            <p class="author">
                                ✍️ <?php echo htmlspecialchars($penulis); ?>
                            </p>

                            <div class="mt-auto">
                                <a href="<?php echo $detailUrl; ?>" class="btn-detail">
                                    Lihat Detail
                                </a>

                                <?php if ($readUrl != '') : ?>
                                    <a href="<?php echo $readInternalUrl; ?>" class="btn-read">
                                        Baca Buku
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>

            <?php endforeach; ?>

        <?php else : ?>

            <div class="col-12">
                <div class="empty-box text-center">
                    <h3 class="fw-bold">Buku Tidak Ditemukan</h3>
                    <p class="text-muted">Coba gunakan kata kunci lain.</p>
                </div>
            </div>

        <?php endif; ?>

    </div>

</main>

<script>
    const bookItems = document.querySelectorAll('.book-item');

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                const items = Array.from(bookItems);
                const index = items.indexOf(entry.target);

                setTimeout(function() {
                    entry.target.classList.add('show');
                }, index * 90);

                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.15,
        rootMargin: '0px 0px -50px 0px'
    });

    bookItems.forEach(function(item) {
        observer.observe(item);
    });
</script>

</body>
</html>