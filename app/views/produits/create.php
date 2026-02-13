<?php include 'includes/header.php'; ?>
<style>
    .form-section {
        background-color: #f8f9fa;
        padding: 3rem;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        max-width: 800px;
        margin: 0 auto;
    }
    .btn-custom {
        border-radius: 10px;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
    }
</style>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
            <div class="form-section">
                <h3 class="mb-4 text-primary text-center"><i class="bi bi-plus-circle"></i> Créer un nouvel objet</h3>
                <?php if (isset($error) && $error !== null): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <form action="/produit/create" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="titre" class="form-label fw-bold">Titre</label>
                            <input type="text" class="form-control" id="titre" name="titre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="prix" class="form-label fw-bold">Prix ($)</label>
                            <input type="number" class="form-control" id="prix" name="prix" step="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="id_categorie" class="form-label fw-bold">Catégorie</label>
                        <select class="form-select" id="id_categorie" name="id_categorie" required>
                            <option value="1">Électronique</option>
                            <option value="2">Meubles</option>
                            <option value="3">Vêtements</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="photos" class="form-label fw-bold">Photos du produit</label>
                        <input type="file" class="form-control" id="photos" name="photos[]" multiple accept="image/*" required>
                        <div class="form-text">Sélectionnez plusieurs images pour votre produit.</div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <button type="submit" class="btn btn-success btn-custom btn-lg"><i class="bi bi-check-circle-fill"></i> Créer l'objet</button>
                        <a href="/profile" class="btn btn-outline-secondary btn-custom"><i class="bi bi-arrow-left"></i> Retour au profil</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>