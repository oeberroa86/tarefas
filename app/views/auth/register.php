<?php
use App\Utils\Config;

$formData = $formData ?? [];
$errors = $errors ?? [];
$pageTitle = $pageTitle ?? "Criar Conta";

?>
<div class="row justify-content-center mt-5">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow">
            <div class="card-body p-4">
                <h2 class="text-center mb-4"><?php echo $pageTitle; ?></h2>

                <!-- error general -->
                <?php if (isset($errors['general'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($errors['general']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if (!empty($errors) && !isset($errors['name']) && !isset($errors['email']) && !isset($errors['password']) && !isset($errors['confirm_password']) && !isset($errors['general'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars(reset($errors)) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="<?= Config::baseUrl('register.php') ?>">
                    <!-- nombre -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                               id="name" name="name" 
                               value="<?= htmlspecialchars($formData['name'] ?? '') ?>" required>
                        <?php if (isset($errors['name'])): ?>
                        <div class="invalid-feedback">
                            <?= htmlspecialchars($errors['name']) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                               id="email" name="email" 
                               value="<?= htmlspecialchars($formData['email'] ?? '') ?>" required>
                        <?php if (isset($errors['email'])): ?>
                        <div class="invalid-feedback">
                            <?= htmlspecialchars($errors['email']) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- contrasenna -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                               id="password" name="password" required>
                        <?php if (isset($errors['password'])): ?>
                        <div class="invalid-feedback">
                            <?= htmlspecialchars($errors['password']) ?>
                        </div>
                        <?php endif; ?>
                        <div class="form-text">Mínimo 6 caracteres</div>
                    </div>
                    
                    <!-- confirmar contrasenna -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                        <input type="password" class="form-control <?= isset($errors['password_confirmation']) ? 'is-invalid' : '' ?>" 
                               id="password_confirmation" name="password_confirmation" required>
                        <?php if (isset($errors['password_confirmation'])): ?>
                        <div class="invalid-feedback">
                            <?= htmlspecialchars($errors['password_confirmation']) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100 mb-3">Registrar-se</button>
                    
                    <div class="text-center">
                        <a href="<?= Config::baseUrl('login.php') ?>" class="text-decoration-none">
                            Já tem uma conta? Faça login aqui
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout.php'; ?>