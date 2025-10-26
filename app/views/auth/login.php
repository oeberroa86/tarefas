<?php
use App\Utils\Config;

$formData = $data ?? [];
$errors = $errors ?? [];
$pageTitle = $pageTitle ?? "Iniciar Sessão";
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

                <!-- primer error si no hay errores específicos por campo -->
                <?php if (!empty($errors) && !isset($errors['email']) && !isset($errors['password']) && !isset($errors['general'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars(reset($errors)) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="<?= Config::baseUrl('login.php') ?>">
                    <!-- correo -->
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
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mb-3">Entrar</button>
                    
                    <div class="text-center">
                        <a href="<?= Config::baseUrl('register.php') ?>" class="text-decoration-none">
                            Não tem uma conta? Registre-se aqui
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout.php'; ?>