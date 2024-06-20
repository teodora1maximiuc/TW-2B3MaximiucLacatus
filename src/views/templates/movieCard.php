<div class="movie-card">
    <div class="card-head">
        <img src="<?= htmlspecialchars($movie['image']); ?>" alt="" class="card-img">
        <div class="card-overlay">
            <div class="bookmark">
                <i class="fa-regular fa-bookmark" style="color: #fff;"></i>
            </div>
            <div class="rating">
                <i class="fa-solid fa-star" style="color: #f9cc6c;"></i>
                <span><?= htmlspecialchars($movie['rating']); ?></span>
            </div>
            <div class="addWatchList">
                <i class="fa-solid fa-circle-plus" style="color: #fff;"></i>
            </div>
        </div>
    </div>
    <div class="card-body">
        <h3 class="card-title"><?= htmlspecialchars($movie['title']); ?></h3>
        <div class="card-info">
            <span class="genre"><?= htmlspecialchars($movie['genre']); ?> - </span>
            <span class="year"><?= htmlspecialchars($movie['release_year']); ?></span>
        </div>
    </div>
</div>
