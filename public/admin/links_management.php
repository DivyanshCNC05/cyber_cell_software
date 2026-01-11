<?php
require __DIR__ . '/../../includes/db.php';
require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../templates/header.php';
require __DIR__ . '/access.php';
require_role('ADMIN');

$tab = $_GET['tab'] ?? 'manage';
$message = '';

// Handle form submissions
if ($_POST) {
  if (isset($_POST['delete_link'])) {
    $pdo->prepare("DELETE FROM quick_links WHERE id = ?")->execute([(int)$_POST['id']]);
    $message = "Link deleted successfully.";
  } elseif (isset($_POST['delete_category'])) {
    $pdo->prepare("DELETE FROM link_categories WHERE id = ?")->execute([(int)$_POST['id']]);
    $message = "Category deleted successfully.";
  } elseif (isset($_POST['save_link'])) {
    $id = (int)$_POST['id'];
    $pdo->prepare("UPDATE quick_links SET category_id=?, title=?, url=?, target=?, icon=?, is_active=?, sort_order=? WHERE id=?")
        ->execute([$_POST['category_id'], $_POST['title'], $_POST['url'], $_POST['target'], $_POST['icon'], $_POST['is_active'], $_POST['sort_order'], $id]);
    $message = "Link updated successfully.";
  } elseif (isset($_POST['add_link'])) {
    $pdo->prepare("INSERT INTO quick_links (category_id, title, url, target, icon, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)")
        ->execute([$_POST['category_id'], $_POST['title'], $_POST['url'], $_POST['target'], $_POST['icon'], 0, 1]);
    $message = "New link added successfully.";
  } elseif (isset($_POST['save_category'])) {
    $id = (int)$_POST['id'];
    $pdo->prepare("UPDATE link_categories SET name=?, is_active=?, sort_order=? WHERE id=?")
        ->execute([$_POST['name'], $_POST['is_active'], $_POST['sort_order'], $id]);
    $message = "Category updated successfully.";
  } elseif (isset($_POST['add_category'])) {
    $pdo->prepare("INSERT INTO link_categories (name, sort_order, is_active) VALUES (?, ?, ?)")
        ->execute([$_POST['name'], 0, 1]);
    $message = "New category added successfully.";
  }
}

// Get edit data
$edit_link_id = $_GET['edit'] ?? 0;
$edit_cat_id = $_GET['edit_cat'] ?? 0;
$link_data = [];
$cat_data = [];

if ($edit_link_id) {
  $stmt = $pdo->prepare("SELECT * FROM quick_links WHERE id = ?");
  $stmt->execute([$edit_link_id]);
  $link_data = $stmt->fetch();
}

if ($edit_cat_id) {
  $stmt = $pdo->prepare("SELECT * FROM link_categories WHERE id = ?");
  $stmt->execute([$edit_cat_id]);
  $cat_data = $stmt->fetch();
}
?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-sitemap me-2"></i>Manage Quick Links</h2>
    <a href="<?= BASE_PATH ?>/dashboards/admin.php" class="btn btn-outline-secondary">← Back to Admin</a>
  </div>

  <?php if ($message): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($message) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <!-- Navigation Tabs -->
  <ul class="nav nav-tabs mb-4" id="linksTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <a class="nav-link <?= $tab=='manage' ? 'active' : '' ?>" id="manage-tab" href="?tab=manage" role="tab">📁 Categories & Links</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link <?= $tab=='add_link' ? 'active' : '' ?>" id="add_link-tab" href="?tab=add_link" role="tab">➕ Add Link</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link <?= $tab=='add_category' ? 'active' : '' ?>" id="add_category-tab" href="?tab=add_category" role="tab">📂 Add Category</a>
    </li>
  </ul>

  <?php if ($tab == 'manage'): ?>
    <!-- Categories & Links Overview -->
    <div class="row g-4">
      <!-- Categories List -->
      <div class="col-lg-3">
        <div class="card h-100">
          <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="fas fa-folder-open me-2"></i>Categories</h6>
          </div>
          <div class="card-body p-0">
            <?php
            $cats = $pdo->query("SELECT *, (SELECT COUNT(*) FROM quick_links WHERE category_id=c.id AND is_active=1) as link_count FROM link_categories c ORDER BY sort_order ASC, id ASC")->fetchAll();
            if (empty($cats)):
            ?>
              <div class="text-center py-4 text-muted">No categories</div>
            <?php else: ?>
              <?php foreach ($cats as $cat): ?>
                <div class="p-3 border-bottom hover-parent">
                  <div class="d-flex justify-content-between align-items-start">
                    <div>
                      <h6 class="mb-1"><?= htmlspecialchars($cat['name']) ?></h6>
                      <small class="text-muted">
                        <?= $cat['link_count'] ?> active link<?= $cat['link_count'] != 1 ? 's' : '' ?>
                      </small>
                    </div>
                    <div class="btn-group btn-group-sm opacity-0 hover-child">
                      <a href="?tab=manage&edit_cat=<?= $cat['id'] ?>" class="btn btn-outline-primary">Edit</a>
                      <form method="post" class="d-inline" onsubmit="return confirm('Delete category \"<?= $cat['name'] ?>\"?')">
                        <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                        <button type="submit" name="delete_category" class="btn btn-outline-danger">Del</button>
                      </form>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Links Table -->
      <div class="col-lg-9">
        <div class="card h-100">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="fas fa-link me-2"></i>All Links</h6>
            <span class="badge bg-primary fs-6">
              <?= $pdo->query("SELECT COUNT(*) FROM quick_links WHERE is_active=1")->fetchColumn() ?>
            </span>
          </div>
          <div class="table-responsive card-body p-0">
            <table class="table table-hover mb-0">
              <thead class="table-light">
                <tr>
                  <th width="60">#</th>
                  <th>Category</th>
                  <th>Title</th>
                  <th>URL</th>
                  <th>Status</th>
                  <th width="100">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $stmt = $pdo->query("
                  SELECT l.*, c.name as category_name 
                  FROM quick_links l 
                  JOIN link_categories c ON l.category_id = c.id 
                  ORDER BY c.sort_order, l.sort_order, l.id
                ");
                if (!$stmt->rowCount()):
                ?>
                  <tr><td colspan="6" class="text-center text-muted py-4">No links found</td></tr>
                <?php else: 
                  while ($link = $stmt->fetch()):
                ?>
                <tr>
                  <td><?= $link['id'] ?></td>
                  <td><span class="badge bg-light text-dark"><?= htmlspecialchars($link['category_name']) ?></span></td>
                  <td><?= htmlspecialchars($link['title']) ?></td>
                  <td>
                    <a href="<?= htmlspecialchars($link['url']) ?>" target="_blank" class="text-truncate d-inline-block text-decoration-none" style="max-width: 250px;" title="<?= htmlspecialchars($link['url']) ?>">
                      <?= htmlspecialchars(substr($link['url'], strpos($link['url'], '://') + 3, 40)) ?>...
                    </a>
                  </td>
                  <td>
                    <span class="badge <?= $link['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                      <?= $link['is_active'] ? 'Active' : 'Inactive' ?>
                    </span>
                  </td>
                  <td>
                    <div class="btn-group btn-group-sm">
                      <a href="?tab=add_link&edit=<?= $link['id'] ?>" class="btn btn-outline-primary" title="Edit">
                        Edit
                      </a>
                      <form method="post" class="d-inline" style="display: inline-block;" onsubmit="return confirm('Delete?')">
                        <input type="hidden" name="id" value="<?= $link['id'] ?>">
                        <button type="submit" name="delete_link" class="btn btn-outline-danger" title="Delete">
                          Del
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
                <?php endwhile; endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  <?php elseif ($tab == 'add_link'): ?>
    <!-- Add/Edit Link Form -->
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
          <i class="fas fa-link me-2"></i>
          <?= $edit_link_id ? 'Edit Link' : 'Add New Link' ?>
        </h5>
        <a href="?tab=manage" class="btn btn-sm btn-outline-secondary">← Back</a>
      </div>
      <div class="card-body">
        <form method="post">
          <?php if ($edit_link_id): ?>
            <input type="hidden" name="id" value="<?= $edit_link_id ?>">
          <?php endif; ?>
          
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
              <select name="category_id" class="form-select" required>
                <option value="">Select Category</option>
                <?php
                $cats = $pdo->query("SELECT * FROM link_categories WHERE is_active=1 ORDER BY sort_order, name")->fetchAll();
                foreach ($cats as $cat):
                ?>
                  <option value="<?= $cat['id'] ?>" <?= ($link_data['category_id'] ?? 0) == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Sort Order</label>
              <input type="number" name="sort_order" class="form-control" 
                     value="<?= htmlspecialchars($link_data['sort_order'] ?? 0) ?>" min="0" step="1">
            </div>
            <div class="col-12">
              <label class="form-label fw-bold">Title <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control" 
                     value="<?= htmlspecialchars($link_data['title'] ?? '') ?>" required maxlength="255">
            </div>
            <div class="col-12">
              <label class="form-label fw-bold">URL <span class="text-danger">*</span></label>
              <input type="url" name="url" class="form-control" 
                     value="<?= htmlspecialchars($link_data['url'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Open In</label>
              <select name="target" class="form-select">
                <option value="_blank" <?= ($link_data['target'] ?? '_blank') == '_blank' ? 'selected' : '' ?>>
                  🆕 New Tab
                </option>
                <option value="_self" <?= ($link_data['target'] ?? '') == '_self' ? 'selected' : '' ?>>
                  📱 Same Tab
                </option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Icon (optional)</label>
              <div class="input-group">
                <input type="text" name="icon" class="form-control" 
                       value="<?= htmlspecialchars($link_data['icon'] ?? '') ?>" 
                       placeholder="fa-envelope">
                <span class="input-group-text bg-light">
                  <i class="fas fa-magic"></i>
                </span>
              </div>
              <div class="form-text">FontAwesome 6: fa-envelope, fa-globe, fa-mobile-alt, etc.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Status</label>
              <select name="is_active" class="form-select">
                <option value="1" <?= empty($link_data['is_active']) || $link_data['is_active'] ? 'selected' : '' ?>>✅ Active</option>
                <option value="0" <?= !empty($link_data['is_active']) && !$link_data['is_active'] ? 'selected' : '' ?>>⏸️ Inactive</option>
              </select>
            </div>
          </div>
          
          <hr class="my-4">
          
          <div class="d-flex gap-2">
            <button type="submit" name="<?= $edit_link_id ? 'save_link' : 'add_link' ?>" value="1" class="btn btn-primary btn-lg">
              <i class="fas fa-save me-2"></i>
              <?= $edit_link_id ? 'Update Link' : 'Add Link' ?>
            </button>
            <a href="?tab=manage" class="btn btn-outline-secondary btn-lg">Cancel</a>
          </div>
        </form>
      </div>
    </div>

  <?php elseif ($tab == 'add_category'): ?>
    <!-- ⭐ ADD CATEGORY FORM - NOW COMPLETE ⭐ -->
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
          <i class="fas fa-folder-plus me-2"></i>
          <?= $edit_cat_id ? 'Edit Category' : 'Add New Category' ?>
        </h5>
        <a href="?tab=manage" class="btn btn-sm btn-outline-secondary">← Back</a>
      </div>
      <div class="card-body">
        <form method="post">
          <?php if ($edit_cat_id): ?>
            <input type="hidden" name="id" value="<?= $edit_cat_id ?>">
          <?php endif; ?>
          
          <div class="row g-3">
            <div class="col-md-8">
              <label class="form-label fw-bold">Category Name <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control form-control-lg" 
                     value="<?= htmlspecialchars($cat_data['name'] ?? '') ?>" required 
                     maxlength="100" placeholder="e.g. Email Services">
            </div>
            <div class="col-md-4">
              <label class="form-label">Sort Order</label>
              <input type="number" name="sort_order" class="form-control" 
                     value="<?= htmlspecialchars($cat_data['sort_order'] ?? 0) ?>" min="0" step="1">
            </div>
            <div class="col-12">
              <label class="form-label">Status</label>
              <select name="is_active" class="form-select">
                <option value="1" <?= empty($cat_data['is_active']) || $cat_data['is_active'] ? 'selected' : '' ?>>✅ Active</option>
                <option value="0" <?= !empty($cat_data['is_active']) && !$cat_data['is_active'] ? 'selected' : '' ?>>⏸️ Inactive</option>
              </select>
            </div>
          </div>
          
          <hr class="my-4">
          
          <div class="d-flex gap-2">
            <button type="submit" name="<?= $edit_cat_id ? 'save_category' : 'add_category' ?>" value="1" class="btn btn-success btn-lg">
              <i class="fas fa-folder-plus me-2"></i>
              <?= $edit_cat_id ? 'Update Category' : 'Add Category' ?>
            </button>
            <a href="?tab=manage" class="btn btn-outline-secondary btn-lg">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  <?php endif; ?>
</div>

<style>
.hover-parent:hover .hover-child { opacity: 1 !important; }
.hover-child { opacity: 0; transition: opacity 0.2s; }
.btn-group-sm .btn { padding: 0.25rem 0.5rem; }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<?php require __DIR__ . '/../../templates/footer.php'; ?>
