//COLUMN ADD
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('ajax-column-form');
    const formBox = document.getElementById('form-container-box');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(response => {
                if (!response.ok) throw new Error('ERROR');
                return response.text();
            })
            .then(html => {
                formBox.insertAdjacentHTML('beforebegin', html);
                form.reset();
            })
            .catch(error => alert('ERROR'));
    });
});

// MOVE columns
document.getElementById('2todo-columns').addEventListener('click', function (e) {
    if (e.target && e.target.classList.contains('move-btn')) {
        e.preventDefault();

        const url = e.target.getAttribute('data-url');

        fetch(url, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(response => {
                if (!response.ok) throw new Error('Nie można przenieść kolumny');
                window.location.reload();
            })
            .catch(error => alert(error.message));
    }
});

// MODAL OF TASK (add)
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('task-modal');
    const form = document.getElementById('popup-task-form');
    const closeModal = document.getElementById('close-task-modal-btn');
    const modalColumnName = document.getElementById('modal-column-name');

    let currentColumnId = null;

    function hideModal() {
        modal.classList.add('hidden');
        form.reset();
    }

    document.getElementById('2todo-columns').addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('open-task-modal-btn')) {
            e.preventDefault();

            currentColumnId = e.target.getAttribute('data-column-id');
            modalColumnName.textContent = e.target.getAttribute('data-column-name');
            form.action = e.target.getAttribute('data-action-url');

            modal.classList.remove('hidden');
        }
    });

    closeModal?.addEventListener('click', function (e) {
        e.preventDefault();
        hideModal();
    });

    document.body.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('open-task-modal-btn')) {
            e.preventDefault();

            currentColumnId = e.target.getAttribute('data-column-id');
            modalColumnName.textContent = e.target.getAttribute('data-column-name');
            form.action = e.target.getAttribute('data-action-url');

            modal.classList.remove('hidden');
        }
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(response => {
                if (!response.ok) throw new Error('Błąd zapisu zadania');
                return response.text();
            })
            .then(taskHtml => {
                const tasksListContainer = document.getElementById('tasks-list-' + currentColumnId);

                const placeholder = tasksListContainer.querySelector('.no-tasks-placeholder');
                if (placeholder) placeholder.remove();

                tasksListContainer.insertAdjacentHTML('afterbegin', taskHtml);

                const counter = document.getElementById('counter-' + currentColumnId);
                if (counter) {
                    counter.textContent = parseInt(counter.textContent) + 1;
                }

                modal.classList.add('hidden');
                form.reset();
            })
            .catch(error => alert(error.message));
    });
});

//SORTABLE darg
document.addEventListener('DOMContentLoaded', function () {
    const containers = document.querySelectorAll('.task-container');

    containers.forEach(container => {
        new Sortable(container, {
            group: '2todo-tasks',
            animation: 150,
            ghostClass: 'bg-indigo-50',


            onEnd: function (evt) {
                const taskId = evt.item.getAttribute('data-id');
                const targetColumnId = evt.to.id.replace('tasks-list-', '');
                const sourceColumnId = evt.from.id.replace('tasks-list-', '');

                if (targetColumnId !== sourceColumnId) {
                    const baseUrlPattern = evt.to.getAttribute('data-move-url');
                    const url = baseUrlPattern.replace('TASK_ID', taskId);

                    fetch(url, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    }).then(response => {
                            if (!response.ok) throw new Error('Błąd przy zapisywaniu zadania.');

                            const sourceCounter = document.getElementById('counter-' + sourceColumnId);
                            const targetCounter = document.getElementById('counter-' + targetColumnId);

                            if (sourceCounter) sourceCounter.textContent = parseInt(sourceCounter.textContent) - 1;
                            if (targetCounter) targetCounter.textContent = parseInt(targetCounter.textContent) + 1;
                        }).catch(error => {
                            alert(error.message);
                            window.location.reload();
                        });
                }
            }
        });
    });
});

//DELETE
document.body.addEventListener('click', function (e) {
    // TASK
    if (e.target && e.target.classList.contains('delete-task-btn')) {
        e.preventDefault();
        if (!confirm('Czy na pewno chcesz usunąć zadanie?')) return;

        const btn = e.target;
        const url = btn.getAttribute('data-url');
        const columnId = btn.getAttribute('data-column-id');
        const taskRow = btn.closest('.task-item');

        fetch(url, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    taskRow.remove();
                    const counter = document.getElementById('counter-' + columnId);
                    if (counter) counter.textContent = parseInt(counter.textContent) - 1;
                }
            })
            .catch(() => alert('Wystąpił błąd podczas usuwania zadania.'));
    }
    //COLUMN
    if (e.target && e.target.classList.contains('delete-column-btn')) {
        e.preventDefault();
        if (!confirm('Czy na pewno chcesz usunąć kolumnę?')) return;

        const btn = e.target;
        const url = btn.getAttribute('data-url');
        const columnBox = btn.closest('.column-item') || document.getElementById(btn.closest('[id^="column_"]').id);

        fetch(url, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    columnBox.remove();
                }})
            .catch(() => alert('Wystąpił błąd podczas usuwania kolumny.'));
    }
});
