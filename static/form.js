/**
 * Form.js - Unified form handling with AJAX, modals, and RESTful operations.
 * Optimized for performance and mobile devices.
 */

// Constants
const HIGHLIGHT_DURATION = 2000;
const ANIMATION_DELAY = 300;
const SUCCESS_FEEDBACK_DELAY = 600;
const FETCH_TIMEOUT = 30000;

// Toast container (initialized once)
let toastContainer = null;

/**
 * Shows a toast notification at the top center of the screen.
 * Prevents duplicate toasts with the same message.
 * @param {string} message - Text to display
 * @param {string} type - 'success' or 'error'
 * @param {number} duration - Auto-hide delay in ms (default 3000)
 */
function showToast(message, type = 'success', duration = 3000) {
    if (!toastContainer) {
        toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container';
            document.body.appendChild(toastContainer);
        }
    }

    // Prevent duplicate toasts with the same message
    const existing = toastContainer.querySelector(`.toast-message[data-message="${CSS.escape(message)}"]`);
    if (existing) return;

    const toast = document.createElement('div');
    toast.className = `toast-message toast-${type}`;
    toast.textContent = message;
    toast.setAttribute('data-message', message);
    toastContainer.appendChild(toast);

    requestAnimationFrame(() => toast.classList.add('show'));

    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), ANIMATION_DELAY);
    }, duration);
}

/**
 * Creates a fetch request with timeout support via AbortController.
 * @param {string} url
 * @param {object} options
 * @param {number} timeout
 * @returns {Promise<Response>}
 */
function fetchWithTimeout(url, options = {}, timeout = FETCH_TIMEOUT) {
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), timeout);
    
    return fetch(url, { ...options, signal: controller.signal })
        .finally(() => clearTimeout(timeoutId));
}

/**
 * Centralized error handler for forms.
 * Displays validation errors on fields and shows alert for general errors.
 */
function handleFormError(form, message, errors = {}) {
    // Clear previous errors
    const invalidElements = form.querySelectorAll('.is-invalid');
    const feedbackElements = form.querySelectorAll('.invalid-feedback');
    invalidElements.forEach(el => el.classList.remove('is-invalid'));
    feedbackElements.forEach(el => el.remove());

    // Mark invalid fields and add error messages
    const errorKeys = Object.keys(errors);
    for (let i = 0; i < errorKeys.length; i++) {
        const key = errorKeys[i];
        const input = form.querySelector(`[name="${key}"]`);
        if (input) {
            input.classList.add('is-invalid');
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.textContent = Array.isArray(errors[key]) ? errors[key][0] : errors[key];
            input.parentNode.appendChild(feedback);
        }
    }

    // Show general error message
    if (message) {
        showToast(message, 'error');
    }
}

/**
 * Helper to reset submit button state.
 */
function resetSubmitButton(btn, originalHtml, enabled = true) {
    if (!btn) return;
    btn.disabled = !enabled;
    btn.innerHTML = originalHtml;
    btn.classList.replace('btn-success', 'btn-primary');
}

/**
 * Helper to show loading state on submit button.
 */
function setButtonLoading(btn) {
    if (!btn) return;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Processing...';
}

/**
 * Helper to show success state on submit button.
 */
function setButtonSuccess(btn) {
    if (!btn) return;
    btn.classList.replace('btn-primary', 'btn-success');
    btn.innerHTML = '<i class="fa fa-check"></i> Success';
}

/**
 * ModalFormHandler: Handles modal-based forms for Add/Edit operations with RESTful support.
 * 
 * Logic:
 * 1. Listen for clicks on [data-bs-target] buttons to detect Add or Edit intent.
 * 2. If Add: Reset form, set action to data-add-action, method to POST.
 * 3. If Edit: Fill form with row data (from data-key attributes), set action to base/id, method to PUT.
 * 4. Use event delegation for better performance and reliability on dynamic content.
 * 
 * Mobile friendly: Fast response, visual feedback, large touch targets (handled by Bootstrap/CSS).
 */
class ModalFormHandler {
    constructor(modalId) {
        this.modal = document.getElementById(modalId);
        if (!this.modal) return;

        this.form = this.modal.querySelector('form');
        this.modalTitle = this.modal.querySelector('.modal-title');
        this.submitBtn = this.modal.querySelector('button[type="submit"]');
        this.modalInstance = null;
        this.abortController = null;
    }

    getModalInstance() {
        if (!this.modalInstance && typeof bootstrap !== 'undefined') {
            this.modalInstance = bootstrap.Modal.getOrCreateInstance(this.modal);
        }
        return this.modalInstance;
    }

    resetForm() {
        if (!this.form) return;
        
        // Abort any pending remote request
        this.abortPendingRequest();
        
        this.form.reset();
        
        // Remove validation classes
        const invalidElements = this.form.querySelectorAll('.is-invalid, .is-valid');
        invalidElements.forEach(el => el.classList.remove('is-invalid', 'is-valid'));

        // Restore original action/method
        const addAction = this.form.getAttribute('data-add-action');
        if (addAction) this.form.action = addAction;
        
        this.setMethod('POST');

        // Clear hidden ID field if exists
        const idInput = this.form.querySelector('[name="id"]');
        if (idInput) idInput.value = '';

        // Update UI labels
        this.updateLabels('add');
    }

    fillForm(row) {
        if (!this.form || !row) return;

        const id = row.getAttribute('data-id');
        if (!id) return;

        // RESTful action: base/id
        const addAction = this.form.getAttribute('data-add-action') || this.form.action;
        const baseAction = addAction.replace(/\/$/, '');
        this.form.action = `${baseAction}/${id}`;
        
        this.setMethod('PUT');

        const idInput = this.form.querySelector('[name="id"]');
        if (idInput) idInput.value = id;

        // Remote vs Local filling
        const isRemote = this.form.getAttribute('data-edit-remote') !== 'false';
        
        if (isRemote) {
            this.fillFormRemote(this.form.action);
        } else {
            this.fillFormLocal(row);
        }

        this.updateLabels('edit');
    }

    fillFormLocal(row) {
        // Fill fields from row's [data-key] elements
        const cells = row.querySelectorAll('[data-key]');
        cells.forEach(td => {
            const key = td.getAttribute('data-key');
            const value = td.textContent.trim();
            this.setFieldValue(key, value);
        });
    }

    abortPendingRequest() {
        if (this.abortController) {
            this.abortController.abort();
            this.abortController = null;
        }
    }

    async fillFormRemote(url) {
        // Abort any previous request
        this.abortPendingRequest();
        this.abortController = new AbortController();

        // Cache inputs query
        const inputs = this.form.querySelectorAll('input, select, textarea, button[type="submit"]');
        
        try {
            // Visual feedback: loading state for form fields
            inputs.forEach(i => i.disabled = true);

            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                signal: this.abortController.signal
            });
            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Failed to fetch record data');
            }

            // Fill form with data from backend
            const dataKeys = Object.keys(result.data);
            for (let i = 0; i < dataKeys.length; i++) {
                this.setFieldValue(dataKeys[i], result.data[dataKeys[i]]);
            }

        } catch (err) {
            if (err.name === 'AbortError') return; // Silently ignore aborted requests
            console.error('Remote fill error:', err);
            showToast('Error loading data: ' + err.message, 'error');
        } finally {
            inputs.forEach(i => i.disabled = false);
            this.abortController = null;
        }
    }

    setMethod(method) {
        let methodInput = this.form.querySelector('input[name="_method"]');
        if (method === 'POST') {
            if (methodInput) methodInput.remove();
            return;
        }

        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            this.form.appendChild(methodInput);
        }
        methodInput.value = method;
    }

    setFieldValue(name, value) {
        const inputs = this.form.querySelectorAll(`[name="${name}"]`);
        inputs.forEach(input => {
            if (input.type === 'checkbox') {
                const val = (typeof value === 'string') ? value.toLowerCase() : value;
                input.checked = (val === '+' || val === '1' || val === 'true' || val === 'on' || val === 't' || val === 'y' || val === true);
            } else if (input.type === 'radio') {
                input.checked = (input.value === value);
            } else {
                input.value = (value !== null && value !== undefined) ? value : '';
            }
            // Trigger change event for any listeners
            input.dispatchEvent(new Event('change', { bubbles: true }));
        });
    }

    updateLabels(type) {
        const attrTitle = `data-${type}-title`;
        const attrBtn = `data-${type}-text`;
        
        if (this.modalTitle) {
            const text = this.form.getAttribute(attrTitle);
            if (text) this.modalTitle.textContent = text;
        }
        
        if (this.submitBtn) {
            const text = this.form.getAttribute(attrBtn);
            if (text) this.submitBtn.textContent = text;
        }
    }
}

/**
 * createRowFromNearbyTemplate: Optimized row creation from <template>.
 */
function createRowFromNearbyTemplate(form, templateSelector = null, data) {
    const container = form.closest('.table-widget, .table-container') || document.body;
    const template = templateSelector 
        ? container.querySelector(templateSelector)
        : container.querySelector('template') || document.querySelector('template');

    if (!template) {
        console.error('Template not found for form', form);
        return document.createElement('tr');
    }

    const clone = template.content.cloneNode(true);
    const tr = clone.querySelector('tr') || clone.firstElementChild;
    
    tr.setAttribute('data-id', data.id || '');

    // Fill data-key placeholders
    const dataKeys = Object.keys(data);
    for (let i = 0; i < dataKeys.length; i++) {
        const key = dataKeys[i];
        const cells = tr.querySelectorAll(`[data-key="${key}"]`);
        cells.forEach(cell => {
            let value = data[key];
            if (typeof value === 'boolean') {
                value = value ? '+' : '-';
            }
            cell.textContent = (value !== null && value !== undefined) ? value : '';
        });
    }

    return tr;
}

/**
 * FormManager: Unified event delegation for all form operations.
 * Combines submit handling, delete operations, and modal interactions.
 */
class FormManager {
    constructor() {
        this.modalHandlers = new Map();
        this.pendingDeletes = new WeakSet();
        this.init();
    }

    init() {
        // Single delegated listener for all click events
        document.addEventListener('click', (e) => this.handleClick(e));
        
        // Single delegated listener for all form submissions
        document.addEventListener('submit', (e) => this.handleSubmit(e));
        
        // Initialize modal handlers
        document.querySelectorAll('.modal').forEach(modal => {
            if (modal.querySelector('form.ajax-form-add')) {
                const handler = new ModalFormHandler(modal.id);
                this.modalHandlers.set(modal.id, handler);
            }
        });
    }

    handleClick(e) {
        // Handle modal triggers (Add/Edit buttons)
        const modalTrigger = e.target.closest('[data-bs-toggle="modal"]');
        if (modalTrigger) {
            const targetId = modalTrigger.getAttribute('data-bs-target')?.replace('#', '');
            const handler = this.modalHandlers.get(targetId);
            if (handler) {
                if (modalTrigger.classList.contains('btn-edit')) {
                    const row = modalTrigger.closest('tr');
                    if (row) handler.fillForm(row);
                } else {
                    handler.resetForm();
                }
            }
            return;
        }

        // Handle delete buttons
        const deleteBtn = e.target.closest('.btn-delete');
        if (deleteBtn) {
            e.preventDefault();
            this.handleDelete(deleteBtn);
        }
    }

    async handleSubmit(e) {
        const form = e.target.closest('.ajax-form-add');
        if (!form) return;

        e.preventDefault();

        // Double-submit protection
        if (form.dataset.submitting === 'true') return;
        form.dataset.submitting = 'true';
        
        const submitBtn = form.querySelector('[type="submit"]');
        const originalBtnHtml = submitBtn ? submitBtn.innerHTML : '';
        const formData = new FormData(form);

        // Determine if edit mode early (single check)
        const methodInput = form.querySelector('input[name="_method"]');
        const method = methodInput ? methodInput.value : 'POST';
        const isEdit = method === 'PUT';

        // UI Feedback: Loading state
        setButtonLoading(submitBtn);

        // Clear previous errors
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        try {
            const response = await fetchWithTimeout(form.action, {
                method: method,
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                handleFormError(form, result.message || 'Server error', result.errors || {});
                resetSubmitButton(submitBtn, originalBtnHtml);
                form.dataset.submitting = 'false';
                return;
            }

            // Show success toast immediately (before modal closes)
            const successMsg = result.message || (isEdit ? 'Saved' : 'Added');
            showToast(successMsg, 'success');

            // Success feedback
            setButtonSuccess(submitBtn);

            // Short delay for visual feedback before closing
            setTimeout(() => {
                const modalEl = form.closest('.modal');
                if (modalEl && typeof bootstrap !== 'undefined') {
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    modal?.hide();
                }

                // UI Update
                const tr = createRowFromNearbyTemplate(form, null, result.data);
                const table = form.closest('.table-widget, .table-container')?.querySelector('table') || document.querySelector('table');
                if (table) {
                    const tbody = table.querySelector('tbody') || table;
                    
                    if (isEdit) {
                        const existingRow = tbody.querySelector(`tr[data-id="${result.data.id}"]`);
                        if (existingRow) {
                            existingRow.replaceWith(tr);
                        } else {
                            tbody.prepend(tr);
                        }
                    } else {
                        // Check for empty state
                        const emptyRow = tbody.querySelector('.table-empty-row');
                        if (emptyRow) {
                            emptyRow.remove();
                        }
                        tbody.prepend(tr);
                    }

                    // Highlight the row
                    tr.classList.add('row-highlight');
                    setTimeout(() => tr.classList.remove('row-highlight'), HIGHLIGHT_DURATION);

                    // Trigger custom event for other components (like sorting)
                    table.dispatchEvent(new CustomEvent('table:updated', { 
                        detail: { row: tr, data: result.data, isEdit } 
                    }));
                }

                resetSubmitButton(submitBtn, originalBtnHtml);
                form.reset();
                form.dataset.submitting = 'false';
            }, SUCCESS_FEEDBACK_DELAY);

        } catch (err) {
            if (err.name === 'AbortError') {
                handleFormError(form, 'Request timed out. Please try again.');
            } else {
                console.error('Form submission error:', err);
                handleFormError(form, err.message);
            }
            resetSubmitButton(submitBtn, originalBtnHtml);
            form.dataset.submitting = 'false';
        }
    }

    async handleDelete(btn) {
        const row = btn.closest('tr');
        const id = row?.getAttribute('data-id');
        const baseUrl = btn.getAttribute('data-delete-url');
        
        if (!id || !baseUrl) return;

        const confirmMsg = btn.getAttribute('data-confirm') || 'Delete this record?';
        if (!confirm(confirmMsg)) return;

        // Prevent double-click using WeakSet (no memory leaks)
        if (this.pendingDeletes.has(btn)) return;
        this.pendingDeletes.add(btn);
        btn.disabled = true;

        try {
            const response = await fetchWithTimeout(`${baseUrl}/${id}`, {
                method: 'DELETE',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Delete failed');
            }

            // Remove row with animation
            row.style.transition = `opacity ${ANIMATION_DELAY}ms`;
            row.style.opacity = '0';
            setTimeout(() => row.remove(), ANIMATION_DELAY);

            // Show success toast
            showToast(result.message || 'Deleted', 'success');

        } catch (err) {
            if (err.name === 'AbortError') {
                showToast('Request timed out. Please try again.', 'error');
            } else {
                console.error('Delete error:', err);
                showToast('Error: ' + err.message, 'error');
            }
        } finally {
            this.pendingDeletes.delete(btn);
            btn.disabled = false;
        }
    }
}

// Global initialization
document.addEventListener('DOMContentLoaded', () => {
    new FormManager();
});
