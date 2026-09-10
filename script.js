// ============================================================
// The Reading Room — front-end behaviour
// ============================================================

// Live search on the catalog page: filters .book-card elements
// by title/author as the visitor types, no page reload needed.
function initCatalogSearch() {
    const input = document.getElementById('bookSearch');
    const cards = document.querySelectorAll('.book-card');
    if (!input || cards.length === 0) return;

    input.addEventListener('input', () => {
        const term = input.value.trim().toLowerCase();
        cards.forEach((card) => {
            const haystack = card.dataset.search || '';
            card.style.display = haystack.includes(term) ? '' : 'none';
        });
    });
}

// Confirm before deleting a book from the admin table.
function initDeleteConfirm() {
    document.querySelectorAll('.btn-delete').forEach((link) => {
        link.addEventListener('click', (event) => {
            const title = link.dataset.title || 'this book';
            if (!confirm(`Delete "${title}" from the catalog? This cannot be undone.`)) {
                event.preventDefault();
            }
        });
    });
}

// Basic client-side validation for the add/edit book form.
function initBookFormValidation() {
    const form = document.getElementById('bookForm');
    if (!form) return;

    form.addEventListener('submit', (event) => {
        const title = form.querySelector('[name="title"]');
        const author = form.querySelector('[name="author"]');
        const isbn = form.querySelector('[name="isbn"]');
        const errors = [];

        if (title && title.value.trim().length < 2) {
            errors.push('Title must be at least 2 characters long.');
        }
        if (author && author.value.trim().length < 2) {
            errors.push('Author must be at least 2 characters long.');
        }
        if (isbn && isbn.value.trim().replace(/[- ]/g, '').length < 10) {
            errors.push('ISBN looks too short — check it and try again.');
        }

        if (errors.length > 0) {
            event.preventDefault();
            alert(errors.join('\n'));
        }
    });
}

// Basic client-side validation for the borrow request form.
function initBorrowFormValidation() {
    const form = document.getElementById('borrowForm');
    if (!form) return;

    form.addEventListener('submit', (event) => {
        const phone = form.querySelector('[name="borrower_phone"]');
        if (phone && !/^[0-9+\-\s]{7,20}$/.test(phone.value.trim())) {
            event.preventDefault();
            alert('Please enter a valid phone number (digits only, 7–20 characters).');
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initCatalogSearch();
    initDeleteConfirm();
    initBookFormValidation();
    initBorrowFormValidation();
});
