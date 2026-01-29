document.addEventListener('DOMContentLoaded', () => {
    /**
     * Utility to limit the frequency of function calls
     */
    const debounce = (fn, delay) => {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => fn(...args), delay);
        };
    };

    /**
     * Component for table sorting.
     */
    const initTableSort = () => {
        document.addEventListener('click', e => {
            const trigger = e.target.closest('th[aria-sort] a, th[aria-sort] button');
            if (!trigger) return;

            const th = trigger.closest('th');
            const table = th.closest('table.sortable');
            if (!table) return;

            e.preventDefault();
            sortTable(table, th);
        });
    };

    /**
     * @param {HTMLTableElement} table
     * @param {HTMLTableCellElement} th
     * @param {string|null} forcedOrder
     */
    const sortTable = (table, th, forcedOrder = null) => {
        const tbody = table.tBodies[0];
        if (!tbody || tbody.rows.length <= 1) return;

        const columnIndex = th.cellIndex;
        const currentOrder = th.getAttribute('aria-sort');
        const newOrder = forcedOrder || (currentOrder === 'ascending' ? 'descending' : 'ascending');
        const type = th.dataset.type || 'string';

        // Cache sort values in memory for the duration of the operation
        const rows = Array.from(tbody.rows).map(row => {
            const cell = row.cells[columnIndex];
            if (!cell) return { row, value: '' };

            // Use cached value if it exists
            if (cell.dataset.sortValue !== undefined) {
                let val = cell.dataset.sortValue;
                if (type === 'numeric') val = parseFloat(val);
                return { row, value: val };
            }

            let value = cell.dataset.sort ?? cell.textContent.trim();

            if (type === 'numeric') {
                value = parseFloat(value.replace(/[^\d.-]/g, '')) || 0;
                cell.dataset.sortValue = value;
            } else if (type === 'date') {
                value = new Date(value).getTime() || 0;
                cell.dataset.sortValue = value;
            }

            return { row, value };
        });

        // If the same order comes and the column is already marked as sorted, 
        // and we are not forcing the order from outside - do nothing or just reverse
        if (th.classList.contains('col-sorted') && !forcedOrder) {
            rows.reverse();
        } else {
            const compareFn = getCompareFn(type);
            rows.sort((a, b) => compareFn(a.value, b.value));

            if (newOrder === 'descending') {
                rows.reverse();
            }
        }

        // Redraw rows via DocumentFragment for minimal DOM impact
        const fragment = document.createDocumentFragment();
        rows.forEach(item => fragment.appendChild(item.row));
        tbody.appendChild(fragment);

        updateSortMarkers(table, th, newOrder);
    };

    const collator = new Intl.Collator(undefined, { numeric: true, sensitivity: 'base' });

    /**
     * Returns a comparison function depending on the type
     */
    const getCompareFn = (type) => {
        if (type === 'numeric' || type === 'date') {
            return (a, b) => a - b;
        }
        return (a, b) => collator.compare(a, b);
    };

    /**
     * Updates the visual state of headers
     */
    const updateSortMarkers = (table, activeTh, order) => {
        const headers = table.querySelectorAll('th[aria-sort]');
        const columnIndex = activeTh.cellIndex;

        requestAnimationFrame(() => {
            headers.forEach(th => {
                const isTarget = th === activeTh;
                const newAriaSort = isTarget ? order : 'none';

                if (th.getAttribute('aria-sort') !== newAriaSort) {
                    th.setAttribute('aria-sort', newAriaSort);
                }
                
                if (th.classList.contains('col-sorted') !== isTarget) {
                    th.classList.toggle('col-sorted', isTarget);
                }
            });

            const tbody = table.tBodies[0];
            if (!tbody) return;

            // Optimized column class updates
            const cells = tbody.querySelectorAll('.col-sorted');
            cells.forEach(cell => cell.classList.remove('col-sorted'));
            
            const targetCells = tbody.querySelectorAll(`tr > td:nth-child(${columnIndex + 1})`);
            targetCells.forEach(cell => cell.classList.add('col-sorted'));
        });
    };

    initTableSort();

    /**
     * Scroll indication for mobile tables
     */
    const initTableScrollIndication = () => {
        const containers = document.querySelectorAll('.table-responsive');
        containers.forEach(container => {
            const checkScroll = () => {
                const isScrolling = container.scrollWidth > container.clientWidth;
                const isAtEnd = container.scrollLeft + container.clientWidth >= container.scrollWidth - 5;
                container.classList.toggle('is-scrolling', isScrolling && !isAtEnd);
            };

            const debouncedCheck = debounce(checkScroll, 100);

            container.addEventListener('scroll', debouncedCheck, { passive: true });
            window.addEventListener('resize', debouncedCheck, { passive: true });
            
            checkScroll();
            container.addEventListener('table:resort-complete', checkScroll);
        });
    };

    initTableScrollIndication();

    /**
     * General function for updating sort
     */
    const refreshTableSort = (table) => {
        const activeTh = table.querySelector('th.col-sorted');
        if (activeTh) {
            const order = activeTh.getAttribute('aria-sort');
            sortTable(table, activeTh, order);
            
            const container = table.closest('.table-responsive');
            if (container) {
                // Trigger scroll check
                const event = new CustomEvent('table:resort-complete');
                container.dispatchEvent(event);
            }
        }
    };

    /**
     * Listen for the row added event to resort the table
     */
    document.addEventListener('table:row-added', e => {
        const table = e.detail?.table || e.target.closest('table.sortable');
        if (!table || !table.classList.contains('sortable')) return;

        const emptyRow = table.querySelector('.table-empty-row');
        if (emptyRow) emptyRow.remove();

        refreshTableSort(table);

        const newRow = e.detail?.row;
        if (newRow instanceof HTMLElement) {
            setTimeout(() => {
                newRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                newRow.classList.add('row-highlight');
                setTimeout(() => newRow.classList.remove('row-highlight'), 2000);
            }, 50);
        }
    });

    /**
     * Allows other scripts to force a sort update
     */
    document.addEventListener('table:resort', e => {
        const table = e.detail?.table || e.target.closest('table.sortable');
        if (!table || !table.classList.contains('sortable')) return;
        refreshTableSort(table);
    });

    /**
     * Listen for table:updated event (from form.js) to resort after edit
     */
    document.querySelectorAll('table.sortable').forEach(table => {
        table.addEventListener('table:updated', e => {
            refreshTableSort(table);

            const row = e.detail?.row;
            if (row instanceof HTMLElement) {
                setTimeout(() => {
                    row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 50);
            }
        });
    });
});
