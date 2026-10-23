/**
 * Practical 6: Rendering External JSON Data using Fetch API, Search, Filter, Sort & Pagination
 * Extensions: LocalStorage caching for offline use & Dependent Dropdowns
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
(() => {
  const escapeHTML = (str) => String(str ?? '').replace(/[&<>"']/g, (m) => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
  }[m]));

  document.querySelectorAll('[data-json][data-list-type]').forEach(async (container) => {
    const listType = container.dataset.listType; // 'faq', 'events', or 'students'
    const jsonUrl = container.dataset.json;
    const cacheKey = `cached_json_${listType}`;
    const pageSize = parseInt(container.dataset.pageSize || '6', 10);
    let currentPage = 1;
    let records = [];

    // Render loading indicator
    container.innerHTML = '<div class="alert alert-info" style="text-align:center;">Loading data from JSON...</div>';

    // Fetch with LocalStorage Cache fallback (Practical 6 Advanced Extension)
    try {
      const response = await fetch(jsonUrl);
      if (!response.ok) throw new Error(`HTTP Error ${response.status}`);
      records = await response.json();
      localStorage.setItem(cacheKey, JSON.stringify(records));
    } catch (err) {
      console.warn('Network fetch failed, attempting localStorage cache...', err);
      const cached = localStorage.getItem(cacheKey);
      if (cached) {
        records = JSON.parse(cached);
        const banner = document.createElement('div');
        banner.className = 'alert alert-warning';
        banner.textContent = 'Showing cached offline records.';
        container.parentElement.prepend(banner);
      } else {
        container.innerHTML = `<div class="alert alert-danger">Unable to load data (${escapeHTML(err.message)}). Please verify JSON path.</div>`;
        return;
      }
    }

    if (!Array.isArray(records) || records.length === 0) {
      container.innerHTML = '<div class="alert alert-warning">No records found in JSON source.</div>';
      return;
    }

    // Extract unique categories for filter dropdown
    let categoryKey = 'category';
    if (listType === 'students') categoryKey = 'branch';
    const categories = [...new Set(records.map(r => r[categoryKey]).filter(Boolean))].sort();

    // Render Filter Bar
    container.innerHTML = `
      <div class="card p-3 mb-4" style="border: 1px solid var(--border-color); border-radius: var(--radius); margin-bottom: 1.5rem; background: var(--bg-card);">
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
          <div style="flex: 2; min-width: 200px;">
            <label class="form-label">Search</label>
            <input type="search" class="form-control list-search" placeholder="Type keyword to filter...">
          </div>
          <div style="flex: 1; min-width: 160px;">
            <label class="form-label">${listType === 'students' ? 'Branch' : 'Category'}</label>
            <select class="form-control list-category">
              <option value="">All ${listType === 'students' ? 'Branches' : 'Categories'}</option>
              ${categories.map(c => `<option value="${escapeHTML(c)}">${escapeHTML(c)}</option>`).join('')}
            </select>
          </div>
          <div style="flex: 1; min-width: 140px;">
            <label class="form-label">Sort</label>
            <select class="form-control list-sort">
              <option value="asc">A to Z (Ascending)</option>
              <option value="desc">Z to A (Descending)</option>
            </select>
          </div>
        </div>
      </div>
      <div class="list-results-container"></div>
      <div class="list-pagination" style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem;">
        <button type="button" class="btn btn-outline btn-sm list-prev">&larr; Previous</button>
        <span class="list-page-info text-muted" style="font-size: 0.9rem;"></span>
        <button type="button" class="btn btn-outline btn-sm list-next">Next &rarr;</button>
      </div>
    `;

    const searchInput = container.querySelector('.list-search');
    const categorySelect = container.querySelector('.list-category');
    const sortSelect = container.querySelector('.list-sort');
    const resultsContainer = container.querySelector('.list-results-container');
    const prevBtn = container.querySelector('.list-prev');
    const nextBtn = container.querySelector('.list-next');
    const pageInfo = container.querySelector('.list-page-info');

    const render = () => {
      const q = searchInput.value.trim().toLowerCase();
      const cat = categorySelect.value;
      const sortDir = sortSelect.value;

      // Filter
      let filtered = records.filter(item => {
        const matchesCategory = !cat || item[categoryKey] === cat;
        const matchesSearch = !q || Object.values(item).some(val => 
          String(val).toLowerCase().includes(q)
        );
        return matchesCategory && matchesSearch;
      });

      // Sort
      filtered.sort((a, b) => {
        let keyA = (a.title || a.name || a.question || '').toLowerCase();
        let keyB = (b.title || b.name || b.question || '').toLowerCase();
        return sortDir === 'asc' ? keyA.localeCompare(keyB) : keyB.localeCompare(keyA);
      });

      // Pagination
      const totalPages = Math.ceil(filtered.length / pageSize) || 1;
      if (currentPage > totalPages) currentPage = totalPages;
      if (currentPage < 1) currentPage = 1;

      const start = (currentPage - 1) * pageSize;
      const paginated = filtered.slice(start, start + pageSize);

      pageInfo.textContent = `Showing ${filtered.length > 0 ? start + 1 : 0} - ${Math.min(start + pageSize, filtered.length)} of ${filtered.length} records (Page ${currentPage} of ${totalPages})`;
      prevBtn.disabled = currentPage <= 1;
      nextBtn.disabled = currentPage >= totalPages;

      if (paginated.length === 0) {
        resultsContainer.innerHTML = '<div class="alert alert-warning">No records matched your search filter.</div>';
        return;
      }

      // Render cards according to listType
      if (listType === 'faq') {
        resultsContainer.innerHTML = `
          <div class="accordion">
            ${paginated.map(faq => `
              <div class="card p-3 mb-2" style="margin-bottom: 0.75rem;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                  <h4 style="font-size: 1.05rem; margin-bottom: 0.25rem;">${escapeHTML(faq.question)}</h4>
                  <span class="badge badge-primary">${escapeHTML(faq.category)}</span>
                </div>
                <p class="text-muted" style="margin-top: 0.5rem; font-size: 0.95rem;">${escapeHTML(faq.answer)}</p>
              </div>
            `).join('')}
          </div>
        `;
      } else if (listType === 'events') {
        resultsContainer.innerHTML = `
          <div class="grid-3">
            ${paginated.map(ev => `
              <div class="card">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 0.5rem;">
                  <span class="badge badge-primary">${escapeHTML(ev.category)}</span>
                  <span class="badge badge-success">${escapeHTML(ev.status || 'Open')}</span>
                </div>
                <h3 class="card-title">${escapeHTML(ev.title)}</h3>
                <p class="text-muted" style="font-size: 0.88rem; margin-bottom: 0.5rem;"><strong>📅 Date:</strong> ${escapeHTML(ev.date)}</p>
                <p class="text-muted" style="font-size: 0.88rem; margin-bottom: 0.5rem;"><strong>📍 Location:</strong> ${escapeHTML(ev.location)}</p>
                <p style="font-size: 0.92rem; margin-bottom: 1rem;">${escapeHTML(ev.description || '')}</p>
                <div style="margin-top:auto;">
                  <a href="events.php?id=${ev.id}" class="btn btn-primary btn-sm" style="width: 100%;">View Details</a>
                </div>
              </div>
            `).join('')}
          </div>
        `;
      } else if (listType === 'students') {
        resultsContainer.innerHTML = `
          <div class="table-responsive">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Student ID</th>
                  <th>Full Name</th>
                  <th>Branch</th>
                  <th>Year</th>
                  <th>Email</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                ${paginated.map(s => `
                  <tr>
                    <td><code>${escapeHTML(s.student_number)}</code></td>
                    <td><strong>${escapeHTML(s.name)}</strong></td>
                    <td>${escapeHTML(s.branch)}</td>
                    <td>${escapeHTML(s.year)}</td>
                    <td>${escapeHTML(s.email)}</td>
                    <td><span class="badge badge-success">${escapeHTML(s.status || 'Active')}</span></td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
          </div>
        `;
      }
    };

    // Event listeners
    searchInput.addEventListener('input', () => { currentPage = 1; render(); });
    categorySelect.addEventListener('change', () => { currentPage = 1; render(); });
    sortSelect.addEventListener('change', () => { render(); });
    prevBtn.addEventListener('click', () => { if (currentPage > 1) { currentPage--; render(); } });
    nextBtn.addEventListener('click', () => { currentPage++; render(); });

    render();
  });
})();
