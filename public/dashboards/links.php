<?php
require __DIR__ . '/../../includes/db.php';
require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../templates/header.php';
require_role('CYBER_USER');
?>

<div class="container py-4">
  <div class="d-flex justify-content-center align-items-center mb-5">
    <h1 class="display-5 fw-bold text-center">
      <i class="fas fa-link me-3 text-primary"></i>
      Quick Links
    </h1>
  </div>

  <?php
  $stmt = $pdo->query("
    SELECT c.*, COUNT(l.id) as link_count 
    FROM link_categories c 
    LEFT JOIN quick_links l ON c.id = l.category_id AND l.is_active=1 
    WHERE c.is_active=1 
    GROUP BY c.id 
    HAVING link_count > 0 
    ORDER BY c.sort_order ASC, c.id ASC
  ");
  $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
  ?>

  <?php foreach ($categories as $cat): ?>
    <div class="card mb-4 border-0 shadow-sm">
      <div class="card-header border-0 py-3" style="background: #0a1431">
        <h5 class="mb-0 fw-bold text-white">
          <i class="fas fa-folder me-2 text-primary"></i>
          <?= htmlspecialchars($cat['name']) ?>
        </h5>
      </div>
      <div class="card-body p-0">
        <?php
        $stmt = $pdo->prepare("SELECT * FROM quick_links WHERE category_id = ? AND is_active=1 ORDER BY sort_order ASC, id ASC");
        $stmt->execute([$cat['id']]);
        $link_number = 1;
        while ($link = $stmt->fetch(PDO::FETCH_ASSOC)):
        ?>
          <a href="<?= htmlspecialchars($link['url']) ?>" 
             target="<?= $link['target'] ?>" 
             class="link-item border-0 px-4 py-3 text-decoration-none d-block">
            
            <div class="d-flex align-items-center">
              <span class="link-number me-3"><?= $link_number++ ?>.</span>
              
              <?php if ($link['icon']): ?>
                <i class="fas <?= htmlspecialchars($link['icon']) ?> text-primary me-2" style="font-size: 1.1rem;"></i>
              <?php endif; ?>
              
              <div class="flex-grow-1">
                <div class="fw-semibold text-dark"><?= htmlspecialchars($link['title']) ?></div>
                <small class="text-muted d-block"><?= htmlspecialchars(parse_url($link['url'], PHP_URL_HOST)) ?></small>
              </div>
            </div>
          </a>
        <?php endwhile; ?>
      </div>
    </div>
  <?php endforeach; ?>

  <?php if (empty($categories)): ?>
    <div class="text-center py-5">
      <i class="fas fa-link-slash fa-3x text-muted mb-3"></i>
      <h5 class="text-muted">No Quick Links Available</h5>
    </div>
  <?php endif; ?>
</div>

<style>
.link-item {
  transition: all 0.2s ease;
  border-radius: 8px !important;
  margin-bottom: 1px;
  display: block;
}
.link-item:hover {
  background-color: #f8f9fa;
  transform: translateX(8px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.link-number {
  font-weight: 700;
  color: #0a1431;
  font-size: 1rem;
  min-width: 25px;
  text-align: center;
}
.card {
  border-radius: 12px;
  overflow: hidden;
}
</style>

<?php require __DIR__ . '/../../templates/footer.php'; ?>
