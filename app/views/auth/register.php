<?php
  function e($v){ return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }
  function cls_invalid($errors, $field){ return ($errors[$field] ?? '') !== '' ? 'is-invalid' : ''; }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Inscription</title>
  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
  <div class="container min-vh-100 d-flex align-items-center justify-content-center py-4">
    <div class="row w-100 justify-content-center">
      <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-lg border-0 rounded-4">
          <div class="card-header bg-primary text-white text-center py-4 rounded-top-4">
            <i class="bi bi-person-plus-fill fs-1"></i>
            <h4 class="mb-0 mt-2">Inscription</h4>
          </div>
          <div class="card-body p-4">

            <?php if (!empty($success)): ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>Inscription réussie ✅
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            <?php endif; ?>

            <form id="registerForm" method="post" action="/register" novalidate>
              <div id="formStatus" class="alert d-none"></div>

              <div class="row g-3">
                <div class="col-md-6">
                  <label for="nom" class="form-label fw-semibold">
                    <i class="bi bi-person me-1"></i>Nom
                  </label>
                  <input 
                    id="nom" 
                    name="nom" 
                    class="form-control form-control-lg <?= cls_invalid($errors,'nom') ?>" 
                    value="<?= e($values['nom'] ?? '') ?>"
                    placeholder="Entrez votre nom"
                    required
                  >
                  <div class="invalid-feedback"><?= e($errors['nom'] ?? '') ?></div>
                </div>

                <div class="col-md-6">
                  <label for="prenom" class="form-label fw-semibold">
                    <i class="bi bi-person me-1"></i>Prénom
                  </label>
                  <input 
                    id="prenom" 
                    name="prenom" 
                    class="form-control form-control-lg <?= cls_invalid($errors,'prenom') ?>" 
                    value="<?= e($values['prenom'] ?? '') ?>"
                    placeholder="Entrez votre prénom"
                    required
                  >
                  <div class="invalid-feedback"><?= e($errors['prenom'] ?? '') ?></div>
                </div>

                <div class="col-12">
                  <label for="email" class="form-label fw-semibold">
                    <i class="bi bi-envelope me-1"></i>Email
                  </label>
                  <input 
                    id="email" 
                    name="email" 
                    type="email"
                    class="form-control form-control-lg <?= cls_invalid($errors,'email') ?>" 
                    value="<?= e($values['email'] ?? '') ?>"
                    placeholder="Entrez votre email"
                    required
                  >
                  <div class="invalid-feedback"><?= e($errors['email'] ?? '') ?></div>
                </div>

                <div class="col-md-6">
                  <label for="password" class="form-label fw-semibold">
                    <i class="bi bi-lock me-1"></i>Mot de passe
                  </label>
                  <input 
                    id="password" 
                    name="password" 
                    type="password" 
                    class="form-control form-control-lg <?= cls_invalid($errors,'password') ?>"
                    placeholder="Entrez votre mot de passe"
                    required
                  >
                  <div class="invalid-feedback"><?= e($errors['password'] ?? '') ?></div>
                </div>

                <div class="col-md-6">
                  <label for="confirm_password" class="form-label fw-semibold">
                    <i class="bi bi-lock-fill me-1"></i>Confirmation
                  </label>
                  <input 
                    id="confirm_password" 
                    name="confirm_password" 
                    type="password" 
                    class="form-control form-control-lg <?= cls_invalid($errors,'confirm_password') ?>"
                    placeholder="Confirmez le mot de passe"
                    required
                  >
                  <div class="invalid-feedback"><?= e($errors['confirm_password'] ?? '') ?></div>
                </div>

                <div class="col-12">
                  <label for="telephone" class="form-label fw-semibold">
                    <i class="bi bi-telephone me-1"></i>Téléphone
                  </label>
                  <input 
                    id="telephone" 
                    name="telephone" 
                    class="form-control form-control-lg <?= cls_invalid($errors,'telephone') ?>" 
                    value="<?= e($values['telephone'] ?? '') ?>"
                    placeholder="Entrez votre numéro de téléphone"
                    required
                  >
                  <div class="invalid-feedback"><?= e($errors['telephone'] ?? '') ?></div>
                </div>

                <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="agreeTerms" name="agreeTerms" required>
                    <label class="form-check-label" for="agreeTerms">
                      J'accepte les <a href="#" class="text-primary">Conditions d'utilisation</a> et la <a href="#" class="text-primary">Politique de confidentialité</a>
                    </label>
                  </div>
                </div>

                <div class="col-12">
                  <div class="d-grid">
                    <button class="btn btn-primary btn-lg" type="submit">
                      <i class="bi bi-person-plus me-2"></i>S'inscrire
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="card-footer bg-light text-center py-3 rounded-bottom-4">
            <p class="mb-0 text-muted">
              Déjà un compte ? 
              <a href="/auth/login" class="text-primary text-decoration-none fw-semibold">Se connecter</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="/assets/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/js/validation-ajax.js" defer></script>
</body>
</html>
