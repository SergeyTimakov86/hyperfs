document.addEventListener('DOMContentLoaded', () => {

    /**
     * Привязывает AJAX форму к таблице
     * @param {string} formSelector - селектор формы
     * @param {function} renderRow - функция(row, form) => <tr> HTMLElement
     */
    function bindAjaxAddForm(formSelector, renderRow) {
        const forms = document.querySelectorAll(formSelector);
        if (!forms) return;

        forms.forEach(form => {
            form.addEventListener('submit', async e => {
                e.preventDefault();
                const data = new FormData(form);

                try {
                    const response = await fetch(form.action, {
                        method: form.method,
                        body: data,
                        headers: {'X-Requested-With': 'XMLHttpRequest'}
                    });

                    if (!response.ok) throw new Error('Server error');

                    const json = await response.json();
                    if (!json.success) throw new Error(json.message || 'Error');

                    // Закрываем модалку
                    const modalEl = form.closest('.modal');
                    if (modalEl) {
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        modal?.hide();
                    }

                    // Рендерим новую строку
                    const tr = renderRow(json.data, form);

                    // Находим ближайший <tbody> для вставки строки
                    const table = form.closest('form, .table-container')?.querySelector('table') || document.querySelector('table');
                    if (!table) throw new Error('Table not found');
                    const tbody = table.querySelector('tbody');
                    if (!tbody) throw new Error('Table tbody not found');

                    tbody.prepend(tr);
                    form.reset();

                } catch (err) {
                    console.error(err);
                    alert(err.message);
                }
            });
        });
    }

    /**
     * Создаёт строку из <template> рядом с формой
     * @param {HTMLFormElement} form - форма, относительно которой искать template
     * @param {string|null} templateSelector - селектор <template>, если null ищем ближайший
     * @param {object} data - объект данных для строки
     * @returns <tr> HTMLElement
     */
    function createRowFromNearbyTemplate(form, templateSelector = null, data) {
        const template = templateSelector
            ? form.closest('form, .table-container')?.querySelector(templateSelector)
            : form.closest('form, .table-container')?.querySelector('template')
            || document.querySelector('template');

        if (!template) throw new Error('Template not found');

        const tr = template.content.cloneNode(true).querySelector('tr');

        Object.keys(data).forEach(key => {
            const td = tr.querySelector(`[data-key="${key}"]`);
            if (!td) return;
            td.textContent = data[key] ?? '';
        });

        return tr;
    }

    bindAjaxAddForm('.ajax-form-add', (row, form) => createRowFromNearbyTemplate(form, null, row));

});