<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Table Dashboard</title>
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
      max-width: 1400px;
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

    /* Date Picker */
    .date-picker {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-bottom: 30px;
    }
    .date-picker input {
      padding: 10px 15px;
      background: #252542;
      border: 1px solid #333;
      border-radius: 6px;
      color: #fff;
      font-size: 16px;
    }
    .date-picker button {
      padding: 10px 15px;
      background: #333;
      border: none;
      color: #fff;
      cursor: pointer;
      border-radius: 6px;
      font-size: 14px;
    }
    .date-picker button:hover { background: #444; }
    .date-info {
      color: #888;
      font-size: 14px;
    }
    .date-info.closed {
      color: #ff6b6b;
    }

    /* Stats */
    .stats-row {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 15px;
      margin-bottom: 30px;
    }
    @media (max-width: 768px) {
      .stats-row { grid-template-columns: repeat(2, 1fr); }
    }
    .stat-card {
      background: #252542;
      border-radius: 12px;
      padding: 20px;
      text-align: center;
    }
    .stat-card .number {
      font-size: 36px;
      font-weight: bold;
      color: #4ecdc4;
    }
    .stat-card .label {
      color: #888;
      font-size: 14px;
      margin-top: 5px;
    }
    .stat-card.warning .number { color: #ff9f1c; }
    .stat-card.danger .number { color: #ff6b6b; }

    /* Table Grid */
    .table-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 15px;
      margin-bottom: 30px;
    }
    .table-card {
      background: #252542;
      border-radius: 12px;
      padding: 20px;
      position: relative;
      cursor: pointer;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .table-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 20px rgba(0,0,0,0.3);
    }
    .table-card.available { border-left: 4px solid #4ecdc4; }
    .table-card.occupied { border-left: 4px solid #ff6b6b; }
    .table-card.reserved { border-left: 4px solid #ff9f1c; }
    .table-card .table-name {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 5px;
    }
    .table-card .table-capacity {
      color: #888;
      font-size: 13px;
      margin-bottom: 10px;
    }
    .table-card .status-badge {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: bold;
    }
    .status-badge.available { background: #4ecdc4; color: #1a1a2e; }
    .status-badge.occupied { background: #ff6b6b; color: #fff; }
    .status-badge.reserved { background: #ff9f1c; color: #1a1a2e; }
    .table-card .current-info {
      margin-top: 10px;
      padding-top: 10px;
      border-top: 1px solid #333;
      font-size: 13px;
    }
    .table-card .current-info .name { color: #fff; }
    .table-card .current-info .time { color: #888; }
    .table-card .time-remaining {
      position: absolute;
      top: 15px;
      right: 15px;
      font-size: 12px;
      padding: 3px 8px;
      border-radius: 4px;
      background: #333;
    }
    .time-remaining.warning { background: #ff9f1c; color: #1a1a2e; }
    .time-remaining.danger { background: #ff6b6b; color: #fff; }

    /* Timeline */
    .timeline-section {
      background: #252542;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 30px;
    }
    .timeline-section h2 {
      font-size: 18px;
      margin-bottom: 20px;
      color: #4ecdc4;
    }
    .timeline-header {
      display: flex;
      border-bottom: 1px solid #333;
      padding-bottom: 10px;
      margin-bottom: 10px;
    }
    .timeline-header .table-col {
      width: 100px;
      flex-shrink: 0;
      font-size: 13px;
      color: #888;
    }
    .timeline-header .hours {
      flex: 1;
      display: flex;
      position: relative;
    }
    .timeline-header .hour-mark {
      flex: 1;
      text-align: center;
      font-size: 11px;
      color: #666;
    }
    .timeline-row {
      display: flex;
      margin-bottom: 8px;
      align-items: center;
    }
    .timeline-row .table-col {
      width: 100px;
      flex-shrink: 0;
      font-size: 13px;
      color: #fff;
    }
    .timeline-row .timeline-bar {
      flex: 1;
      height: 30px;
      background: #1a1a2e;
      border-radius: 4px;
      position: relative;
      overflow: hidden;
    }
    .timeline-block {
      position: absolute;
      height: 100%;
      border-radius: 4px;
      display: flex;
      align-items: center;
      padding: 0 8px;
      font-size: 11px;
      color: #fff;
      overflow: hidden;
      white-space: nowrap;
      cursor: pointer;
    }
    .timeline-block.confirmed { background: #3d5a80; }
    .timeline-block.seated { background: #4ecdc4; color: #1a1a2e; }
    .timeline-block.completed { background: #555; }
    .current-time-line {
      position: absolute;
      top: 0;
      bottom: 0;
      width: 2px;
      background: #ff6b6b;
      z-index: 10;
    }

    /* Reservations List */
    .reservations-section {
      background: #252542;
      border-radius: 12px;
      padding: 20px;
    }
    .reservations-section h2 {
      font-size: 18px;
      margin-bottom: 20px;
      color: #4ecdc4;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .reservations-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    .reservation-item {
      display: flex;
      align-items: center;
      padding: 15px;
      background: #1a1a2e;
      border-radius: 8px;
      gap: 15px;
    }
    .reservation-item .time {
      font-size: 16px;
      font-weight: bold;
      color: #4ecdc4;
      width: 80px;
    }
    .reservation-item .info {
      flex: 1;
    }
    .reservation-item .name {
      font-size: 15px;
      color: #fff;
    }
    .reservation-item .details {
      font-size: 13px;
      color: #888;
    }
    .reservation-item .table-badge {
      padding: 5px 12px;
      background: #333;
      border-radius: 20px;
      font-size: 13px;
    }
    .reservation-item .actions {
      display: flex;
      gap: 8px;
    }

    /* Buttons */
    .btn {
      padding: 8px 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 13px;
      transition: all 0.2s;
    }
    .btn-primary { background: #4ecdc4; color: #1a1a2e; }
    .btn-primary:hover { background: #3dbdb5; }
    .btn-success { background: #2ecc71; color: #fff; }
    .btn-success:hover { background: #27ae60; }
    .btn-warning { background: #ff9f1c; color: #1a1a2e; }
    .btn-warning:hover { background: #e8910a; }
    .btn-danger { background: #ff6b6b; color: #fff; }
    .btn-danger:hover { background: #ee5a5a; }
    .btn-secondary { background: #444; color: #fff; }
    .btn-secondary:hover { background: #555; }
    .btn-sm { padding: 5px 10px; font-size: 12px; }

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
      max-height: 90vh;
      overflow-y: auto;
    }
    .modal-content h2 {
      margin-bottom: 20px;
      color: #4ecdc4;
    }
    .modal-actions {
      display: flex;
      gap: 10px;
      justify-content: flex-end;
      margin-top: 20px;
    }
    .form-group {
      margin-bottom: 15px;
    }
    .form-group label {
      display: block;
      margin-bottom: 5px;
      color: #aaa;
      font-size: 14px;
    }
    .form-group input, .form-group select {
      width: 100%;
      padding: 10px 12px;
      background: #1a1a2e;
      border: 1px solid #333;
      border-radius: 6px;
      color: #fff;
      font-size: 14px;
    }

    /* Card Actions */
    .card-actions {
      margin-top: 10px;
      padding-top: 10px;
      border-top: 1px solid #333;
      display: flex;
      gap: 8px;
    }

    /* Button Extra Small */
    .btn-xs { padding: 4px 8px; font-size: 11px; }

    /* Time Slots Grid in Modal */
    .modal-content.wide {
      max-width: 700px;
    }
    .time-block-header {
      font-size: 14px;
      font-weight: bold;
      color: #4ecdc4;
      margin: 15px 0 10px;
      padding-bottom: 5px;
      border-bottom: 1px solid #333;
    }
    .time-block-header:first-child {
      margin-top: 0;
    }
    .time-slots-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
      gap: 10px;
      margin-bottom: 20px;
    }
    .time-slot {
      background: #1a1a2e;
      border-radius: 8px;
      padding: 12px;
      border-left: 3px solid #555;
      transition: all 0.2s;
    }
    .time-slot.available { border-left-color: #4ecdc4; }
    .time-slot.reserved { border-left-color: #ff9f1c; }
    .time-slot.occupied { border-left-color: #ff6b6b; }
    .time-slot.completed { border-left-color: #555; opacity: 0.6; }
    .time-slot.past { opacity: 0.4; }
    .time-slot.current {
      box-shadow: 0 0 0 2px #4ecdc4;
    }
    .time-slot .slot-time {
      font-size: 13px;
      font-weight: bold;
      color: #fff;
      margin-bottom: 5px;
    }
    .time-slot .slot-status {
      margin-bottom: 5px;
    }
    .time-slot .slot-status .status-badge {
      font-size: 10px;
      padding: 2px 8px;
    }
    .time-slot .slot-guest {
      font-size: 12px;
      color: #ccc;
      margin-bottom: 8px;
    }
    .time-slot .slot-guest strong {
      color: #fff;
    }
    .time-slot .slot-actions {
      display: flex;
      gap: 5px;
      flex-wrap: wrap;
    }
    .status-badge.completed {
      background: #555;
      color: #aaa;
    }
    .status-badge.passed {
      background: #444;
      color: #777;
    }
    .status-badge.blocked {
      background: #8b5cf6;
      color: #fff;
    }
    .status-badge.limited {
      background: #f59e0b;
      color: #1a1a2e;
    }
    .time-slot.passed { border-left-color: #444; }
    .time-slot.blocked { border-left-color: #8b5cf6; opacity: 0.7; }
    .time-slot.limited { border-left-color: #f59e0b; }
    .table-card.blocked { border-left: 4px solid #8b5cf6; }
    .table-card.limited { border-left: 4px solid #f59e0b; }

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

    /* Quick Actions */
    .quick-actions {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1 data-i18n="title">Table Dashboard</h1>
      <div class="header-right">
        <div class="lang-switch">
          <button class="lang-btn" data-lang="en">EN</button>
          <button class="lang-btn active" data-lang="ko">KO</button>
        </div>
        <nav class="nav-links">
          <a href="admin.php" data-i18n="nav.settings">Settings</a>
          <a href="dashboard.php" class="active" data-i18n="nav.dashboard">Dashboard</a>
          <a href="reservation.php" data-i18n="nav.reservation">Reservation</a>
          <a href="orders-dashboard.php" data-i18n="nav.orders">Orders</a>
        </nav>
      </div>
    </header>

    <!-- Date Picker -->
    <div class="date-picker">
      <button onclick="changeDate(-1)">&lt;</button>
      <input type="date" id="selected-date" onchange="loadDayData()">
      <button onclick="changeDate(1)">&gt;</button>
      <button onclick="setToday()" data-i18n="dashboard.today">Today</button>
      <span class="date-info" id="date-info"></span>
    </div>

    <!-- Stats -->
    <div class="stats-row">
      <div class="stat-card">
        <div class="number" id="stat-available">0</div>
        <div class="label" data-i18n="dashboard.available">Available</div>
      </div>
      <div class="stat-card warning">
        <div class="number" id="stat-reserved">0</div>
        <div class="label" data-i18n="dashboard.reserved">Reserved</div>
      </div>
      <div class="stat-card danger">
        <div class="number" id="stat-occupied">0</div>
        <div class="label" data-i18n="dashboard.occupied">Occupied</div>
      </div>
      <div class="stat-card">
        <div class="number" id="stat-total">0</div>
        <div class="label" data-i18n="dashboard.totalRes">Today's Reservations</div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
      <button class="btn btn-primary" onclick="openQuickReservation()" data-i18n="dashboard.quickRes">+ Quick Reservation</button>
      <button class="btn btn-secondary" onclick="refreshData()" data-i18n="dashboard.refresh">Refresh</button>
    </div>

    <!-- Table Grid -->
    <div class="table-grid" id="table-grid">
      <!-- Populated by JS -->
    </div>

    <!-- Timeline -->
    <div class="timeline-section">
      <h2 data-i18n="dashboard.timeline">Timeline</h2>
      <div class="timeline-header">
        <div class="table-col" data-i18n="dashboard.table">Table</div>
        <div class="hours" id="timeline-hours">
          <!-- Populated by JS -->
        </div>
      </div>
      <div id="timeline-rows">
        <!-- Populated by JS -->
      </div>
    </div>

    <!-- Upcoming Reservations -->
    <div class="reservations-section">
      <h2>
        <span data-i18n="dashboard.upcoming">Upcoming Reservations</span>
      </h2>
      <div class="reservations-list" id="reservations-list">
        <!-- Populated by JS -->
      </div>
    </div>
  </div>

  <!-- Table Detail Modal -->
  <div class="modal" id="table-modal">
    <div class="modal-content wide">
      <h2 id="modal-table-name">Table 1</h2>
      <div id="modal-table-status"></div>
      <div id="modal-current-reservation"></div>
      <div id="modal-upcoming-reservations"></div>
      <div class="modal-actions" id="modal-actions">
        <!-- Dynamic buttons -->
      </div>
    </div>
  </div>

  <!-- Quick Reservation Modal -->
  <div class="modal" id="quick-res-modal">
    <div class="modal-content">
      <h2 data-i18n="dashboard.quickRes">Quick Reservation</h2>
      <div class="form-group">
        <label data-i18n="reservation.name">Name</label>
        <input type="text" id="quick-name">
      </div>
      <div class="form-group">
        <label data-i18n="reservation.phone">Phone</label>
        <input type="tel" id="quick-phone" placeholder="+44 7123 456789">
      </div>
      <div class="form-group">
        <label data-i18n="reservation.guests">Guests</label>
        <input type="number" id="quick-guests" value="2" min="1" max="10">
      </div>
      <div class="form-group">
        <label data-i18n="reservation.table">Table</label>
        <select id="quick-table"></select>
      </div>
      <div class="form-group">
        <label data-i18n="reservation.time">Time</label>
        <input type="time" id="quick-time">
      </div>
      <p style="color: #888; font-size: 13px; margin-top: 10px;" data-i18n="reservation.durationAuto">Duration is auto-assigned based on guest count.</p>
      <div class="modal-actions">
        <button class="btn btn-secondary" onclick="closeQuickReservation()" data-i18n="common.cancel">Cancel</button>
        <button class="btn btn-primary" onclick="saveQuickReservation()" data-i18n="common.save">Save</button>
      </div>
    </div>
  </div>

  <!-- Toast -->
  <div class="toast" id="toast"></div>

  <!-- Confirm Modal -->
  <div class="modal" id="confirm-modal">
    <div class="modal-content" style="max-width: 400px; text-align: center;">
      <h2 id="confirm-title" style="margin-bottom: 15px;">Confirm</h2>
      <p id="confirm-message" style="color: #ccc; margin-bottom: 25px;"></p>
      <div class="modal-actions" style="justify-content: center;">
        <button class="btn btn-secondary" id="confirm-cancel-btn">Cancel</button>
        <button class="btn btn-danger" id="confirm-ok-btn">Confirm</button>
      </div>
    </div>
  </div>

  <script>
    // ==================== i18n ====================
    const i18n = {
      en: {
        title: 'Table Dashboard',
        nav: {
          settings: 'Settings',
          dashboard: 'Dashboard',
          reservation: 'Reservation',
          orders: 'Orders'
        },
        dashboard: {
          today: 'Today',
          available: 'Available',
          reserved: 'Reserved',
          occupied: 'Occupied',
          totalRes: "Today's Reservations",
          quickRes: '+ Quick Reservation',
          refresh: 'Refresh',
          timeline: 'Timeline',
          table: 'Table',
          upcoming: 'Upcoming Reservations',
          noReservations: 'No reservations',
          closed: 'Closed',
          guests: 'guests',
          remaining: 'remaining',
          terminateConfirm: 'Terminate this session?',
          terminated: 'Session terminated',
          seated: 'Marked as seated',
          seatNow: 'Seat Now',
          terminate: 'Terminate',
          cancel: 'Cancel Reservation',
          clearTable: 'Clear Table',
          clearConfirm: 'Clear this table and cancel the reservation?',
          tableCleared: 'Table cleared',
          reserve: 'Reserve',
          cancelConfirm: 'Are you sure you want to cancel this reservation?'
        },
        reservation: {
          name: 'Name',
          phone: 'Phone',
          guests: 'Guests',
          table: 'Table',
          time: 'Time',
          duration: 'Duration',
          created: 'Reservation created',
          durationAuto: 'Duration is auto-assigned based on guest count.'
        },
        common: {
          save: 'Save',
          cancel: 'Cancel',
          close: 'Close',
          confirm: 'Confirm'
        },
        status: {
          available: 'Available',
          occupied: 'Occupied',
          reserved: 'Reserved',
          completed: 'Completed',
          passed: 'Passed',
          blocked: 'Blocked',
          limited: 'Limited'
        }
      },
      ko: {
        title: '테이블 대시보드',
        nav: {
          settings: '설정',
          dashboard: '대시보드',
          reservation: '예약',
          orders: '주문'
        },
        dashboard: {
          today: '오늘',
          available: '이용 가능',
          reserved: '예약됨',
          occupied: '사용 중',
          totalRes: '오늘 예약',
          quickRes: '+ 빠른 예약',
          refresh: '새로고침',
          timeline: '타임라인',
          table: '테이블',
          upcoming: '예정된 예약',
          noReservations: '예약 없음',
          closed: '휴무',
          guests: '명',
          remaining: '남음',
          terminateConfirm: '이 세션을 종료하시겠습니까?',
          terminated: '세션이 종료되었습니다',
          seated: '착석 처리되었습니다',
          seatNow: '착석',
          terminate: '종료',
          cancel: '예약 취소',
          clearTable: '테이블 비우기',
          clearConfirm: '테이블을 비우고 예약을 취소하시겠습니까?',
          tableCleared: '테이블이 비워졌습니다',
          reserve: '예약',
          cancelConfirm: '이 예약을 취소하시겠습니까?'
        },
        reservation: {
          name: '이름',
          phone: '전화번호',
          guests: '인원',
          table: '테이블',
          time: '시간',
          duration: '식사 시간',
          created: '예약이 생성되었습니다',
          durationAuto: '식사 시간은 인원에 따라 자동 배정됩니다.'
        },
        common: {
          save: '저장',
          cancel: '취소',
          close: '닫기',
          confirm: '확인'
        },
        status: {
          available: '이용 가능',
          occupied: '사용 중',
          reserved: '예약됨',
          completed: '완료됨',
          passed: '종료됨',
          blocked: '사용 불가',
          limited: '일부 가능'
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
      document.documentElement.lang = currentLang;
      document.querySelectorAll('.lang-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.lang === currentLang);
      });
      renderAll();
    }

    function setLanguage(lang) {
      currentLang = lang;
      localStorage.setItem('yanji-lang', lang);
      applyTranslations();
    }

    // ==================== Data ====================
    const API_BASE = window.YANJI_CONFIG?.API_BASE || 'http://localhost:3000';

    let selectedDate = new Date().toISOString().split('T')[0];
    let tables = [];
    let reservations = [];
    let operationHours = {
      open: '11:30',
      close: '22:00',
      blocks: [
        { name: 'Lunch', start: '11:30', end: '14:30' },
        { name: 'Dinner', start: '17:00', end: '22:00' }
      ]
    };

    // Track currently open modal
    let currentModalTableId = null;

    // ==================== Confirm Modal ====================
    function showConfirm(message, title = null) {
      return new Promise((resolve) => {
        const modal = document.getElementById('confirm-modal');
        const titleEl = document.getElementById('confirm-title');
        const messageEl = document.getElementById('confirm-message');
        const okBtn = document.getElementById('confirm-ok-btn');
        const cancelBtn = document.getElementById('confirm-cancel-btn');

        titleEl.textContent = title || t('common.confirm') || 'Confirm';
        messageEl.textContent = message;
        okBtn.textContent = t('common.confirm') || 'Confirm';
        cancelBtn.textContent = t('common.cancel') || 'Cancel';

        modal.classList.add('active');

        const cleanup = () => {
          modal.classList.remove('active');
          okBtn.removeEventListener('click', onOk);
          cancelBtn.removeEventListener('click', onCancel);
        };

        const onOk = () => {
          cleanup();
          resolve(true);
        };

        const onCancel = () => {
          cleanup();
          resolve(false);
        };

        okBtn.addEventListener('click', onOk);
        cancelBtn.addEventListener('click', onCancel);
      });
    }

    // ==================== API Functions ====================
    async function fetchConfig() {
      try {
        const res = await fetch(`${API_BASE}/config`);
        if (!res.ok) throw new Error('Failed to fetch config');
        const data = await res.json();

        if (data.tables && data.tables.length > 0) {
          // Flatten tables (include parts AND parent as separate entries for display)
          tables = [];
          data.tables.forEach(table => {
            if (table.splittable && table.parts) {
              // Add the FULL room (parent) as a bookable option
              tables.push({
                id: table.id,
                name: table.name + ' (Full)',
                nameKo: (table.nameKo || table.name) + ' (전체)',
                minGuests: table.minGuests,
                maxGuests: table.maxGuests,
                location: table.location,
                isSplittable: true,
                isFullRoom: true,
                parts: table.parts.map(p => p.id)
              });
              // Add parts as individual tables
              table.parts.forEach(part => {
                tables.push({
                  id: part.id,
                  name: part.name,
                  nameKo: part.nameKo || part.name,
                  minGuests: part.minGuests,
                  maxGuests: part.maxGuests,
                  location: table.location,
                  parentId: table.id,
                  isPart: true
                });
              });
            } else {
              tables.push({
                id: table.id,
                name: table.name,
                nameKo: table.nameKo || table.name,
                minGuests: table.minGuests,
                maxGuests: table.maxGuests,
                location: table.location
              });
            }
          });
        }

        if (data.operationHours) {
          operationHours = data.operationHours;
        }
      } catch (e) {
        console.error('Failed to fetch config:', e);
      }
    }

    // Request debouncing to prevent rate limiting
    let lastFetchTime = {};
    const FETCH_DEBOUNCE_MS = 60000; // Wait at least 60 seconds (1 minute) between fetches for same date

    async function fetchReservations(date) {
      try {
        // Check if we recently fetched this date
        const now = Date.now();
        if (lastFetchTime[date] && now - lastFetchTime[date] < FETCH_DEBOUNCE_MS) {
          console.log(`Debouncing fetch for ${date} (fetched ${now - lastFetchTime[date]}ms ago)`);
          return;
        }

        const res = await fetch(`${API_BASE}/reservations?date=${date}`);
        
        if (!res.ok) {
          if (res.status === 429) {
            console.warn('Rate limited (429). Waiting 5 seconds before retry...');
            showToast('Server busy, retrying...', true);
            // Don't update lastFetchTime to allow retry
            return;
          }
          const errorData = await res.text();
          throw new Error(`Failed to fetch reservations: ${res.status} - ${errorData}`);
        }
        
        reservations = await res.json();
        lastFetchTime[date] = Date.now();
        console.log(`Fetched ${reservations.length} reservations for ${date}`);
      } catch (e) {
        console.error('Failed to fetch reservations:', e);
        reservations = [];
      }
    }

    async function apiSeatReservation(resId) {
      try {
        const res = await fetch(`${API_BASE}/reservations/${resId}/seat`, {
          method: 'POST'
        });
        if (!res.ok) {
          const errorData = await res.text();
          console.error('API Error Response:', errorData);
          throw new Error(`Failed to seat reservation: ${res.status} - ${errorData}`);
        }
        return true;
      } catch (e) {
        console.error('Failed to seat reservation:', e);
        return false;
      }
    }

    async function apiCompleteReservation(resId) {
      try {
        const res = await fetch(`${API_BASE}/reservations/${resId}/complete`, {
          method: 'POST'
        });
        if (!res.ok) {
          const errorData = await res.text();
          console.error('API Error Response:', errorData);
          throw new Error(`Failed to complete reservation: ${res.status} - ${errorData}`);
        }
        return true;
      } catch (e) {
        console.error('Failed to complete reservation:', e);
        return false;
      }
    }

    async function apiCancelReservation(resId) {
      try {
        const res = await fetch(`${API_BASE}/reservations/${resId}`, {
          method: 'DELETE'
        });
        if (!res.ok) {
          const errorData = await res.text();
          console.error('API Error Response:', errorData);
          throw new Error(`Failed to cancel reservation: ${res.status} - ${errorData}`);
        }
        return true;
      } catch (e) {
        console.error('Failed to cancel reservation:', e);
        return false;
      }
    }

    async function apiCreateReservation(data) {
      try {
        const res = await fetch(`${API_BASE}/reservations`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(data)
        });
        if (!res.ok) {
          const errorData = await res.text();
          console.error('API Error Response:', errorData);
          throw new Error(`Failed to create reservation: ${res.status} - ${errorData}`);
        }
        return await res.json();
      } catch (e) {
        console.error('Failed to create reservation:', e);
        return null;
      }
    }

    // ==================== Rendering ====================
    function renderAll() {
      renderStats();
      renderTableGrid();
      renderTimeline();
      renderReservationsList();
    }

    function renderStats() {
      const dayRes = reservations.filter(r => r.date === selectedDate && r.status !== 'cancelled');
      const occupied = dayRes.filter(r => r.status === 'seated').length;
      const reserved = dayRes.filter(r => r.status === 'confirmed').length;
      const available = tables.length - occupied;

      document.getElementById('stat-available').textContent = available;
      document.getElementById('stat-reserved').textContent = reserved;
      document.getElementById('stat-occupied').textContent = occupied;
      document.getElementById('stat-total').textContent = dayRes.length;
    }

    function renderTableGrid() {
      const grid = document.getElementById('table-grid');
      grid.innerHTML = '';
      const currentTime = getCurrentTime();

      tables.forEach(table => {
        const status = getTableStatus(table.id);
        const currentRes = getCurrentReservation(table.id);
        const displayName = currentLang === 'ko' ? (table.nameKo || table.name) : table.name;
        const guestLabel = currentLang === 'ko' ? '명' : ' guests';

        const card = document.createElement('div');
        card.className = `table-card ${status}`;

        // Quick actions on the card (for current slot only)
        let quickActions = '';
        let currentInfo = '';
        let timeRemaining = '';

        if (currentRes) {
          const isCurrentlySeated = currentRes.status === 'seated' && currentRes.startTime <= currentTime && currentRes.endTime > currentTime;
          const isUpcoming = currentRes.status === 'confirmed';

          currentInfo = `
            <div class="current-info">
              <div class="name">${currentRes.customerName}</div>
              <div class="time">${currentRes.startTime} - ${currentRes.endTime} (${currentRes.guestCount}${guestLabel})</div>
            </div>
          `;

          if (isCurrentlySeated) {
            const remaining = getTimeRemaining(currentRes.endTime);
            if (remaining !== null) {
              const remainingClass = remaining <= 10 ? 'danger' : remaining <= 20 ? 'warning' : '';
              timeRemaining = `<div class="time-remaining ${remainingClass}">${remaining}min ${t('dashboard.remaining')}</div>`;
            }
            quickActions = `
              <div class="card-actions" onclick="event.stopPropagation()">
                <button class="btn btn-warning btn-sm" onclick="clearTable('${currentRes.reservationId}')">${t('dashboard.clearTable')}</button>
              </div>
            `;
          } else if (isUpcoming) {
            quickActions = `
              <div class="card-actions" onclick="event.stopPropagation()">
                <button class="btn btn-success btn-sm" onclick="seatReservation('${currentRes.reservationId}')">${t('dashboard.seatNow')}</button>
              </div>
            `;
          }
        }

        card.innerHTML = `
          ${timeRemaining}
          <div class="table-name">${displayName}</div>
          <div class="table-capacity">${table.minGuests}-${table.maxGuests}${guestLabel}</div>
          <span class="status-badge ${status}">${t('status.' + status)}</span>
          ${currentInfo}
          ${quickActions}
        `;

        // Click on card (not on buttons) opens modal
        card.onclick = (e) => {
          if (!e.target.closest('.card-actions')) {
            openTableModal(table.id);
          }
        };

        grid.appendChild(card);
      });
    }

    function renderTimeline() {
      // Render hours
      const hoursContainer = document.getElementById('timeline-hours');
      hoursContainer.innerHTML = '';

      const startHour = 11;
      const endHour = 23;

      for (let h = startHour; h <= endHour; h++) {
        const hourDiv = document.createElement('div');
        hourDiv.className = 'hour-mark';
        hourDiv.textContent = `${h}:00`;
        hoursContainer.appendChild(hourDiv);
      }

      // Render rows
      const rowsContainer = document.getElementById('timeline-rows');
      rowsContainer.innerHTML = '';

      const totalMinutes = (endHour - startHour) * 60;

      tables.forEach(table => {
        const displayName = currentLang === 'ko' ? (table.nameKo || table.name) : table.name;
        const tableRes = reservations.filter(r => r.tableId === table.id && r.date === selectedDate && r.status !== 'cancelled');

        const row = document.createElement('div');
        row.className = 'timeline-row';

        let blocksHtml = '';
        tableRes.forEach(res => {
          const startMin = timeToMinutes(res.startTime) - startHour * 60;
          const endMin = timeToMinutes(res.endTime) - startHour * 60;
          const left = (startMin / totalMinutes) * 100;
          const width = ((endMin - startMin) / totalMinutes) * 100;

          blocksHtml += `
            <div class="timeline-block ${res.status}"
                 style="left: ${left}%; width: ${width}%;"
                 onclick="openReservationDetail('${res.reservationId}')"
                 title="${res.customerName} (${res.guestCount})">
              ${res.customerName}
            </div>
          `;
        });

        // Current time line
        const now = new Date();
        if (selectedDate === now.toISOString().split('T')[0]) {
          const nowMin = now.getHours() * 60 + now.getMinutes() - startHour * 60;
          if (nowMin >= 0 && nowMin <= totalMinutes) {
            const nowLeft = (nowMin / totalMinutes) * 100;
            blocksHtml += `<div class="current-time-line" style="left: ${nowLeft}%;"></div>`;
          }
        }

        row.innerHTML = `
          <div class="table-col">${displayName}</div>
          <div class="timeline-bar">${blocksHtml}</div>
        `;

        rowsContainer.appendChild(row);
      });
    }

    function renderReservationsList() {
      const list = document.getElementById('reservations-list');
      const dayRes = reservations
        .filter(r => r.date === selectedDate && r.status === 'confirmed')
        .sort((a, b) => a.startTime.localeCompare(b.startTime));

      if (dayRes.length === 0) {
        list.innerHTML = `<div style="color: #888; text-align: center; padding: 20px;">${t('dashboard.noReservations')}</div>`;
        return;
      }

      const guestLabel = currentLang === 'ko' ? '명' : ' guests';

      list.innerHTML = dayRes.map(res => {
        const table = tables.find(t => t.id === res.tableId);
        const tableName = currentLang === 'ko' ? (table?.nameKo || table?.name || res.tableId) : (table?.name || res.tableId);

        return `
          <div class="reservation-item">
            <div class="time">${res.startTime}</div>
            <div class="info">
              <div class="name">${res.customerName}</div>
              <div class="details">${res.guestCount}${guestLabel} · ${res.startTime} - ${res.endTime}</div>
            </div>
            <div class="table-badge">${tableName}</div>
            <div class="actions">
              <button class="btn btn-success btn-sm" onclick="seatReservation('${res.reservationId}')">${t('dashboard.seatNow')}</button>
              <button class="btn btn-danger btn-sm" onclick="cancelReservation('${res.reservationId}')">${t('common.cancel')}</button>
            </div>
          </div>
        `;
      }).join('');
    }

    // ==================== Helpers ====================
    function getCurrentTime() {
      const now = new Date();
      return `${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}`;
    }

    function timeToMinutes(time) {
      const [h, m] = time.split(':').map(Number);
      return h * 60 + m;
    }

    function minutesToTime(minutes) {
      const h = Math.floor(minutes / 60);
      const m = minutes % 60;
      return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}`;
    }

    // Helper: Check if tableId is a part of a splittable table (e.g., "8a" -> "8")
    function getParentTableId(tableId) {
      const match = tableId.match(/^(\d+)[a-z]$/i);
      return match ? match[1] : null;
    }

    // Helper: Check if tableId is a full splittable room
    function isFullRoom(tableId) {
      const table = tables.find(t => t.id === tableId);
      return table?.isFullRoom === true;
    }

    // Helper: Get parts of a full room
    function getPartsOfRoom(tableId) {
      const table = tables.find(t => t.id === tableId);
      return table?.parts || [];
    }

    // Helper: Check if two tables conflict (for splittable logic)
    function tablesConflict(tableId1, tableId2) {
      if (tableId1 === tableId2) return true;

      const parent1 = getParentTableId(tableId1);
      const parent2 = getParentTableId(tableId2);

      // Part vs Full room: 8a conflicts with 8
      if (parent1 && parent1 === tableId2) return true;
      if (parent2 && parent2 === tableId1) return true;

      // Parts do NOT conflict with each other (8a vs 8b = no conflict)
      return false;
    }

    // Get status of a specific time slot for a table
    function getSlotStatus(tableId, slotStart, slotEnd) {
      const currentTime = getCurrentTime();

      // Find direct reservation for this table
      const directRes = reservations.find(r =>
        r.tableId === tableId &&
        r.date === selectedDate &&
        r.status !== 'cancelled' &&
        r.startTime < slotEnd &&
        r.endTime > slotStart
      );

      if (directRes) {
        if (directRes.status === 'completed') {
          return { status: 'completed', reservation: directRes };
        }
        if (directRes.status === 'seated') {
          return { status: 'occupied', reservation: directRes };
        }
        return { status: 'reserved', reservation: directRes };
      }

      // Check for blocking due to splittable table rules
      const table = tables.find(t => t.id === tableId);

      if (table?.isFullRoom) {
        // Full room: blocked if ANY of its parts has a reservation
        const parts = table.parts || [];
        const blockingRes = reservations.find(r =>
          parts.includes(r.tableId) &&
          r.date === selectedDate &&
          r.status !== 'cancelled' &&
          r.startTime < slotEnd &&
          r.endTime > slotStart
        );
        if (blockingRes) {
          return { status: 'blocked', reservation: blockingRes, blockedBy: blockingRes.tableId };
        }
      } else if (table?.isPart) {
        // Part: blocked if the parent (full room) has a reservation
        const parentId = table.parentId;
        const blockingRes = reservations.find(r =>
          r.tableId === parentId &&
          r.date === selectedDate &&
          r.status !== 'cancelled' &&
          r.startTime < slotEnd &&
          r.endTime > slotStart
        );
        if (blockingRes) {
          return { status: 'blocked', reservation: blockingRes, blockedBy: parentId };
        }
      }

      return { status: 'available', reservation: null };
    }

    // Get current time slot (which 30-min block are we in?)
    function getCurrentSlotTime() {
      const now = new Date();
      const minutes = now.getHours() * 60 + now.getMinutes();
      const slotStart = Math.floor(minutes / 30) * 30;
      return minutesToTime(slotStart);
    }

    // Get table status based on CURRENT time slot only
    function getTableStatus(tableId) {
      const currentTime = getCurrentTime();
      const currentTimeMin = timeToMinutes(currentTime);
      const nearTermEnd = minutesToTime(currentTimeMin + 30); // Next 30 min window
      const table = tables.find(t => t.id === tableId);

      // Check if there's any reservation overlapping with current time
      const activeRes = reservations.find(r =>
        r.tableId === tableId &&
        r.date === selectedDate &&
        r.status === 'seated' &&
        r.startTime <= currentTime &&
        r.endTime > currentTime
      );

      if (activeRes) return 'occupied';

      // Check for upcoming reservation within next 30 minutes
      const upcomingSoon = reservations.find(r =>
        r.tableId === tableId &&
        r.date === selectedDate &&
        r.status === 'confirmed' &&
        r.startTime <= nearTermEnd &&
        r.startTime >= currentTime
      );

      if (upcomingSoon) return 'reserved';

      // Check for blocking due to splittable table rules (CURRENT time only)
      if (table?.isFullRoom) {
        // Full room: blocked if ANY part has a reservation overlapping current time window
        const parts = table.parts || [];
        const blockingRes = reservations.find(r =>
          parts.includes(r.tableId) &&
          r.date === selectedDate &&
          (r.status === 'seated' || r.status === 'confirmed') &&
          r.startTime < nearTermEnd &&
          r.endTime > currentTime
        );
        if (blockingRes) return 'blocked';
      } else if (table?.isPart) {
        // Part: check if the parent (full room) has reservations
        const parentId = table.parentId;

        // Get all full room reservations for today (sorted by start time)
        const fullRoomReservations = reservations.filter(r =>
          r.tableId === parentId &&
          r.date === selectedDate &&
          (r.status === 'seated' || r.status === 'confirmed') &&
          r.endTime > currentTime
        ).sort((a, b) => timeToMinutes(a.startTime) - timeToMinutes(b.startTime));

        if (fullRoomReservations.length === 0) {
          // No full room reservations, part is available
          return 'available';
        }

        // Check if blocked NOW (overlapping current time window)
        const blockingResNow = fullRoomReservations.find(r =>
          r.startTime < nearTermEnd &&
          r.endTime > currentTime
        );

        if (blockingResNow) {
          // Blocked now - check if there are available sessions AFTER blocking ends
          // Get operation hours end time
          const lastBlock = operationHours.blocks?.[operationHours.blocks.length - 1];
          const operationEnd = lastBlock?.end || operationHours.close || '22:00';
          const operationEndMin = timeToMinutes(operationEnd);

          // Find when all consecutive blocking ends
          let blockEndMin = timeToMinutes(blockingResNow.endTime);

          // Check for continuous blocking (back-to-back reservations)
          for (const res of fullRoomReservations) {
            const resStartMin = timeToMinutes(res.startTime);
            const resEndMin = timeToMinutes(res.endTime);
            // If this reservation starts within 30 min of current block end, extend block
            if (resStartMin <= blockEndMin + 30 && resEndMin > blockEndMin) {
              blockEndMin = resEndMin;
            }
          }

          // If blocking ends before operation hours end, there's availability later
          if (blockEndMin < operationEndMin - 30) {
            return 'limited'; // Blocked now but has available sessions later
          }
          return 'blocked'; // Blocked for rest of operation hours
        }

        // Not blocked now, but has future full room reservations
        return 'limited';
      }

      return 'available';
    }

    // Get the current active/upcoming reservation for display on card
    function getCurrentReservation(tableId) {
      const currentTime = getCurrentTime();

      // First check for seated reservation
      const seatedRes = reservations.find(r =>
        r.tableId === tableId &&
        r.date === selectedDate &&
        r.status === 'seated' &&
        r.endTime > currentTime
      );

      if (seatedRes) return seatedRes;

      // Then check for next confirmed reservation
      const nextRes = reservations
        .filter(r =>
          r.tableId === tableId &&
          r.date === selectedDate &&
          r.status === 'confirmed' &&
          r.endTime > currentTime
        )
        .sort((a, b) => a.startTime.localeCompare(b.startTime))[0];

      return nextRes || null;
    }

    function getTimeRemaining(endTime) {
      const now = new Date();
      const [endH, endM] = endTime.split(':').map(Number);
      const endDate = new Date(now);
      endDate.setHours(endH, endM, 0);

      const diff = Math.floor((endDate - now) / 60000);
      return diff > 0 ? diff : null;
    }

    // ==================== Actions ====================
    function changeDate(delta) {
      const date = new Date(selectedDate);
      date.setDate(date.getDate() + delta);
      selectedDate = date.toISOString().split('T')[0];
      document.getElementById('selected-date').value = selectedDate;
      loadDayData();
    }

    function setToday() {
      selectedDate = new Date().toISOString().split('T')[0];
      document.getElementById('selected-date').value = selectedDate;
      loadDayData();
    }

    async function loadDayData() {
      selectedDate = document.getElementById('selected-date').value;
      await fetchReservations(selectedDate);
      renderAll();
    }

    async function refreshData() {
      await fetchReservations(selectedDate);
      renderAll();
      showToast(t('dashboard.refresh'));
    }

    function openTableModal(tableId) {
      const table = tables.find(t => t.id === tableId);
      if (!table) return;

      currentModalTableId = tableId; // Track which table modal is open

      const displayName = currentLang === 'ko' ? (table.nameKo || table.name) : table.name;
      const currentTime = getCurrentTime();
      const currentSlotStart = getCurrentSlotTime();
      const guestLabel = currentLang === 'ko' ? '명' : ' guests';

      document.getElementById('modal-table-name').textContent = displayName;

      // Generate all time slots grouped by block
      const blocks = operationHours?.blocks || [
        { name: 'Lunch', start: '11:30', end: '14:30' },
        { name: 'Dinner', start: '17:00', end: '22:00' }
      ];

      let slotsHtml = '';

      blocks.forEach(block => {
        const blockName = currentLang === 'ko'
          ? (block.name === 'Lunch' ? '점심' : block.name === 'Dinner' ? '저녁' : block.name)
          : block.name;

        slotsHtml += `<div class="time-block-header">${blockName} (${block.start} - ${block.end})</div>`;
        slotsHtml += '<div class="time-slots-grid">';

        const startMin = timeToMinutes(block.start);
        const endMin = timeToMinutes(block.end);

        for (let min = startMin; min < endMin; min += 30) {
          const slotStart = minutesToTime(min);
          const slotEnd = minutesToTime(min + 90); // Default 90min session
          const slotInfo = getSlotStatus(tableId, slotStart, slotEnd);
          const isCurrentSlot = slotStart === currentSlotStart;
          const isPastSlot = slotStart < currentTime && selectedDate === new Date().toISOString().split('T')[0];

          // For past available slots, show "Passed" instead of "Available"
          let displayStatus = slotInfo.status;
          if (isPastSlot && slotInfo.status === 'available') {
            displayStatus = 'passed';
          }

          let slotClass = `time-slot ${displayStatus}`;
          if (isCurrentSlot) slotClass += ' current';
          if (isPastSlot) slotClass += ' past';

          let slotContent = `
            <div class="slot-time">${slotStart} - ${slotEnd}</div>
            <div class="slot-status">
              <span class="status-badge ${displayStatus}">${t('status.' + displayStatus)}</span>
            </div>
          `;

          let slotActions = '';
          if (slotInfo.status === 'blocked') {
            // Show which table is blocking this slot
            const blockingTable = tables.find(t => t.id === slotInfo.blockedBy);
            const blockingName = currentLang === 'ko'
              ? (blockingTable?.nameKo || slotInfo.blockedBy)
              : (blockingTable?.name || slotInfo.blockedBy);
            slotContent += `
              <div class="slot-guest" style="color: #8b5cf6;">
                <small>${currentLang === 'ko' ? '예약됨:' : 'Booked:'} ${blockingName}</small>
              </div>
            `;
            // No actions for blocked slots
          } else if (slotInfo.reservation) {
            const res = slotInfo.reservation;
            slotContent += `
              <div class="slot-guest">
                <strong>${res.customerName}</strong>
                <span>(${res.guestCount}${guestLabel})</span>
              </div>
            `;

            if (slotInfo.status === 'reserved') {
              slotActions = `
                <div class="slot-actions">
                  <button class="btn btn-success btn-xs" onclick="event.stopPropagation(); seatReservation('${res.reservationId}')">${t('dashboard.seatNow')}</button>
                  <button class="btn btn-danger btn-xs" onclick="event.stopPropagation(); cancelReservation('${res.reservationId}')">${t('common.cancel')}</button>
                </div>
              `;
            } else if (slotInfo.status === 'occupied') {
              slotActions = `
                <div class="slot-actions">
                  <button class="btn btn-warning btn-xs" onclick="event.stopPropagation(); clearTable('${res.reservationId}')">${t('dashboard.clearTable')}</button>
                </div>
              `;
            }
          } else if (!isPastSlot && displayStatus !== 'blocked') {
            slotActions = `
              <div class="slot-actions">
                <button class="btn btn-primary btn-xs" onclick="event.stopPropagation(); openQuickReservationForSlot('${tableId}', '${slotStart}')">${t('dashboard.reserve')}</button>
              </div>
            `;
          }

          slotsHtml += `
            <div class="${slotClass}">
              ${slotContent}
              ${slotActions}
            </div>
          `;
        }

        slotsHtml += '</div>';
      });

      document.getElementById('modal-table-status').innerHTML = slotsHtml;
      document.getElementById('modal-current-reservation').innerHTML = '';
      document.getElementById('modal-upcoming-reservations').innerHTML = '';
      document.getElementById('modal-actions').innerHTML = `
        <button class="btn btn-secondary" onclick="closeTableModal()">${t('common.close')}</button>
      `;

      document.getElementById('table-modal').classList.add('active');
    }

    function openQuickReservationForSlot(tableId, startTime) {
      closeTableModal();
      document.getElementById('quick-time').value = startTime;

      const select = document.getElementById('quick-table');
      select.innerHTML = tables.map(t => {
        const name = currentLang === 'ko' ? (t.nameKo || t.name) : t.name;
        return `<option value="${t.id}" ${t.id === tableId ? 'selected' : ''}>${name}</option>`;
      }).join('');

      document.getElementById('quick-res-modal').classList.add('active');
    }

    function closeTableModal() {
      currentModalTableId = null;
      document.getElementById('table-modal').classList.remove('active');
    }

    function refreshModalIfOpen() {
      if (currentModalTableId) {
        openTableModal(currentModalTableId);
      }
    }

    function openQuickReservation() {
      // Set current time rounded to next 30 min
      const now = new Date();
      now.setMinutes(Math.ceil(now.getMinutes() / 30) * 30);
      document.getElementById('quick-time').value = `${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}`;

      // Populate tables
      const select = document.getElementById('quick-table');
      select.innerHTML = tables.map(t => {
        const name = currentLang === 'ko' ? (t.nameKo || t.name) : t.name;
        return `<option value="${t.id}">${name}</option>`;
      }).join('');

      document.getElementById('quick-res-modal').classList.add('active');
    }

    function closeQuickReservation() {
      document.getElementById('quick-res-modal').classList.remove('active');
    }

    async function saveQuickReservation() {
      const name = document.getElementById('quick-name').value;
      const phone = document.getElementById('quick-phone').value;
      const guests = parseInt(document.getElementById('quick-guests').value);
      const tableId = document.getElementById('quick-table').value;
      const startTime = document.getElementById('quick-time').value;

      if (!name || !startTime || !tableId) {
        showToast('Please fill in all required fields', true);
        return;
      }

      if (isNaN(guests) || guests < 1 || guests > 10) {
        showToast('Guest count must be between 1 and 10', true);
        return;
      }

      if (!phone) {
        showToast('Phone number is required', true);
        return;
      }

      const result = await apiCreateReservation({
        tableId,
        customerName: name,
        customerPhone: phone,
        guestCount: guests,
        date: selectedDate,
        startTime,
        createdBy: 'admin'
      });

      if (result) {
        closeQuickReservation();
        await refreshData();
        showToast(t('reservation.created'));
      } else {
        showToast('Failed to create reservation', true);
      }
    }

    async function seatReservation(resId) {
      const success = await apiSeatReservation(resId);
      if (success) {
        await fetchReservations(selectedDate);
        renderAll();
        refreshModalIfOpen();
        showToast(t('dashboard.seated'));
      } else {
        showToast('Failed to seat reservation', true);
      }
    }

    async function terminateSession(resId) {
      const confirmed = await showConfirm(t('dashboard.terminateConfirm'), t('dashboard.terminate'));
      if (!confirmed) return;

      const success = await apiCompleteReservation(resId);
      if (success) {
        await fetchReservations(selectedDate);
        renderAll();
        refreshModalIfOpen();
        showToast(t('dashboard.terminated'));
      } else {
        showToast('Failed to terminate session', true);
      }
    }

    async function clearTable(resId) {
      const confirmed = await showConfirm(t('dashboard.clearConfirm'), t('dashboard.clearTable'));
      if (!confirmed) return;

      const success = await apiCancelReservation(resId);
      if (success) {
        await fetchReservations(selectedDate);
        renderAll();
        refreshModalIfOpen();
        showToast(t('dashboard.tableCleared'));
      } else {
        showToast('Failed to clear table', true);
      }
    }

    async function cancelReservation(resId) {
      const confirmed = await showConfirm(t('dashboard.cancelConfirm'), t('dashboard.cancel'));
      if (!confirmed) return;

      const success = await apiCancelReservation(resId);
      if (success) {
        await fetchReservations(selectedDate);
        renderAll();
        refreshModalIfOpen();
        showToast(t('common.cancel'));
      } else {
        showToast('Failed to cancel reservation', true);
      }
    }

    function openReservationDetail(resId) {
      const res = reservations.find(r => r.reservationId === resId);
      if (res) {
        openTableModal(res.tableId);
      }
    }

    // ==================== Toast ====================
    function showToast(message, isError = false) {
      const toast = document.getElementById('toast');
      toast.textContent = message;
      toast.className = 'toast active' + (isError ? ' error' : '');
      setTimeout(() => toast.classList.remove('active'), 3000);
    }

    // ==================== Event Listeners ====================
    document.querySelectorAll('.lang-btn').forEach(btn => {
      btn.addEventListener('click', () => setLanguage(btn.dataset.lang));
    });

    // Close modals on outside click
    document.querySelectorAll('.modal').forEach(modal => {
      modal.addEventListener('click', (e) => {
        if (e.target === modal) {
          modal.classList.remove('active');
        }
      });
    });

    // ==================== Initialize ====================
    document.getElementById('selected-date').value = selectedDate;

    // Load data from API
    async function init() {
      await fetchConfig();
      await fetchReservations(selectedDate);
      applyTranslations();
    }
    init();

    // Auto refresh every 60 seconds with rate limit handling
    let refreshInProgress = false;
    setInterval(async () => {
      if (refreshInProgress) {
        console.log('Refresh already in progress, skipping...');
        return;
      }
      refreshInProgress = true;
      try {
        await fetchReservations(selectedDate);
        renderAll();
      } finally {
        refreshInProgress = false;
      }
    }, 60000);
  </script>
</body>
</html>
