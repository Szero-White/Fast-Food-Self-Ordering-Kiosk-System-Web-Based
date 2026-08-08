<?php
require_once __DIR__ . '/../../config/banner_repository.php';
$banners = get_active_banners($mysqli);
?>

<style>
  #carouselExampleInterval {
      max-width: 84rem;
      width: 100%;
      margin: 0 auto;
      background-color: black;
  }
  .carousel-item img {
      width: 100%;
      height: 28rem;
      object-fit: cover;
      display: block;
  }
</style>

<?php if (!empty($banners)) { ?>
  <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <?php foreach ($banners as $index => $banner) { ?>
        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>" data-bs-interval="2000">
          <img src="<?php echo htmlspecialchars(banner_image_url($banner), ENT_QUOTES, 'UTF-8'); ?>" class="d-block" alt="<?php echo htmlspecialchars($banner['title'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>
      <?php } ?>
    </div>
    <?php if (count($banners) > 1) { ?>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Trước</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Sau</span>
      </button>
    <?php } ?>
  </div>
<?php } ?>
