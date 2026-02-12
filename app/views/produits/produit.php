<?php include 'includes/header.php'; ?>
<style>
    .product-image-large {
        max-height: 400px;
        object-fit: cover;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .thumbnail-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 5px;
        cursor: pointer;
        margin: 5px;
        transition: border-color 0.2s;
    }
    .thumbnail-image:hover {
        border-color: #007bff;
    }
    .thumbnail-image.active {
        box-shadow: 0 0 5px rgba(0,123,255,0.5);
    }
    .form-section {
        background-color: #f8f9fa;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .btn-custom {
        border-radius: 8px;
        font-weight: 500;
    }
    .card {
        border: none;
    }
</style>

<div class="container my-5">
    <div class="row">
        <!-- Image Section -->
        <div class="col-lg-7 col-md-6">
            <div class="card mb-4">
                <div class="card-body text-center m-5">
                    <img id="main-image" src="/assets/img/<?= $objet['photos'][0]['nom'] ?? 'default.png' ?>" alt="<?= $objet['titre'] ?>" class="img-fluid product-image-large mb-3">
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3"><i class="bi bi-images"></i> Autres images</h6>
                    <div class="d-flex flex-wrap justify-content-center">
                        <?php foreach ($objet['photos'] as $index => $photo): ?>
                            <div class="m-1">
                                <img src="/assets/img/<?= $photo['nom'] ?>" alt="Thumbnail <?= $index + 1 ?>" class="thumbnail-image <?= $index === 0 ? 'active' : '' ?>" onclick="changeImage('/assets/img/<?= $photo['nom'] ?>', this, '<?= $photo['nom'] ?>')">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modification Form -->
        <div class="col-lg-5 col-md-6">
            <div class="form-section">
                <h3 class="mb-4 text-primary"><i class="bi bi-pencil-square"></i> Modifier l'objet</h3>
                <form action="/produit/edit/<?= $objet['id_objet'] ?>" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="titre" class="form-label fw-bold">Titre</label>
                            <input type="text" class="form-control" id="titre" name="titre" value="<?= $objet['titre'] ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="prix" class="form-label fw-bold">Prix ($)</label>
                            <input type="number" class="form-control" id="prix" name="prix" value="<?= $objet['prix'] ?>" step="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required><?= $objet['description'] ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="id_categorie" class="form-label fw-bold">Catégorie</label>
                        <select class="form-select" id="id_categorie" name="id_categorie" required>
                            <option value="1" <?= $objet['id_categorie'] == 1 ? 'selected' : '' ?>>Électronique</option>
                            <option value="2" <?= $objet['id_categorie'] == 2 ? 'selected' : '' ?>>Meubles</option>
                            <option value="3" <?= $objet['id_categorie'] == 3 ? 'selected' : '' ?>>Vêtements</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="photos" class="form-label fw-bold">Ajouter/Remplacer des photos</label>
                        <input type="file" class="form-control" id="photos" name="photos[]" multiple accept="image/*">
                        <div class="form-text">Sélectionnez plusieurs images. Les nouvelles remplaceront les anciennes si nécessaire.</div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-custom btn-lg"><i class="bi bi-check-circle-fill"></i> Mettre à jour</button>
                        <a href="/produit/delete/<?= $objet['id_objet'] ?>" class="btn btn-danger btn-custom btn-lg"><i class="bi bi-trash-fill"></i> Supprimer</a>
                        <a href="/profile" class="btn btn-outline-secondary btn-custom"><i class="bi bi-arrow-left"></i> Retour au profil</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function changeImage(src, element, name) {
    document.getElementById('main-image').src = src;
    document.getElementById('image-info').textContent = 'Image: ' + name + ' | Taille: N/A | Format: ' + name.split('.').pop().toUpperCase();
    document.querySelectorAll('.thumbnail-image').forEach(img => img.classList.remove('active'));
    element.classList.add('active');
}
</script>

<?php include 'includes/footer.php'; ?>
