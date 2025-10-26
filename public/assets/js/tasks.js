$(document).ready(function() {
    //mostrar alerta
    function showAlert(message, type = 'success') {        
        let alertMessage = '';
        
        if (typeof message === 'string') {
            alertMessage = message;
        } else if (typeof message === 'object' && message !== null) {
            const errorKeys = Object.keys(message);
            
            if (errorKeys.length > 0) {
                const firstKey = errorKeys[0];
                const firstValue = message[firstKey];
                
                if (typeof firstValue === 'string') {
                    alertMessage = firstValue;
                } else if (typeof firstValue === 'object') {
                    alertMessage = JSON.stringify(firstValue);
                } else {
                    alertMessage = String(firstValue);
                }
            } else {
                alertMessage = 'Erro de validação desconhecido';
            }
        } else {
            alertMessage = String(message);
        }
        
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${alertMessage}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $('#alertContainer').html(alertHtml);
        $('#alertContaineredit').html(alertHtml);
    }

    //escapar html (seguridad)
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    //renderizar una tarea
    function renderTask(task) {
        const isCompleted = task.status === 'concluida';
        const badgeClass = isCompleted ? 'bg-success' : 'bg-warning';
        const textClass = isCompleted ? 'text-decoration-line-through text-muted' : '';
        const btnClass = isCompleted ? 'btn-outline-warning' : 'btn-outline-success';
        const btnIcon = isCompleted ? 'bi-x-circle' : 'bi-check-circle';
        const btnText = isCompleted ? 'Marcar Pendente' : 'Marcar Concluída';
        
        return `
        <div class="card task-card mb-3 ${isCompleted ? 'bg-success-subtle' : ''}" data-task-id="${task.id}">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-9">
                        <h6 class="card-title ${textClass}">${escapeHtml(task.titulo)}</h6>
                        ${task.descricao ? `<p class="card-text text-muted small ${textClass}">${escapeHtml(task.descricao)}</p>` : ''}
                        <span class="badge ${badgeClass}">${isCompleted ? 'Concluida' : 'Pendente'}</span>
                        <small class="text-muted">Criada: ${new Date(task.data_criacao).toLocaleDateString('pt-BR')}</small>
                    </div>
                    <div class="col-md-3 text-end">
                        <!-- Botón para cambiar estado -->
                        <button class="btn btn-sm ${btnClass} toggle-status mb-2" 
                                data-task-id="${task.id}"
                                data-current-status="${task.status}">
                            <i class="bi ${btnIcon}"></i>
                        </button>
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-outline-primary edit-task" 
                                    data-task-id="${task.id}"
                                    data-title="${escapeHtml(task.titulo)}"
                                    data-description="${escapeHtml(task.descricao || '')}">
                                <i class="bi bi-pencil"></i> Editar
                            </button>
                            <button class="btn btn-sm btn-outline-danger delete-task" 
                                    data-task-id="${task.id}">
                                <i class="bi bi-trash"></i> Excluir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `;
    }

    //agregar tarea al DOM
    function addTaskToDOM(task) {
        const taskHtml = renderTask(task);
        
        //si no hay tareas, reemplazar el mensaje vacío
        if ($('#tasksContainer .card').length === 0) {
            $('#tasksContainer').html(taskHtml);
        } else {
            $('#tasksContainer').prepend(taskHtml);
        }
    }

    //remover tarea del DOM
    function removeTaskFromDOM(taskId) {
        $(`.task-card[data-task-id="${taskId}"]`).remove();
        
        //si no quedan tareas, mostrar mensaje vacío
        if ($('#tasksContainer .card').length === 0) {
            $('#tasksContainer').html(`
                <div class="text-center py-5">
                    <div class="text-muted">
                        <h4>Não há tarefas</h4>
                        <p>Comece adicionando sua primeira tarefa</p>
                    </div>
                </div>
            `);
        }
    }

    //actualizar estado en el DOM
    function updateTaskStatusInDOM(taskId, status) {
        const $taskCard = $(`.task-card[data-task-id="${taskId}"]`);
        const isCompleted = status === 'concluida';
        
        //actualizar clases y apariencia
        $taskCard.toggleClass('bg-success-subtle', isCompleted);
        $taskCard.find('.card-title').toggleClass('text-decoration-line-through text-muted', isCompleted);
        $taskCard.find('.card-text').toggleClass('text-decoration-line-through text-muted', isCompleted);
        
        //actualizar el badge
        const $badge = $taskCard.find('.badge');
        if (isCompleted) {
            $badge.removeClass('bg-warning').addClass('bg-success').text('Concluida');
        } else {
            $badge.removeClass('bg-success').addClass('bg-warning').text('Pendente');
        }
        
        //actualizar el botón toggle-status
        const $toggleBtn = $taskCard.find('.toggle-status');
        if (isCompleted) {
            $toggleBtn
                .removeClass('btn-outline-success')
                .addClass('btn-outline-warning')
                .html('<i class="bi bi-x-circle"></i>')
                .data('current-status', 'concluida');
        } else {
            $toggleBtn
                .removeClass('btn-outline-warning')
                .addClass('btn-outline-success')
                .html('<i class="bi bi-check-circle"></i>')
                .data('current-status', 'pendente');
        }
        
        //aplicar filtros
        const currentFilter = $('.filter-link.btn-primary, .filter-link.btn-outline-warning, .filter-link.btn-outline-success').data('filter');
        if (currentFilter && currentFilter !== 'all') {
            applyFilter(currentFilter);
        }
    }

    //actualizar tarea editada en el DOM
    function updateTaskInDOM(taskId, newTitle, newDescription) {
        const $taskCard = $(`.task-card[data-task-id="${taskId}"]`);
        
        $taskCard.find('.card-title').text(newTitle);        
        if (newDescription) {
            $taskCard.find('.card-text').text(newDescription).show();
        } else {
            $taskCard.find('.card-text').hide();
        }
        
        $taskCard.find('.edit-task').data('title', newTitle).data('description', newDescription || '');
    }

    // ========== EVENTOS  ==========

    //nueva tarea
    $('#createTaskForm').on('submit', function(e) {
        e.preventDefault();
        
        const title = $('#taskTitle').val().trim();
        const description = $('#taskDescription').val().trim();

        if (!title) {
            showAlert('O título é obrigatório', 'danger');
            return;
        }

        $.post('api/tasks.php?action=create', {
            title: title,
            description: description
        }, function(response) {
            if (response.success) {
                $('#taskTitle').val('');
                $('#taskDescription').val('');
                showAlert(response.message);
                
                if (response.task) {
                    addTaskToDOM(response.task);
                    const $allTasks = $('.task-card');                
                    const totalTasks = $allTasks.length;
                    
                    updateTaskCounter(totalTasks);
                } else {
                    setTimeout(() => location.reload(), 2000);
                }
            } else {
                showAlert(response.message, 'danger');
            }
        }).fail(function() {
            showAlert('Erro de conexão', 'danger');
        });
    });

    //cambiar estado
    $(document).on('click', '.toggle-status', function() {
        const taskId = $(this).data('task-id');
        const currentStatus = $(this).data('current-status');
        const newStatus = currentStatus === 'concluida' ? 'pendente' : 'concluida';

        $.post('api/tasks.php?action=update-status', {
            id: taskId,
            status: newStatus
        }, function(response) {
            if (response.success) {
                showAlert(response.message);
                updateTaskStatusInDOM(taskId, newStatus);
            } else {
                showAlert(response.message, 'danger');
            }
        }).fail(function() {
            showAlert('Erro de conexão', 'danger');
        });
    });

    //editar
    $(document).on('click', '.edit-task', function() {
        const taskId = $(this).data('task-id');
        const title = $(this).data('title');
        const description = $(this).data('description');

        $('#editTaskId').val(taskId);
        $('#editTaskTitle').val(title);
        $('#editTaskDescription').val(description);
        $('#editTaskModal').modal('show');
        $('#alertContaineredit').hide();
    });

    //guardar edicion
    $('#saveEditTask').on('click', function() {
        const taskId = $('#editTaskId').val();
        const title = $('#editTaskTitle').val().trim();
        const description = $('#editTaskDescription').val().trim();
        $('#alertContaineredit').show();

        if (!title) {
            showAlert('O título é obrigatório', 'danger');
            return;
        }

        $.post('api/tasks.php?action=update', {
            id: taskId,
            title: title,
            description: description
        }, function(response) {
            if (response.success) {
                $('#editTaskModal').modal('hide');
                showAlert(response.message);
                updateTaskInDOM(taskId, title, description);
            } else {
                showAlert(response.message, 'danger');
            }
        }).fail(function() {
            showAlert('Erro de conexão', 'danger');
        });
    });

    //eliminar
    $(document).on('click', '.delete-task', function() {
        const taskId = $(this).data('task-id');

        if (confirm('Tem certeza de que deseja eliminar esta tarefa?')) {
            $.post('api/tasks.php?action=delete', {
                id: taskId
            }, function(response) {
                if (response.success) {
                    showAlert(response.message);
                    removeTaskFromDOM(taskId);
                    const $allTasks = $('.task-card');                
                    const totalTasks = $allTasks.length;
                    
                    updateTaskCounter(totalTasks);
                } else {
                    showAlert(response.message, 'danger');
                }
            }).fail(function() {
                showAlert('Erro de conexão', 'danger');
            });     
        }
    });

    //clic en enlaces de filtro
    $(document).on('click', '.filter-link', function(e) {
        e.preventDefault();
        
        const filter = $(this).data('filter');
        updateFilterButtons(filter);
        applyFilter(filter);
    });

    //actualizar botones
    function updateFilterButtons(activeFilter) {
        $('.filter-link').each(function() {
            const filter = $(this).data('filter');
            $(this)
                .removeClass('btn-primary btn-warning btn-success btn-outline-primary btn-outline-warning btn-outline-success');

            switch(filter) {
                case 'all':
                    $(this).addClass('btn-outline-primary');
                    break;
                case 'pendentes':
                    $(this).addClass('btn-outline-warning');
                    break;
                case 'concluidas':
                    $(this).addClass('btn-outline-success');
                    break;
            }
        });
        
        //activa al boton seleccionado
        const $activeLink = $(`.filter-link[data-filter="${activeFilter}"]`);
        $activeLink.removeClass('btn-outline-primary btn-outline-warning btn-outline-success');
        
        switch(activeFilter) {
            case 'all':
                $activeLink.addClass('btn-primary');
                break;
            case 'pendentes':
                $activeLink.addClass('btn-warning');
                break;
            case 'concluidas':
                $activeLink.addClass('btn-success');
                break;
        }
    }

    //filtro de tareas
    function applyFilter(filter) {        
        let visibleCount = 0;
        const $allTasks = $('.task-card');
        
        $allTasks.each(function() {
            const $card = $(this);
            const status = $card.find('.badge').text().toLowerCase();
            
            const isCompleted = status.includes('concluida');
            const isPending = status.includes('pendente');
            
            let showCard = true;
            
            switch(filter) {
                case 'pendentes':
                    showCard = isPending;
                    break;
                case 'concluidas':
                    showCard = isCompleted;
                    break;
            }
            
            if (showCard) {
                $card.show();
                visibleCount++;
            } else {
                $card.hide();
            }
        });
        updateFilterButtons(filter)
        updateTaskCounter(visibleCount);
    }

    //actualizar el contador de tareas
    function updateTaskCounter(visibleCount) {
        $('.badge.bg-secondary').text('Total: ' + visibleCount);
    }
});