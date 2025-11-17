<?php
// contoh array gambar (bisa juga ambil dari database)
$images = [
    ["src" => "https://images.unsplash.com/photo-1519681393784-d120267933ba?w=1200&q=80&auto=format&fit=crop", "alt" => "Mountains"],
    ["src" => "https://images.unsplash.com/photo-1504198453319-5ce911bafcde?w=1200&q=80&auto=format&fit=crop", "alt" => "Waves"],
    ["src" => "https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=1200&q=80&auto=format&fit=crop", "alt" => "Forest"],
    ["src" => "https://images.unsplash.com/photo-1526318472351-c75fcf070c49?w=1200&q=80&auto=format&fit=crop", "alt" => "City"],
    ["src" => "https://images.unsplash.com/photo-1482192596544-9eb780fc7f66?w=1200&q=80&auto=format&fit=crop", "alt" => "Desert"],
    ["src" => "https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=1200&q=80&auto=format&fit=crop", "alt" => "Canyon"],
    ["src" => "https://image.idntimes.com/post/20250313/img-20250313-210622-12f67e805c04e2f8a3a83a6025cf0ffc.jpg?tr=w-1200,f-webp,q-75&width=1200&format=webp&quality=75", "alt" => "adit jahat"],
    ["src" => "https://images.unsplash.com/photo-1482192505345-5655af888cc4?w=1200&q=80&auto=format&fit=crop", "alt" => "Bridge"],
    ["src" => "https://images.unsplash.com/photo-1472214103451-9374bd1c798e?w=1200&q=80&auto=format&fit=crop", "alt" => "Night Sky"]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pinterest Style Gallery</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        .btn {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 25px;
            border: none;
            background: linear-gradient(45deg, #ff6a00, #ee0979);
            color: white;
            text-decoration: none;
            transition: 0.3s;
        }
        .gallery {
            column-count: 3; /* jumlah kolom */
            column-gap: 15px;
        }
        .gallery-item {
            break-inside: avoid;
            margin-bottom: 15px;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s ease-in-out;
        }
        .gallery-item:hover {
            transform: scale(1.03);
        }
        .gallery-item img {
            width: 100%;
            display: block;
        }
        .caption {
            padding: 10px;
            font-size: 14px;
            color: #333;
        }
        @media(max-width: 768px) {
            .gallery {
                column-count: 2;
            }
        }
        @media(max-width: 480px) {
            .gallery {
                column-count: 1;
            }
        }
    </style>
</head>
<body>
    <h1>Pinterest Style Gallery</h1>
    <div class="gallery">
        <?php foreach($images as $img): ?>
            <div class="gallery-item">
                <img src="<?= $img['src']; ?>" alt="<?= $img['alt']; ?>">
                <div class="caption"><?= $img['alt']; ?></div>
            </div>
        <?php endforeach; ?>
        <a href="home.php" class="btn">kembali</a>
    </div>
</body>
</html>
