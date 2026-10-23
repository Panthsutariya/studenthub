<?php
/**
 * Practical 13: Asynchronous AJAX/Fetch-Based CRUD (No Page Reload)
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'AJAX Event CRUD (No Page Reload) - Practical 13';
$activePage = 'events';
$breadcrumbs = ['Events' => 'events.php', 'AJAX CRUD' => ''];
require_once __DIR__ . '/includes/header.php';
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
    <div>
        <h1 class="card-title" style="font-size:1.75rem; margin:0;">Practical 13: Asynchronous Event CRUD</h1>
        <p class="text-muted">Perform List, Add, Update, and Delete operations using JavaScript Fetch API without page reload.</p>
    </div>
    <div style="display:flex; gap:0.5rem;">
        <?php if (is_admin()): ?>
            <button type="button" class="btn btn-primary btn-sm" id="btnOpenAddModal">+ New Event (AJAX)</button>
        <?php endif; ?>
        <a href="events.php" class="btn btn-outline btn-sm">&larr; Standard Events View</a>
    </div>
</div>

<!-- Alert Feedback Area -->
<div id="ajaxAlert"></div>

<!-- Live Search & Category Filter with Debounce -->
<div class="card p-3 mb-4" style="margin-bottom:1.5rem;">
    <div style="display:flex; gap:1rem; flex-wrap:wrap; align-items:flex-end;">
        <div style="flex:2; min-width:200px;">
            <label class="form-label" for="ajaxSearch">Search Events (Debounced)</label>
            <input type="search" id="ajaxSearch" class="form-control" placeholder="Type title or venue to filter instantly...">
        </div>
        <div style="flex:1; min-width:160px;">
            <label class="form-label" for="ajaxCategory">Category</label>
            <select id="ajaxCategory" class="form-control">
                <option value="">All Categories</option>
                <option value="Technical">Technical</option>
                <option value="Cultural">Cultural</option>
                <option value="Sports">Sports</option>
                <option value="Workshop">Workshop</option>
                <option value="Seminar">Seminar</option>
            </select>
        </div>
        <div>
            <button type="button" class="btn btn-outline" id="btnRefresh">↻ Reload</button>
        </div>
    </div>
</div>

<!-- Events Table Container -->
<div class="card p-0" style="overflow:hidden;">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Date & Time</th>
                    <th>Venue</th>
                    <th>Capacity</th>
                    <th>Status</th>
                    <?php if (is_admin()): ?>
                        <th style="text-align:right;">Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody id="eventsTableBody">
                <tr>
                    <td colspan="8" style="text-align:center; padding:2rem;">
                        <span class="text-muted">Loading events via Fetch API...</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Add / Edit Event Modal -->
<div id="eventModal" class="modal-backdrop" aria-hidden="true">
    <div class="modal-content">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <h3 class="card-title" id="modalTitle" style="margin:0;">Add New Event</h3>
            <button type="button" class="btn-outline btn-sm" data-modal-close>&times;</button>
        </div>

        <form id="eventAjaxForm">
            <input type="hidden" id="eventId" name="id" value="">

            <div class="form-group">
                <label class="form-label" for="modalEventTitle">Title *</label>
                <input type="text" id="modalEventTitle" class="form-control" required>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label" for="modalEventCategory">Category *</label>
                    <select id="modalEventCategory" class="form-control" required>
                        <option value="Technical">Technical</option>
                        <option value="Cultural">Cultural</option>
                        <option value="Sports">Sports</option>
                        <option value="Workshop">Workshop</option>
                        <option value="Seminar">Seminar</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="modalEventDate">Date & Time *</label>
                    <input type="datetime-local" id="modalEventDate" class="form-control" required>
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label" for="modalEventLocation">Location *</label>
                    <input type="text" id="modalEventLocation" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="modalEventCapacity">Capacity</label>
                    <input type="number" id="modalEventCapacity" class="form-control" value="100" min="1" max="2000">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="modalEventDescription">Description</label>
                <textarea id="modalEventDescription" class="form-control" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="modalEventStatus">Status</label>
                <select id="modalEventStatus" class="form-control">
                    <option value="open">Open</option>
                    <option value="closed">Closed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.5rem; margin-top:1.5rem;">
                <button type="button" class="btn btn-outline" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary" id="btnSaveEvent">Save Event</button>
            </div>
        </form>
    </div>
</div>

<script>
const isAdmin = <?= json_encode(is_admin()) ?>;
const API_URL = '/studenthub/api/events.php';

const tableBody = document.getElementById('eventsTableBody');
const searchInput = document.getElementById('ajaxSearch');
const categorySelect = document.getElementById('ajaxCategory');
const btnRefresh = document.getElementById('btnRefresh');
const alertArea = document.getElementById('ajaxAlert');
const modal = document.getElementById('eventModal');
const modalTitle = document.getElementById('modalTitle');
const eventForm = document.getElementById('eventAjaxForm');

function showAlert(message, type = 'success') {
    alertArea.innerHTML = `
        <div class="alert alert-${type} alert-dismissible">
            <span>${message}</span>
            <button type="button" class="btn-sm alert-close" onclick="this.parentElement.remove()">&times;</button>
        </div>
    `;
    setTimeout(() => { if (alertArea.firstElementChild) alertArea.firstElementChild.remove(); }, 5000);
}

// 1. Fetch & Render Events (GET)
async function loadEvents() {
    const q = searchInput.value.trim();
    const cat = categorySelect.value;
    const url = `${API_URL}?search=${encodeURIComponent(q)}&category=${encodeURIComponent(cat)}`;

    tableBody.innerHTML = '<tr><td colspan="8" style="text-align:center; padding:1.5rem;" class="text-muted">Loading events...</td></tr>';

    try {
        const res = await fetch(url);
        const json = await res.json();

        if (json.status !== 'success') {
            tableBody.innerHTML = `<tr><td colspan="8" class="text-danger" style="text-align:center;">${json.message || 'Error'}</td></tr>`;
            return;
        }

        const events = json.data;
        if (events.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="8" style="text-align:center; padding:1.5rem;" class="text-muted">No events found matching your criteria.</td></tr>';
            return;
        }

        tableBody.innerHTML = events.map(e => `
            <tr id="row-event-${e.id}">
                <td><code>#${e.id}</code></td>
                <td><strong>${escapeHtml(e.title)}</strong></td>
                <td><span class="badge badge-primary">${escapeHtml(e.category)}</span></td>
                <td><small>${escapeHtml(e.event_date)}</small></td>
                <td>${escapeHtml(e.location)}</td>
                <td>${e.capacity || 'N/A'}</td>
                <td><span class="badge ${e.status === 'open' ? 'badge-success' : 'badge-danger'}">${e.status}</span></td>
                ${isAdmin ? `
                    <td style="text-align:right; white-space:nowrap;">
                        <button type="button" class="btn btn-outline btn-sm" onclick="editEvent(${e.id})">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteEvent(${e.id}, '${escapeHtml(e.title)}')">Delete</button>
                    </td>
                ` : ''}
            </tr>
        `).join('');
    } catch (err) {
        tableBody.innerHTML = `<tr><td colspan="8" class="text-danger" style="text-align:center;">Network error: ${err.message}</td></tr>`;
    }
}

function escapeHtml(str) {
    return String(str || '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
}

// 2. Debounced Search (Practical 13 Extension)
let debounceTimer;
searchInput.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(loadEvents, 300);
});
categorySelect.addEventListener('change', loadEvents);
btnRefresh.addEventListener('click', loadEvents);

// 3. Add Event Modal Open
const btnOpenAddModal = document.getElementById('btnOpenAddModal');
if (btnOpenAddModal) {
    btnOpenAddModal.addEventListener('click', () => {
        eventForm.reset();
        document.getElementById('eventId').value = '';
        modalTitle.textContent = 'Add New Event (AJAX)';
        modal.classList.add('show');
    });
}

// 4. Edit Event (GET by ID & populate modal)
window.editEvent = async (id) => {
    try {
        const res = await fetch(`${API_URL}?id=${id}`);
        const json = await res.json();
        if (json.status === 'success' && json.data) {
            const ev = json.data;
            document.getElementById('eventId').value = ev.id;
            document.getElementById('modalEventTitle').value = ev.title;
            document.getElementById('modalEventCategory').value = ev.category;
            document.getElementById('modalEventDate').value = ev.event_date.replace(' ', 'T').substring(0, 16);
            document.getElementById('modalEventLocation').value = ev.location;
            document.getElementById('modalEventCapacity').value = ev.capacity;
            document.getElementById('modalEventDescription').value = ev.description || '';
            document.getElementById('modalEventStatus').value = ev.status;

            modalTitle.textContent = `Edit Event #${ev.id}`;
            modal.classList.add('show');
        } else {
            showAlert('Could not load event data.', 'danger');
        }
    } catch (e) {
        showAlert('Network error: ' + e.message, 'danger');
    }
};

// 5. Submit Event Form (POST or PUT via Fetch API)
eventForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = document.getElementById('eventId').value;
    const isUpdate = Boolean(id);

    const payload = {
        id: id ? parseInt(id, 10) : undefined,
        title: document.getElementById('modalEventTitle').value.trim(),
        category: document.getElementById('modalEventCategory').value,
        event_date: document.getElementById('modalEventDate').value.replace('T', ' ') + ':00',
        location: document.getElementById('modalEventLocation').value.trim(),
        capacity: parseInt(document.getElementById('modalEventCapacity').value, 10),
        description: document.getElementById('modalEventDescription').value.trim(),
        status: document.getElementById('modalEventStatus').value
    };

    try {
        const res = await fetch(API_URL, {
            method: isUpdate ? 'PUT' : 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const result = await res.json();

        if (result.status === 'success') {
            showAlert(result.message || 'Operation successful!', 'success');
            modal.classList.remove('show');
            loadEvents();
        } else {
            showAlert(result.message || 'Error occurred.', 'danger');
        }
    } catch (err) {
        showAlert('Network error: ' + err.message, 'danger');
    }
});

// 6. Delete Event (DELETE via Fetch API without page reload)
window.deleteEvent = async (id, title) => {
    if (!confirm(`Are you sure you want to delete "${title}"?`)) return;

    try {
        const res = await fetch(API_URL, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        });
        const result = await res.json();

        if (result.status === 'success') {
            showAlert(result.message, 'success');
            const row = document.getElementById(`row-event-${id}`);
            if (row) {
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '0';
                setTimeout(() => row.remove(), 300);
            }
        } else {
            showAlert(result.message || 'Failed to delete event.', 'danger');
        }
    } catch (err) {
        showAlert('Network error: ' + err.message, 'danger');
    }
};

// Initial load
loadEvents();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
