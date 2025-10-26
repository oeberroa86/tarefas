<?php
use App\Utils\Config;

$currentFilter = $_GET['filter'] ?? 'all';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= Config::baseUrl('tasks.php') ?>"> <?= Config::get('system.site_name') ?></a>
        <div class="navbar-nav ms-auto">
            <span class="navbar-text me-3">Olá, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?></span>
            <a class="btn btn-outline-light btn-sm" href="<?= Config::baseUrl('logout.php') ?>">Encerrar Sessão</a>
        </div>
    </div>
</nav>

<div class="container">
    
    <!-- alert -->
    <div id="alertContainer"></div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Nova Tarefa</h5>
            <form id="createTaskForm">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="taskTitle" placeholder="Título da tarefa" required>
                    </div>
                    <div class="col-md-5">
                        <textarea class="form-control" id="taskDescription" placeholder="Descrição (opcional)" rows="1"></textarea>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-success w-100"><i class="bi bi-plus"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="btn-group" role="group">
                <a href="<?= Config::baseUrl('tasks.php') ?>" 
                class="btn <?= $currentFilter === 'all' ? 'btn-primary' : 'btn-outline-primary' ?> filter-link" 
                data-filter="all">
                    Todas
                </a>
                <a href="<?= Config::baseUrl('tasks.php?filter=pendentes') ?>" 
                class="btn <?= $currentFilter === 'pendentes' ? 'btn-warning' : 'btn-outline-warning' ?> filter-link" 
                data-filter="pendentes">
                    Pendentes
                </a>
                <a href="<?= Config::baseUrl('tasks.php?filter=concluidas') ?>" 
                class="btn <?= $currentFilter === 'concluidas' ? 'btn-success' : 'btn-outline-success' ?> filter-link" 
                data-filter="concluidas">
                    Concluidas
                </a>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <span class="badge bg-secondary">Total: <?= count($tasks) ?></span>
        </div>
    </div>

    <div id="tasksContainer">
        <?php if (empty($tasks)): ?>
            <div class="text-center py-5">
                <div class="text-muted">
                    <h4>Não há tarefas</h4>
                    <p><?= $currentFilter === 'all' ? 'Comece adicionando sua primeira tarefa' : 'Não há tarefas com este filtro' ?></p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($tasks as $task): 
                $isCompleted = $task['status'] === 'concluida';
                $badgeClass = $isCompleted ? 'bg-success' : 'bg-warning';
                $textClass = $isCompleted ? 'text-decoration-line-through text-muted' : '';
            ?>
                <div class="card task-card mb-3  <?= $isCompleted ? 'bg-success-subtle' : '' ?>" data-task-id="<?= $task['id'] ?>">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="card-title <?= $textClass ?>"><?= htmlspecialchars($task['titulo']) ?></h6>
                                <?php if ($task['descricao']): ?>
                                    <p class="card-text text-muted small <?= $textClass ?>"><?= htmlspecialchars($task['descricao']) ?></p>
                                <?php endif; ?>
                                <span class="badge <?= $badgeClass ?>"><?= ucfirst($task['status']) ?></span>
                                <small class="text-muted">Criada: <?= date('d/m/Y H:i', strtotime($task['data_criacao'])) ?></small>
                            </div>
                            <div class="col-md-3 text-end">
                                <button class="btn btn-sm <?= $isCompleted ? 'btn-outline-warning' : 'btn-outline-success' ?> toggle-status" 
                                        data-task-id="<?= $task['id'] ?>"
                                        data-current-status="<?= $task['status'] ?>"
                                        title="<?= $isCompleted ? 'Marcar como Pendente' : 'Marcar como Concluída' ?>">
                                    <i class="bi <?= $isCompleted ? 'bi-x-circle' : 'bi-check-circle' ?>"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-primary edit-task" 
                                        data-task-id="<?= $task['id'] ?>"
                                        data-title="<?= htmlspecialchars($task['titulo']) ?>"
                                        data-description="<?= htmlspecialchars($task['descricao'] ?? '') ?>">
                                    <i class="bi bi-pencil"></i> Editar
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-task" 
                                        data-task-id="<?= $task['id'] ?>">
                                    <i class="bi bi-trash"></i> Excluir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal para Editar Tarea -->
<div class="modal fade" id="editTaskModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Tarefa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editTaskForm">
                    <input type="hidden" id="editTaskId">
                    <div class="mb-3">
                        <label for="editTaskTitle" class="form-label">Título</label>
                        <input type="text" class="form-control" id="editTaskTitle" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTaskDescription" class="form-label">Descrição</label>
                        <textarea class="form-control" id="editTaskDescription" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveEditTask">Salvar Alterações</button>    
            </div>
            <div id="alertContaineredit"></div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout.php'; ?>
