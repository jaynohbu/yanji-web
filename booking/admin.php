<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Table Admin</title>
  <script src="config.js"></script>
  <script src="auth.js"></script>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: #1a1a2e;
      color: #eee;
      min-height: 100vh;
    }
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 0;
      border-bottom: 1px solid #333;
      margin-bottom: 30px;
    }
    header h1 { font-size: 24px; color: #fff; }
    .header-right {
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .lang-switch {
      display: flex;
      gap: 5px;
    }
    .lang-btn {
      padding: 5px 10px;
      background: #333;
      border: none;
      color: #888;
      cursor: pointer;
      border-radius: 4px;
      font-size: 12px;
    }
    .lang-btn.active {
      background: #4ecdc4;
      color: #1a1a2e;
    }
    .nav-links a {
      color: #888;
      text-decoration: none;
      margin-left: 20px;
      transition: color 0.2s;
    }
    .nav-links a:hover { color: #fff; }
    .nav-links a.active { color: #4ecdc4; }

    /* Tabs */
    .tabs {
      display: flex;
      gap: 10px;
      margin-bottom: 30px;
    }
    .tab-btn {
      padding: 12px 24px;
      background: #252542;
      border: none;
      color: #888;
      cursor: pointer;
      border-radius: 8px;
      font-size: 14px;
      transition: all 0.2s;
    }
    .tab-btn:hover { background: #333355; }
    .tab-btn.active {
      background: #4ecdc4;
      color: #1a1a2e;
    }
    .tab-content { display: none; }
    .tab-content.active { display: block; }

    /* Cards */
    .card {
      background: #252542;
      border-radius: 12px;
      padding: 24px;
      margin-bottom: 20px;
    }
    .card h2 {
      font-size: 18px;
      margin-bottom: 20px;
      color: #4ecdc4;
    }
    .card h3 {
      font-size: 16px;
      margin: 20px 0 15px;
      color: #fff;
    }

    /* Forms */
    .form-group {
      margin-bottom: 15px;
    }
    .form-group label {
      display: block;
      margin-bottom: 5px;
      color: #aaa;
      font-size: 14px;
    }
    .form-row {
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
    }
    .form-row .form-group { flex: 1; min-width: 150px; }
    input, select {
      width: 100%;
      padding: 10px 12px;
      background: #1a1a2e;
      border: 1px solid #333;
      border-radius: 6px;
      color: #fff;
      font-size: 14px;
    }
    input:focus, select:focus {
      outline: none;
      border-color: #4ecdc4;
    }
    input[type="checkbox"] {
      width: auto;
      margin-right: 8px;
    }
    .checkbox-label {
      display: flex;
      align-items: center;
      cursor: pointer;
    }

    /* Buttons */
    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
      transition: all 0.2s;
    }
    .btn-primary {
      background: #4ecdc4;
      color: #1a1a2e;
    }
    .btn-primary:hover { background: #3dbdb5; }
    .btn-danger {
      background: #ff6b6b;
      color: #fff;
    }
    .btn-danger:hover { background: #ee5a5a; }
    .btn-secondary {
      background: #444;
      color: #fff;
    }
    .btn-secondary:hover { background: #555; }
    .btn-sm { padding: 6px 12px; font-size: 12px; }

    /* Tables */
    .table-wrapper {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      margin: 0 -12px;
      padding: 0 12px;
    }
    .data-table {
      width: 100%;
      border-collapse: collapse;
      min-width: 600px;
    }
    .data-table th, .data-table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #333;
      white-space: nowrap;
    }
    .data-table th {
      color: #888;
      font-weight: normal;
      font-size: 13px;
    }
    .data-table tr:hover { background: #2a2a4a; }

    /* Tags */
    .tag {
      display: inline-block;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 12px;
      margin-right: 5px;
    }
    .tag-main { background: #3d5a80; }
    .tag-private { background: #7b2cbf; }
    .tag-split { background: #ff9f1c; color: #1a1a2e; }

    /* Weekly Schedule */
    .weekly-grid {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 10px;
    }
    /* Mobile Responsive */
    @media (max-width: 768px) {
      .container {
        padding: 10px;
      }
      header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
      }
      header h1 {
        font-size: 20px;
      }
      .header-right {
        width: 100%;
        justify-content: space-between;
      }
      .nav-links a {
        margin-left: 10px;
        font-size: 13px;
      }
      .tabs {
        flex-wrap: wrap;
        gap: 8px;
      }
      .tab-btn {
        padding: 10px 16px;
        font-size: 13px;
        flex: 1;
        min-width: calc(50% - 4px);
        text-align: center;
      }
      .card {
        padding: 16px;
        margin-bottom: 15px;
      }
      .card h2 {
        font-size: 16px;
      }
      .weekly-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      .form-row {
        flex-direction: column;
        gap: 10px;
      }
      .form-row .form-group {
        min-width: 100%;
      }
      .block-item {
        flex-wrap: wrap;
        gap: 10px;
      }
      .block-item input {
        min-width: 80px;
      }
      .block-item input[type="text"] {
        flex: 1 1 100%;
      }
      .actions {
        flex-wrap: wrap;
      }
      .btn {
        padding: 10px 16px;
        font-size: 13px;
      }
      .modal-content {
        padding: 20px;
        width: 95%;
        max-height: 90vh;
        overflow-y: auto;
      }
      .override-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
      }
      .override-item .btn {
        align-self: flex-end;
      }
      .session-options {
        gap: 8px;
      }
      .session-option {
        padding: 8px 12px;
        font-size: 13px;
      }
    }
    @media (max-width: 480px) {
      .weekly-grid {
        grid-template-columns: 1fr;
      }
      .tab-btn {
        min-width: 100%;
      }
      .day-card {
        padding: 12px;
      }
    }
    .day-card {
      background: #1a1a2e;
      border-radius: 8px;
      padding: 15px;
      text-align: center;
    }
    .day-card.closed { opacity: 0.5; }
    .day-card h4 {
      font-size: 14px;
      margin-bottom: 10px;
      color: #4ecdc4;
    }
    .day-card input {
      margin-bottom: 8px;
      text-align: center;
    }

    /* Override List */
    .override-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px;
      background: #1a1a2e;
      border-radius: 6px;
      margin-bottom: 8px;
    }
    .override-info { flex: 1; }
    .override-date { font-weight: bold; color: #fff; }
    .override-reason { color: #888; font-size: 13px; }

    /* Blocks */
    .block-item {
      display: flex;
      gap: 15px;
      align-items: center;
      padding: 12px;
      background: #1a1a2e;
      border-radius: 6px;
      margin-bottom: 8px;
    }
    .block-item input { flex: 1; }

    /* Session Options */
    .session-options {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }
    .session-option {
      display: flex;
      align-items: center;
      padding: 10px 15px;
      background: #1a1a2e;
      border-radius: 6px;
    }
    .session-option input { margin-right: 8px; }

    /* Actions */
    .actions {
      display: flex;
      gap: 10px;
      margin-top: 20px;
      padding-top: 20px;
      border-top: 1px solid #333;
    }

    /* Modal */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.7);
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }
    .modal.active { display: flex; }
    .modal-content {
      background: #252542;
      border-radius: 12px;
      padding: 30px;
      width: 90%;
      max-width: 500px;
    }
    .modal-content h2 { margin-bottom: 20px; }
    .modal-actions {
      display: flex;
      gap: 10px;
      justify-content: flex-end;
      margin-top: 20px;
    }

    /* Toast */
    .toast {
      position: fixed;
      bottom: 20px;
      right: 20px;
      padding: 15px 25px;
      background: #4ecdc4;
      color: #1a1a2e;
      border-radius: 8px;
      display: none;
      z-index: 1001;
    }
    .toast.error { background: #ff6b6b; color: #fff; }
    .toast.active { display: block; }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1 data-i18n="title">Yanji Admin</h1>
      <div class="header-right">
        <div class="lang-switch">
          <button class="lang-btn" data-lang="en">EN</button>
          <button class="lang-btn active" data-lang="ko">KO</button>
        </div>
        <nav class="nav-links">
          <a href="admin.php" class="active" data-i18n="nav.settings">Settings</a>
          <a href="dashboard.php" data-i18n="nav.dashboard">Dashboard</a>
          <a href="reservation.php" data-i18n="nav.reservation">Reservation</a>
          <a href="menu.php">Menu Admin</a>
        </nav>
      </div>
    </header>

    <!-- Tabs -->
    <div class="tabs">
      <button class="tab-btn active" data-tab="tables" data-i18n="tabs.tables">Tables</button>
      <button class="tab-btn" data-tab="hours" data-i18n="tabs.hours">Hours</button>
      <button class="tab-btn" data-tab="sessions" data-i18n="tabs.sessions">Sessions</button>
      <button class="tab-btn" data-tab="danger" data-i18n="tabs.danger">Danger Zone</button>
    </div>

    <!-- Tab: Tables -->
    <div id="tab-tables" class="tab-content active">
      <div class="card">
        <h2 data-i18n="tables.title">Table List</h2>
        <button class="btn btn-primary" onclick="openAddTableModal()" data-i18n="tables.add">+ Add Table</button>

        <div class="table-wrapper">
          <table class="data-table" style="margin-top: 20px;">
            <thead>
              <tr>
                <th>ID</th>
                <th data-i18n="tables.name">Name</th>
                <th data-i18n="tables.guests">Guests</th>
                <th data-i18n="tables.location">Location</th>
                <th data-i18n="tables.split">Split</th>
                <th data-i18n="tables.actions">Actions</th>
              </tr>
            </thead>
            <tbody id="tables-body">
              <!-- Populated by JS -->
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Tab: Hours -->
    <div id="tab-hours" class="tab-content">
      <div class="card">
        <h2 data-i18n="hours.default">Default Hours</h2>
        <div class="form-row">
          <div class="form-group">
            <label data-i18n="hours.open">Open</label>
            <input type="time" id="default-open" value="11:30">
          </div>
          <div class="form-group">
            <label data-i18n="hours.close">Close</label>
            <input type="time" id="default-close" value="22:00">
          </div>
          <div class="form-group">
            <label data-i18n="hours.lastRes">Last Reservation</label>
            <input type="time" id="default-last" value="20:30">
          </div>
        </div>
      </div>

      <div class="card">
        <h2 data-i18n="hours.weekly">Weekly Schedule</h2>
        <div class="weekly-grid">
          <div class="day-card closed" data-day="monday">
            <h4 data-i18n="days.mon">Mon</h4>
            <label class="checkbox-label">
              <input type="checkbox" class="day-closed" checked> <span data-i18n="hours.closed">Closed</span>
            </label>
            <input type="time" class="day-open" value="11:30" disabled>
            <input type="time" class="day-close" value="22:00" disabled>
          </div>
          <div class="day-card" data-day="tuesday">
            <h4 data-i18n="days.tue">Tue</h4>
            <label class="checkbox-label">
              <input type="checkbox" class="day-closed"> <span data-i18n="hours.closed">Closed</span>
            </label>
            <input type="time" class="day-open" value="11:30">
            <input type="time" class="day-close" value="22:00">
          </div>
          <div class="day-card" data-day="wednesday">
            <h4 data-i18n="days.wed">Wed</h4>
            <label class="checkbox-label">
              <input type="checkbox" class="day-closed"> <span data-i18n="hours.closed">Closed</span>
            </label>
            <input type="time" class="day-open" value="11:30">
            <input type="time" class="day-close" value="22:00">
          </div>
          <div class="day-card" data-day="thursday">
            <h4 data-i18n="days.thu">Thu</h4>
            <label class="checkbox-label">
              <input type="checkbox" class="day-closed"> <span data-i18n="hours.closed">Closed</span>
            </label>
            <input type="time" class="day-open" value="11:30">
            <input type="time" class="day-close" value="22:00">
          </div>
          <div class="day-card" data-day="friday">
            <h4 data-i18n="days.fri">Fri</h4>
            <label class="checkbox-label">
              <input type="checkbox" class="day-closed"> <span data-i18n="hours.closed">Closed</span>
            </label>
            <input type="time" class="day-open" value="11:30">
            <input type="time" class="day-close" value="23:00">
          </div>
          <div class="day-card" data-day="saturday">
            <h4 data-i18n="days.sat">Sat</h4>
            <label class="checkbox-label">
              <input type="checkbox" class="day-closed"> <span data-i18n="hours.closed">Closed</span>
            </label>
            <input type="time" class="day-open" value="11:30">
            <input type="time" class="day-close" value="23:00">
          </div>
          <div class="day-card" data-day="sunday">
            <h4 data-i18n="days.sun">Sun</h4>
            <label class="checkbox-label">
              <input type="checkbox" class="day-closed"> <span data-i18n="hours.closed">Closed</span>
            </label>
            <input type="time" class="day-open" value="11:30">
            <input type="time" class="day-close" value="21:00">
          </div>
        </div>
      </div>

      <div class="card">
        <h2 data-i18n="hours.blocks">Time Blocks (Lunch/Dinner)</h2>
        <div id="blocks-container">
          <div class="block-item">
            <input type="text" data-i18n-placeholder="hours.blockName" placeholder="Block name" value="Lunch">
            <input type="time" value="11:30">
            <span>~</span>
            <input type="time" value="14:30">
            <button class="btn btn-danger btn-sm" onclick="removeBlock(this)" data-i18n="common.delete">Delete</button>
          </div>
          <div class="block-item">
            <input type="text" data-i18n-placeholder="hours.blockName" placeholder="Block name" value="Dinner">
            <input type="time" value="17:00">
            <span>~</span>
            <input type="time" value="22:00">
            <button class="btn btn-danger btn-sm" onclick="removeBlock(this)" data-i18n="common.delete">Delete</button>
          </div>
        </div>
        <button class="btn btn-secondary" onclick="addBlock()" style="margin-top: 10px;" data-i18n="hours.addBlock">+ Add Block</button>
      </div>

      <div class="card">
        <h2 data-i18n="hours.overrides">Date Overrides (Holidays/Special)</h2>
        <div id="overrides-container">
          <!-- Populated by JS -->
        </div>
        <button class="btn btn-secondary" onclick="openOverrideModal()" style="margin-top: 10px;" data-i18n="hours.addOverride">+ Add Date</button>
      </div>

      <div class="actions">
        <button class="btn btn-primary" onclick="saveHours()" data-i18n="hours.save">Save Hours</button>
      </div>
    </div>

    <!-- Tab: Sessions -->
    <div id="tab-sessions" class="tab-content">
      <div class="card">
        <h2 data-i18n="sessions.title">Session Duration Options</h2>
        <p style="color: #888; margin-bottom: 20px;" data-i18n="sessions.desc">Available dining durations for reservations.</p>

        <div class="session-options" id="session-options">
          <label class="session-option">
            <input type="checkbox" value="45" checked> 45min
          </label>
          <label class="session-option">
            <input type="checkbox" value="80" checked> 80min
          </label>
          <label class="session-option">
            <input type="checkbox" value="90" checked> 90min
          </label>
          <label class="session-option">
            <input type="checkbox" value="120" checked> 2hr
          </label>
          <label class="session-option">
            <input type="checkbox" value="180"> 3hr
          </label>
          <label class="session-option">
            <input type="checkbox" value="240"> 4hr
          </label>
        </div>

        <div class="actions">
          <button class="btn btn-primary" onclick="saveSessions()" data-i18n="sessions.save">Save Sessions</button>
        </div>
      </div>
    </div>

    <!-- Tab: Danger Zone -->
    <div id="tab-danger" class="tab-content">
      <div class="card" style="border: 1px solid #ff6b6b;">
        <h2 style="color: #ff6b6b;" data-i18n="danger.title">Danger Zone</h2>

        <h3 data-i18n="danger.cancelDay">Cancel All Reservations for a Day</h3>
        <div class="form-row">
          <div class="form-group">
            <label data-i18n="common.date">Date</label>
            <input type="date" id="cancel-date">
          </div>
          <div class="form-group" style="display: flex; align-items: flex-end;">
            <button class="btn btn-danger" onclick="cancelAllForDay()" data-i18n="danger.cancelAll">Cancel All</button>
          </div>
        </div>

        <h3 data-i18n="danger.export">Export / Import Config</h3>
        <div class="form-row">
          <button class="btn btn-secondary" onclick="exportConfig()" data-i18n="danger.exportJson">Export JSON</button>
          <button class="btn btn-secondary" onclick="document.getElementById('import-file').click()" data-i18n="danger.importJson">Import JSON</button>
          <input type="file" id="import-file" accept=".json" style="display:none" onchange="importConfig(event)">
        </div>
      </div>
    </div>
  </div>

  <!-- Add/Edit Table Modal -->
  <div class="modal" id="table-modal">
    <div class="modal-content">
      <h2 id="table-modal-title" data-i18n="tables.addTitle">Add Table</h2>
      <input type="hidden" id="table-edit-id">

      <div class="form-group">
        <label data-i18n="tables.tableId">Table ID</label>
        <input type="text" id="table-id" data-i18n-placeholder="tables.tableIdPlaceholder" placeholder="e.g. 1, 8a, 9">
      </div>
      <div class="form-group">
        <label data-i18n="tables.tableName">Table Name</label>
        <input type="text" id="table-name" data-i18n-placeholder="tables.tableNamePlaceholder" placeholder="e.g. Table 1">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label data-i18n="tables.minGuests">Min Guests</label>
          <input type="number" id="table-min" value="2" min="1">
        </div>
        <div class="form-group">
          <label data-i18n="tables.maxGuests">Max Guests</label>
          <input type="number" id="table-max" value="4" min="1">
        </div>
      </div>
      <div class="form-group">
        <label data-i18n="tables.location">Location</label>
        <select id="table-location">
          <option value="main" data-i18n="tables.mainHall">Main Hall</option>
          <option value="private" data-i18n="tables.privateRoom">Private Room</option>
        </select>
      </div>
      <div class="form-group">
        <label class="checkbox-label">
          <input type="checkbox" id="table-splittable"> <span data-i18n="tables.splittable">Splittable (e.g. 8 → 8A + 8B)</span>
        </label>
      </div>
      <div id="parts-section" style="display: none;">
        <h3 data-i18n="tables.partsConfig">Parts Configuration</h3>
        <div id="parts-container"></div>
        <button class="btn btn-secondary btn-sm" onclick="addPart()" data-i18n="tables.addPart">+ Add Part</button>
      </div>

      <div class="modal-actions">
        <button class="btn btn-secondary" onclick="closeTableModal()" data-i18n="common.cancel">Cancel</button>
        <button class="btn btn-primary" onclick="saveTable()" data-i18n="common.save">Save</button>
      </div>
    </div>
  </div>

  <!-- Override Modal -->
  <div class="modal" id="override-modal">
    <div class="modal-content">
      <h2 data-i18n="hours.addOverrideTitle">Add Date Override</h2>

      <div class="form-group">
        <label data-i18n="common.date">Date</label>
        <input type="date" id="override-date">
      </div>
      <div class="form-group">
        <label class="checkbox-label">
          <input type="checkbox" id="override-closed"> <span data-i18n="hours.closed">Closed</span>
        </label>
      </div>
      <div id="override-hours-section">
        <div class="form-row">
          <div class="form-group">
            <label data-i18n="hours.open">Open</label>
            <input type="time" id="override-open" value="11:30">
          </div>
          <div class="form-group">
            <label data-i18n="hours.close">Close</label>
            <input type="time" id="override-close" value="22:00">
          </div>
        </div>
      </div>
      <div class="form-group">
        <label data-i18n="hours.reason">Reason</label>
        <input type="text" id="override-reason" data-i18n-placeholder="hours.reasonPlaceholder" placeholder="e.g. Holiday, Special Event">
      </div>

      <div class="modal-actions">
        <button class="btn btn-secondary" onclick="closeOverrideModal()" data-i18n="common.cancel">Cancel</button>
        <button class="btn btn-primary" onclick="saveOverride()" data-i18n="common.save">Save</button>
      </div>
    </div>
  </div>

  <!-- Toast -->
  <div class="toast" id="toast"></div>

  <script>
    // ==================== i18n ====================
    const i18n = {
      en: {
        title: 'Table Admin',
        nav: {
          settings: 'Settings',
          dashboard: 'Dashboard',
          reservation: 'Reservation'
        },
        tabs: {
          tables: 'Tables',
          hours: 'Hours',
          sessions: 'Sessions',
          danger: 'Danger Zone'
        },
        tables: {
          title: 'Table List',
          add: '+ Add Table',
          name: 'Name',
          guests: 'Guests',
          location: 'Location',
          split: 'Split',
          actions: 'Actions',
          addTitle: 'Add Table',
          editTitle: 'Edit Table',
          tableId: 'Table ID',
          tableIdPlaceholder: 'e.g. 1, 8a, 9',
          tableName: 'Table Name',
          tableNamePlaceholder: 'e.g. Table 1',
          minGuests: 'Min Guests',
          maxGuests: 'Max Guests',
          mainHall: 'Main Hall',
          privateRoom: 'Private Room',
          splittable: 'Splittable (e.g. 8 → 8A + 8B)',
          partsConfig: 'Parts Configuration',
          addPart: '+ Add Part',
          main: 'Main',
          private: 'Private',
          canSplit: 'Splittable',
          saved: 'Table saved',
          deleted: 'Table deleted'
        },
        hours: {
          default: 'Default Hours',
          open: 'Open',
          close: 'Close',
          lastRes: 'Last Reservation',
          weekly: 'Weekly Schedule',
          closed: 'Closed',
          blocks: 'Time Blocks (Lunch/Dinner)',
          blockName: 'Block name',
          addBlock: '+ Add Block',
          overrides: 'Date Overrides (Holidays/Special)',
          addOverride: '+ Add Date',
          addOverrideTitle: 'Add Date Override',
          reason: 'Reason',
          reasonPlaceholder: 'e.g. Holiday, Special Event',
          save: 'Save Hours',
          saved: 'Hours saved',
          overrideAdded: 'Date override added',
          overrideDeleted: 'Date override deleted'
        },
        days: {
          mon: 'Mon',
          tue: 'Tue',
          wed: 'Wed',
          thu: 'Thu',
          fri: 'Fri',
          sat: 'Sat',
          sun: 'Sun'
        },
        sessions: {
          title: 'Session Duration Options',
          desc: 'Available dining durations for reservations.',
          save: 'Save Sessions',
          saved: 'Sessions saved'
        },
        danger: {
          title: 'Danger Zone',
          cancelDay: 'Cancel All Reservations for a Day',
          cancelAll: 'Cancel All',
          export: 'Export / Import Config',
          exportJson: 'Export JSON',
          importJson: 'Import JSON',
          cancelled: 'All reservations cancelled for',
          imported: 'Config imported',
          invalidJson: 'Invalid JSON file'
        },
        common: {
          date: 'Date',
          save: 'Save',
          cancel: 'Cancel',
          delete: 'Delete',
          edit: 'Edit',
          selectDate: 'Please select a date',
          confirmDelete: 'Are you sure you want to delete?',
          confirmCancel: 'Cancel all reservations for'
        }
      },
      ko: {
        title: '테이블 관리자',
        nav: {
          settings: '설정',
          dashboard: '대시보드',
          reservation: '예약'
        },
        tabs: {
          tables: '테이블 설정',
          hours: '영업시간',
          sessions: '세션 시간',
          danger: '위험 구역'
        },
        tables: {
          title: '테이블 목록',
          add: '+ 테이블 추가',
          name: '이름',
          guests: '인원',
          location: '위치',
          split: '분할',
          actions: '작업',
          addTitle: '테이블 추가',
          editTitle: '테이블 수정',
          tableId: '테이블 ID',
          tableIdPlaceholder: '예: 1, 8a, 9',
          tableName: '테이블 이름',
          tableNamePlaceholder: '예: 테이블 1',
          minGuests: '최소 인원',
          maxGuests: '최대 인원',
          mainHall: '메인 홀',
          privateRoom: '프라이빗룸',
          splittable: '분할 가능 (예: 8 → 8A + 8B)',
          partsConfig: '분할 파트 설정',
          addPart: '+ 파트 추가',
          main: '메인',
          private: '프라이빗',
          canSplit: '분할가능',
          saved: '테이블이 저장되었습니다',
          deleted: '테이블이 삭제되었습니다'
        },
        hours: {
          default: '기본 영업시간',
          open: '오픈',
          close: '마감',
          lastRes: '마지막 예약',
          weekly: '요일별 설정',
          closed: '휴무',
          blocks: '시간 블록 (점심/저녁)',
          blockName: '블록 이름',
          addBlock: '+ 블록 추가',
          overrides: '특정 날짜 설정 (휴무/특별 영업)',
          addOverride: '+ 날짜 추가',
          addOverrideTitle: '날짜 설정 추가',
          reason: '사유',
          reasonPlaceholder: '예: 설날, 발렌타인 특별 영업',
          save: '영업시간 저장',
          saved: '영업시간이 저장되었습니다',
          overrideAdded: '날짜 설정이 추가되었습니다',
          overrideDeleted: '날짜 설정이 삭제되었습니다'
        },
        days: {
          mon: '월',
          tue: '화',
          wed: '수',
          thu: '목',
          fri: '금',
          sat: '토',
          sun: '일'
        },
        sessions: {
          title: '세션 시간 옵션',
          desc: '예약 시 선택 가능한 식사 시간입니다.',
          save: '세션 옵션 저장',
          saved: '세션 옵션이 저장되었습니다'
        },
        danger: {
          title: '위험 구역',
          cancelDay: '특정 날짜 전체 예약 취소',
          cancelAll: '전체 취소',
          export: '설정 내보내기 / 가져오기',
          exportJson: 'JSON 내보내기',
          importJson: 'JSON 가져오기',
          cancelled: '예약이 모두 취소되었습니다:',
          imported: '설정을 가져왔습니다',
          invalidJson: '잘못된 JSON 파일입니다'
        },
        common: {
          date: '날짜',
          save: '저장',
          cancel: '취소',
          delete: '삭제',
          edit: '수정',
          selectDate: '날짜를 선택해주세요',
          confirmDelete: '정말 삭제하시겠습니까?',
          confirmCancel: '의 모든 예약을 취소하시겠습니까?'
        }
      }
    };

    let currentLang = localStorage.getItem('yanji-lang') || (navigator.language.startsWith('ko') ? 'ko' : 'en');

    function t(key) {
      const keys = key.split('.');
      let value = i18n[currentLang];
      for (const k of keys) {
        value = value?.[k];
      }
      return value || key;
    }

    function applyTranslations() {
      document.querySelectorAll('[data-i18n]').forEach(el => {
        el.textContent = t(el.dataset.i18n);
      });
      document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
        el.placeholder = t(el.dataset.i18nPlaceholder);
      });
      document.documentElement.lang = currentLang;

      // Update lang buttons
      document.querySelectorAll('.lang-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.lang === currentLang);
      });

      // Re-render dynamic content
      renderTables();
      renderOverrides();
    }

    function setLanguage(lang) {
      currentLang = lang;
      localStorage.setItem('yanji-lang', lang);
      applyTranslations();
    }

    // ==================== State ====================
    const API_BASE = window.YANJI_CONFIG?.API_BASE || 'http://localhost:3000';

    let tables = [];
    let operationHours = {
      default: { open: '11:30', close: '22:00', lastReservation: '20:30' },
      weekly: {},
      override: {},
      blocks: []
    };
    let sessionOptions = [];

    // ==================== API Functions ====================
    async function fetchConfig() {
      try {
        const res = await fetch(`${API_BASE}/config`);
        if (!res.ok) throw new Error('Failed to fetch config');
        const data = await res.json();

        // Load tables
        if (data.tables && data.tables.length > 0) {
          tables = data.tables;
        }

        // Load operation hours
        if (data.operationHours) {
          operationHours = data.operationHours;
          populateHoursForm();
        }

        // Load session options
        if (data.sessions && data.sessions.length > 0) {
          sessionOptions = data.sessions.map(s => s.minutes);
          populateSessionsForm(data.sessions);
        }

        renderTables();
        renderOverrides();
        renderBlocks();
        showToast('Config loaded');
      } catch (e) {
        console.error('Failed to fetch config:', e);
        showToast('Failed to load config - using defaults', true);
      }
    }

    async function saveTablesConfig() {
      try {
        const res = await fetch(`${API_BASE}/config/tables`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ tables })
        });
        if (!res.ok) throw new Error('Failed to save tables');
        showToast(t('tables.saved'));
      } catch (e) {
        console.error('Failed to save tables:', e);
        showToast('Failed to save tables', true);
      }
    }

    async function saveHoursConfig() {
      try {
        const res = await fetch(`${API_BASE}/config/hours`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(operationHours)
        });
        if (!res.ok) throw new Error('Failed to save hours');
        showToast(t('hours.saved'));
      } catch (e) {
        console.error('Failed to save hours:', e);
        showToast('Failed to save hours', true);
      }
    }

    async function saveSessionsConfig() {
      try {
        const sessions = sessionOptions.map(min => {
          if (min === 60) return { id: '60min', label: '60min', minutes: 60 };
          if (min === 45) return { id: '45min', label: '45min', minutes: 45 };
          if (min === 80) return { id: '80min', label: '80min', minutes: 80 };
          if (min === 90) return { id: '90min', label: '90min', minutes: 90 };
          if (min === 120) return { id: '2hr', label: '2hr', minutes: 120 };
          if (min === 180) return { id: '3hr', label: '3hr', minutes: 180 };
          if (min === 240) return { id: '4hr', label: '4hr', minutes: 240 };
          return { id: `${min}min`, label: `${min}min`, minutes: min };
        });

        const res = await fetch(`${API_BASE}/config/sessions`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ sessions })
        });
        if (!res.ok) throw new Error('Failed to save sessions');
        showToast(t('sessions.saved'));
      } catch (e) {
        console.error('Failed to save sessions:', e);
        showToast('Failed to save sessions', true);
      }
    }

    function populateHoursForm() {
      // Default hours
      if (operationHours.default) {
        document.getElementById('default-open').value = operationHours.default.open || '11:30';
        document.getElementById('default-close').value = operationHours.default.close || '22:00';
        document.getElementById('default-last').value = operationHours.default.lastReservation || '20:30';
      }

      // Weekly schedule
      if (operationHours.weekly) {
        document.querySelectorAll('.day-card').forEach(card => {
          const day = card.dataset.day;
          const dayData = operationHours.weekly[day];
          if (dayData) {
            const closedCheckbox = card.querySelector('.day-closed');
            const openInput = card.querySelector('.day-open');
            const closeInput = card.querySelector('.day-close');

            if (dayData.closed) {
              closedCheckbox.checked = true;
              card.classList.add('closed');
              openInput.disabled = true;
              closeInput.disabled = true;
            } else {
              closedCheckbox.checked = false;
              card.classList.remove('closed');
              openInput.disabled = false;
              closeInput.disabled = false;
              if (dayData.open) openInput.value = dayData.open;
              if (dayData.close) closeInput.value = dayData.close;
            }
          }
        });
      }
    }

    function populateSessionsForm(sessions) {
      const container = document.getElementById('session-options');
      container.innerHTML = '';

      const allOptions = [
        { value: 45, label: '45min' },
        { value: 60, label: '60min' },
        { value: 80, label: '80min' },
        { value: 90, label: '90min' },
        { value: 120, label: '2hr' },
        { value: 180, label: '3hr' },
        { value: 240, label: '4hr' }
      ];

      allOptions.forEach(opt => {
        const isChecked = sessions.some(s => s.minutes === opt.value);
        const label = document.createElement('label');
        label.className = 'session-option';
        label.innerHTML = `
          <input type="checkbox" value="${opt.value}" ${isChecked ? 'checked' : ''}> ${opt.label}
        `;
        container.appendChild(label);
      });
    }

    function renderBlocks() {
      const container = document.getElementById('blocks-container');
      container.innerHTML = '';

      if (operationHours.blocks && operationHours.blocks.length > 0) {
        operationHours.blocks.forEach(block => {
          const div = document.createElement('div');
          div.className = 'block-item';
          div.innerHTML = `
            <input type="text" placeholder="${t('hours.blockName')}" value="${block.name || ''}">
            <input type="time" value="${block.start || '11:30'}">
            <span>~</span>
            <input type="time" value="${block.end || '14:30'}">
            <button class="btn btn-danger btn-sm" onclick="removeBlock(this)">${t('common.delete')}</button>
          `;
          container.appendChild(div);
        });
      }
    }

    // ==================== Event Listeners ====================
    document.querySelectorAll('.lang-btn').forEach(btn => {
      btn.addEventListener('click', () => setLanguage(btn.dataset.lang));
    });

    document.querySelectorAll('.tab-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
      });
    });

    document.querySelectorAll('.day-closed').forEach(checkbox => {
      checkbox.addEventListener('change', function() {
        const card = this.closest('.day-card');
        const inputs = card.querySelectorAll('input[type="time"]');
        if (this.checked) {
          card.classList.add('closed');
          inputs.forEach(i => i.disabled = true);
        } else {
          card.classList.remove('closed');
          inputs.forEach(i => i.disabled = false);
        }
      });
    });

    document.getElementById('override-closed').addEventListener('change', function() {
      document.getElementById('override-hours-section').style.display = this.checked ? 'none' : 'block';
    });

    document.getElementById('table-splittable').addEventListener('change', function() {
      document.getElementById('parts-section').style.display = this.checked ? 'block' : 'none';
      if (this.checked && document.getElementById('parts-container').children.length === 0) {
        addPart();
        addPart();
      }
    });

    // ==================== Render Functions ====================
    function renderTables() {
      const tbody = document.getElementById('tables-body');
      tbody.innerHTML = '';

      tables.forEach(table => {
        const displayName = currentLang === 'ko' ? (table.nameKo || table.name) : table.name;
        const locationLabel = table.location === 'private' ? t('tables.private') : t('tables.main');
        const guestLabel = currentLang === 'ko' ? '명' : ' guests';

        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${table.id}</td>
          <td>${displayName}</td>
          <td>${table.minGuests}-${table.maxGuests}${guestLabel}</td>
          <td><span class="tag ${table.location === 'private' ? 'tag-private' : 'tag-main'}">${locationLabel}</span></td>
          <td>${table.splittable ? `<span class="tag tag-split">${t('tables.canSplit')}</span>` : '-'}</td>
          <td>
            <button class="btn btn-secondary btn-sm" onclick="editTable('${table.id}')">${t('common.edit')}</button>
            <button class="btn btn-danger btn-sm" onclick="deleteTable('${table.id}')">${t('common.delete')}</button>
          </td>
        `;
        tbody.appendChild(tr);

        if (table.splittable && table.parts) {
          table.parts.forEach(part => {
            const partTr = document.createElement('tr');
            partTr.style.background = '#1e1e3a';
            partTr.innerHTML = `
              <td style="padding-left: 30px;">└ ${part.id}</td>
              <td>${part.name}</td>
              <td>${part.minGuests}-${part.maxGuests}${guestLabel}</td>
              <td>-</td>
              <td>-</td>
              <td>-</td>
            `;
            tbody.appendChild(partTr);
          });
        }
      });
    }

    function renderOverrides() {
      const container = document.getElementById('overrides-container');
      container.innerHTML = '';

      Object.entries(operationHours.override).forEach(([date, data]) => {
        const closedLabel = t('hours.closed');
        const div = document.createElement('div');
        div.className = 'override-item';
        div.innerHTML = `
          <div class="override-info">
            <div class="override-date">${date}</div>
            <div class="override-reason">${data.closed ? closedLabel : `${data.open} - ${data.close}`} ${data.reason ? `(${data.reason})` : ''}</div>
          </div>
          <button class="btn btn-danger btn-sm" onclick="deleteOverride('${date}')">${t('common.delete')}</button>
        `;
        container.appendChild(div);
      });
    }

    // ==================== Table Modal ====================
    function openAddTableModal() {
      document.getElementById('table-modal-title').textContent = t('tables.addTitle');
      document.getElementById('table-edit-id').value = '';
      document.getElementById('table-id').value = '';
      document.getElementById('table-name').value = '';
      document.getElementById('table-min').value = '2';
      document.getElementById('table-max').value = '4';
      document.getElementById('table-location').value = 'main';
      document.getElementById('table-splittable').checked = false;
      document.getElementById('parts-section').style.display = 'none';
      document.getElementById('parts-container').innerHTML = '';
      document.getElementById('table-modal').classList.add('active');
    }

    function editTable(id) {
      const table = tables.find(t => t.id === id);
      if (!table) return;

      document.getElementById('table-modal-title').textContent = t('tables.editTitle');
      document.getElementById('table-edit-id').value = id;
      document.getElementById('table-id').value = table.id;
      document.getElementById('table-name').value = table.name;
      document.getElementById('table-min').value = table.minGuests;
      document.getElementById('table-max').value = table.maxGuests;
      document.getElementById('table-location').value = table.location;
      document.getElementById('table-splittable').checked = table.splittable;

      if (table.splittable && table.parts) {
        document.getElementById('parts-section').style.display = 'block';
        document.getElementById('parts-container').innerHTML = '';
        table.parts.forEach(part => addPart(part));
      } else {
        document.getElementById('parts-section').style.display = 'none';
        document.getElementById('parts-container').innerHTML = '';
      }

      document.getElementById('table-modal').classList.add('active');
    }

    function closeTableModal() {
      document.getElementById('table-modal').classList.remove('active');
    }

    function addPart(data = null) {
      const container = document.getElementById('parts-container');
      const div = document.createElement('div');
      div.className = 'form-row';
      div.style.marginBottom = '10px';
      div.innerHTML = `
        <div class="form-group">
          <input type="text" class="part-id" placeholder="ID (e.g. 8a)" value="${data?.id || ''}">
        </div>
        <div class="form-group">
          <input type="text" class="part-name" placeholder="Name" value="${data?.name || ''}">
        </div>
        <div class="form-group">
          <input type="number" class="part-min" placeholder="Min" value="${data?.minGuests || 2}" min="1">
        </div>
        <div class="form-group">
          <input type="number" class="part-max" placeholder="Max" value="${data?.maxGuests || 4}" min="1">
        </div>
        <button class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">X</button>
      `;
      container.appendChild(div);
    }

    async function saveTable() {
      const editId = document.getElementById('table-edit-id').value;
      const table = {
        id: document.getElementById('table-id').value,
        name: document.getElementById('table-name').value,
        minGuests: parseInt(document.getElementById('table-min').value),
        maxGuests: parseInt(document.getElementById('table-max').value),
        location: document.getElementById('table-location').value,
        splittable: document.getElementById('table-splittable').checked
      };

      if (table.splittable) {
        table.parts = [];
        document.querySelectorAll('#parts-container .form-row').forEach(row => {
          table.parts.push({
            id: row.querySelector('.part-id').value,
            name: row.querySelector('.part-name').value,
            minGuests: parseInt(row.querySelector('.part-min').value),
            maxGuests: parseInt(row.querySelector('.part-max').value)
          });
        });
      }

      if (editId) {
        const idx = tables.findIndex(t => t.id === editId);
        if (idx !== -1) tables[idx] = table;
      } else {
        tables.push(table);
      }

      renderTables();
      closeTableModal();
      await saveTablesConfig();
    }

    async function deleteTable(id) {
      if (!confirm(t('common.confirmDelete'))) return;
      tables = tables.filter(t => t.id !== id);
      renderTables();
      await saveTablesConfig();
    }

    // ==================== Blocks ====================
    function addBlock() {
      const container = document.getElementById('blocks-container');
      const div = document.createElement('div');
      div.className = 'block-item';
      div.innerHTML = `
        <input type="text" placeholder="${t('hours.blockName')}">
        <input type="time" value="11:30">
        <span>~</span>
        <input type="time" value="14:30">
        <button class="btn btn-danger btn-sm" onclick="removeBlock(this)">${t('common.delete')}</button>
      `;
      container.appendChild(div);
    }

    function removeBlock(btn) {
      btn.parentElement.remove();
    }

    // ==================== Override Modal ====================
    function openOverrideModal() {
      document.getElementById('override-date').value = '';
      document.getElementById('override-closed').checked = false;
      document.getElementById('override-hours-section').style.display = 'block';
      document.getElementById('override-open').value = '11:30';
      document.getElementById('override-close').value = '22:00';
      document.getElementById('override-reason').value = '';
      document.getElementById('override-modal').classList.add('active');
    }

    function closeOverrideModal() {
      document.getElementById('override-modal').classList.remove('active');
    }

    function saveOverride() {
      const date = document.getElementById('override-date').value;
      const closed = document.getElementById('override-closed').checked;
      const reason = document.getElementById('override-reason').value;

      if (!date) {
        showToast(t('common.selectDate'), true);
        return;
      }

      const override = { reason };
      if (closed) {
        override.closed = true;
      } else {
        override.open = document.getElementById('override-open').value;
        override.close = document.getElementById('override-close').value;
      }

      operationHours.override[date] = override;
      renderOverrides();
      closeOverrideModal();
      showToast(t('hours.overrideAdded'));
    }

    function deleteOverride(date) {
      delete operationHours.override[date];
      renderOverrides();
      showToast(t('hours.overrideDeleted'));
    }

    // ==================== Save Functions ====================
    function saveHours() {
      operationHours.default = {
        open: document.getElementById('default-open').value,
        close: document.getElementById('default-close').value,
        lastReservation: document.getElementById('default-last').value
      };

      document.querySelectorAll('.day-card').forEach(card => {
        const day = card.dataset.day;
        const closed = card.querySelector('.day-closed').checked;
        if (closed) {
          operationHours.weekly[day] = { closed: true };
        } else {
          operationHours.weekly[day] = {
            open: card.querySelector('.day-open').value,
            close: card.querySelector('.day-close').value
          };
        }
      });

      operationHours.blocks = [];
      document.querySelectorAll('#blocks-container .block-item').forEach(item => {
        const inputs = item.querySelectorAll('input');
        operationHours.blocks.push({
          name: inputs[0].value,
          start: inputs[1].value,
          end: inputs[2].value
        });
      });

      saveHoursConfig();
    }

    function saveSessions() {
      sessionOptions = [];
      document.querySelectorAll('#session-options input:checked').forEach(cb => {
        sessionOptions.push(parseInt(cb.value));
      });

      saveSessionsConfig();
    }

    // ==================== Danger Zone ====================
    async function cancelAllForDay() {
      const date = document.getElementById('cancel-date').value;
      if (!date) {
        showToast(t('common.selectDate'), true);
        return;
      }
      if (!confirm(`${date} ${t('common.confirmCancel')}`)) return;

      try {
        const res = await fetch(`${API_BASE}/reservations/cancel-day/${date}`, {
          method: 'POST'
        });
        if (!res.ok) throw new Error('Failed to cancel reservations');
        showToast(`${t('danger.cancelled')} ${date}`);
      } catch (e) {
        console.error('Failed to cancel reservations:', e);
        showToast('Failed to cancel reservations', true);
      }
    }

    function exportConfig() {
      const config = { tables, operationHours, sessionOptions };
      const blob = new Blob([JSON.stringify(config, null, 2)], { type: 'application/json' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = `yanji-config-${new Date().toISOString().split('T')[0]}.json`;
      a.click();
      URL.revokeObjectURL(url);
    }

    function importConfig(event) {
      const file = event.target.files[0];
      if (!file) return;

      const reader = new FileReader();
      reader.onload = function(e) {
        try {
          const config = JSON.parse(e.target.result);
          if (config.tables) tables = config.tables;
          if (config.operationHours) operationHours = config.operationHours;
          if (config.sessionOptions) sessionOptions = config.sessionOptions;

          renderTables();
          renderOverrides();
          showToast(t('danger.imported'));
        } catch (err) {
          showToast(t('danger.invalidJson'), true);
        }
      };
      reader.readAsText(file);
    }

    // ==================== Toast ====================
    function showToast(message, isError = false) {
      const toast = document.getElementById('toast');
      toast.textContent = message;
      toast.className = 'toast active' + (isError ? ' error' : '');
      setTimeout(() => toast.classList.remove('active'), 3000);
    }

    // ==================== Initialize ====================
    fetchConfig().then(() => {
      applyTranslations();
    });
  </script>
</body>
</html>
