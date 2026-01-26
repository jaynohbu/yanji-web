<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Make a Reservation</title>
  <script src="config.js"></script>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: #1a1a2e;
      color: #eee;
      min-height: 100vh;
    }
    .container {
      max-width: 800px;
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

    /* Steps */
    .steps {
      display: flex;
      justify-content: center;
      margin-bottom: 40px;
    }
    .step {
      display: flex;
      align-items: center;
      color: #666;
    }
    .step-number {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: #333;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      margin-right: 8px;
    }
    .step.active .step-number {
      background: #4ecdc4;
      color: #1a1a2e;
    }
    .step.completed .step-number {
      background: #2ecc71;
      color: #fff;
    }
    .step-label { font-size: 14px; }
    .step.active .step-label { color: #fff; }
    .step-divider {
      width: 40px;
      height: 2px;
      background: #333;
      margin: 0 15px;
    }

    /* Form Card */
    .form-card {
      background: #252542;
      border-radius: 16px;
      padding: 30px;
      margin-bottom: 20px;
    }
    .form-card h2 {
      font-size: 20px;
      margin-bottom: 25px;
      color: #4ecdc4;
    }

    /* Date Selection */
    .date-grid {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 8px;
      margin-bottom: 20px;
    }
    .date-cell {
      padding: 12px 8px;
      background: #1a1a2e;
      border-radius: 8px;
      text-align: center;
      cursor: pointer;
      transition: all 0.2s;
    }
    .date-cell:hover:not(.disabled) {
      background: #333;
    }
    .date-cell.selected {
      background: #4ecdc4;
      color: #1a1a2e;
    }
    .date-cell.disabled {
      opacity: 0.3;
      cursor: not-allowed;
    }
    .date-cell .day-name {
      font-size: 11px;
      color: #888;
      margin-bottom: 5px;
    }
    .date-cell.selected .day-name { color: #1a1a2e; }
    .date-cell .day-num {
      font-size: 18px;
      font-weight: bold;
    }
    .date-nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }
    .date-nav button {
      padding: 8px 15px;
      background: #333;
      border: none;
      color: #fff;
      cursor: pointer;
      border-radius: 6px;
    }
    .date-nav button:hover { background: #444; }
    .date-nav .month-label {
      font-size: 16px;
      font-weight: bold;
    }

    /* Time Selection */
    .block-section {
      margin-bottom: 25px;
    }
    .block-title {
      font-size: 14px;
      color: #888;
      margin-bottom: 10px;
    }
    .time-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 10px;
    }
    @media (max-width: 500px) {
      .time-grid { grid-template-columns: repeat(3, 1fr); }
    }
    .time-slot {
      padding: 12px;
      background: #1a1a2e;
      border-radius: 8px;
      text-align: center;
      cursor: pointer;
      transition: all 0.2s;
      font-size: 15px;
    }
    .time-slot:hover:not(.disabled) {
      background: #333;
    }
    .time-slot.selected {
      background: #4ecdc4;
      color: #1a1a2e;
    }
    .time-slot.disabled {
      opacity: 0.3;
      cursor: not-allowed;
    }
    .time-slot .availability {
      font-size: 11px;
      color: #888;
      margin-top: 3px;
    }
    .time-slot.selected .availability { color: #1a1a2e; }
    .time-slot.limited .availability { color: #f39c12; }

    /* Guest Count */
    .guest-selector {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 20px;
      padding: 20px;
    }
    .guest-btn {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: #333;
      border: none;
      color: #fff;
      font-size: 24px;
      cursor: pointer;
      transition: all 0.2s;
    }
    .guest-btn:hover { background: #444; }
    .guest-btn:disabled {
      opacity: 0.3;
      cursor: not-allowed;
    }
    .guest-count {
      font-size: 48px;
      font-weight: bold;
      color: #4ecdc4;
      min-width: 80px;
      text-align: center;
    }
    .guest-label {
      font-size: 14px;
      color: #888;
      text-align: center;
      margin-top: 10px;
    }

    /* Contact Form */
    .form-group {
      margin-bottom: 20px;
    }
    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: #aaa;
      font-size: 14px;
    }
    .form-group input, .form-group textarea {
      width: 100%;
      padding: 14px 16px;
      background: #1a1a2e;
      border: 1px solid #333;
      border-radius: 8px;
      color: #fff;
      font-size: 16px;
    }
    .form-group input:focus, .form-group textarea:focus {
      outline: none;
      border-color: #4ecdc4;
    }
    .form-group textarea {
      resize: vertical;
      min-height: 80px;
    }
    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
    }
    @media (max-width: 500px) {
      .form-row { grid-template-columns: 1fr; }
    }

    /* Summary */
    .summary-card {
      background: #1a1a2e;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 20px;
    }
    .summary-row {
      display: flex;
      justify-content: space-between;
      padding: 10px 0;
      border-bottom: 1px solid #333;
    }
    .summary-row:last-child { border-bottom: none; }
    .summary-label { color: #888; }
    .summary-value { font-weight: bold; }

    /* Buttons */
    .btn {
      padding: 14px 28px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
      transition: all 0.2s;
    }
    .btn-primary {
      background: #4ecdc4;
      color: #1a1a2e;
    }
    .btn-primary:hover { background: #3dbdb5; }
    .btn-primary:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }
    .btn-secondary {
      background: #444;
      color: #fff;
    }
    .btn-secondary:hover { background: #555; }
    .btn-group {
      display: flex;
      justify-content: space-between;
      margin-top: 30px;
    }

    /* Success */
    .success-container {
      text-align: center;
      padding: 40px;
    }
    .success-icon {
      width: 80px;
      height: 80px;
      background: #4ecdc4;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
      font-size: 40px;
    }
    .success-title {
      font-size: 24px;
      margin-bottom: 10px;
    }
    .success-message {
      color: #888;
      margin-bottom: 30px;
    }
    .confirmation-number {
      background: #1a1a2e;
      padding: 15px 30px;
      border-radius: 8px;
      display: inline-block;
      font-size: 20px;
      font-family: monospace;
      margin-bottom: 30px;
    }

    /* Hidden steps */
    .step-content { display: none; }
    .step-content.active { display: block; }

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

    /* Closed message */
    .closed-message {
      text-align: center;
      padding: 40px;
      color: #888;
    }
    .closed-message .icon {
      font-size: 48px;
      margin-bottom: 15px;
    }

    /* Loading */
    .loading {
      display: flex;
      justify-content: center;
      padding: 20px;
    }
    .loading::after {
      content: '';
      width: 30px;
      height: 30px;
      border: 3px solid #333;
      border-top-color: #4ecdc4;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* No availability */
    .no-availability {
      text-align: center;
      padding: 30px;
      color: #888;
    }

    /* Reservations Unavailable - shown by default when RESERVATIONS_ENABLED is false */
    .unavailable-container {
      display: block;
    }
    .unavailable-container.hidden {
      display: none;
    }
    .unavailable-card {
      background: #252542;
      border-radius: 16px;
      padding: 60px 30px;
      text-align: center;
      max-width: 500px;
      margin: 40px auto;
    }
    .unavailable-icon {
      font-size: 64px;
      margin-bottom: 20px;
    }
    .unavailable-title {
      font-size: 24px;
      color: #ff9f1c;
      margin-bottom: 15px;
    }
    .unavailable-message {
      color: #888;
      font-size: 16px;
      line-height: 1.6;
    }
    /* Reservation content - hidden by default when RESERVATIONS_ENABLED is false */
    .reservation-content {
      display: none;
    }
    .reservation-content.visible {
      display: block;
    }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1 data-i18n="title">Make a Reservation</h1>
      <div class="header-right">
        <div class="lang-switch">
          <button class="lang-btn" data-lang="en">EN</button>
          <button class="lang-btn active" data-lang="ko">KO</button>
        </div>
        <nav class="nav-links">
          <a href="admin.php" data-i18n="nav.settings">Settings</a>
          <a href="dashboard.php" data-i18n="nav.dashboard">Dashboard</a>
          <a href="reservation.php" class="active" data-i18n="nav.reservation">Reservation</a>
        </nav>
      </div>
    </header>

    <!-- Reservations Unavailable Message -->
    <div class="unavailable-container" id="unavailable-container">
      <div class="unavailable-card">
        <div class="unavailable-icon">🚧</div>
        <h2 class="unavailable-title" data-i18n="reservation.unavailableTitle">Reservations Temporarily Unavailable</h2>
        <p class="unavailable-message" data-i18n="reservation.unavailableMessage">Online reservations are currently not available. Please call us to make a reservation.</p>
        <div style="margin-top: 30px;">
          <p style="color: #4ecdc4; font-size: 18px;">Yanji Restaurant</p>
          <p style="color: #888; font-size: 14px; margin-top: 10px;">📍 153 Bethnal Green Road, London E2 7DG</p>
          <p style="color: #888; font-size: 14px;">📞 +447910754793</p>
        </div>
      </div>
    </div>

    <!-- Reservation Content (hidden when unavailable) -->
    <div class="reservation-content" id="reservation-content">

    <!-- Steps Indicator -->
    <div class="steps">
      <div class="step active" data-step="1">
        <div class="step-number">1</div>
        <span class="step-label" data-i18n="steps.dateTime">Date & Time</span>
      </div>
      <div class="step-divider"></div>
      <div class="step" data-step="2">
        <div class="step-number">2</div>
        <span class="step-label" data-i18n="steps.guests">Guests</span>
      </div>
      <div class="step-divider"></div>
      <div class="step" data-step="3">
        <div class="step-number">3</div>
        <span class="step-label" data-i18n="steps.details">Details</span>
      </div>
      <div class="step-divider"></div>
      <div class="step" data-step="4">
        <div class="step-number">4</div>
        <span class="step-label" data-i18n="steps.confirm">Confirm</span>
      </div>
    </div>

    <!-- Step 1: Date & Time -->
    <div class="step-content active" id="step-1">
      <div class="form-card">
        <h2 data-i18n="reservation.selectDate">Select Date</h2>
        <div class="date-nav">
          <button onclick="changeWeek(-1)">&lt;</button>
          <span class="month-label" id="month-label"></span>
          <button onclick="changeWeek(1)">&gt;</button>
        </div>
        <div class="date-grid" id="date-grid">
          <!-- Populated by JS -->
        </div>
      </div>

      <div class="form-card" id="time-section" style="display: none;">
        <h2 data-i18n="reservation.selectTime">Select Time</h2>
        <div id="time-blocks">
          <!-- Populated by JS -->
        </div>
      </div>

      <div class="btn-group">
        <div></div>
        <button class="btn btn-primary" onclick="goToStep(2)" id="btn-step1" disabled data-i18n="common.next">Next</button>
      </div>
    </div>

    <!-- Step 2: Guests -->
    <div class="step-content" id="step-2">
      <div class="form-card">
        <h2 data-i18n="reservation.howMany">How many guests?</h2>
        <div class="guest-selector">
          <button class="guest-btn" onclick="changeGuests(-1)">-</button>
          <div class="guest-count" id="guest-count">2</div>
          <button class="guest-btn" onclick="changeGuests(1)">+</button>
        </div>
        <div class="guest-label" data-i18n="reservation.guestsLabel">guests</div>
        <p id="availability-message" style="text-align: center; margin-top: 20px; color: #4ecdc4;"></p>
      </div>

      <div class="btn-group">
        <button class="btn btn-secondary" onclick="goToStep(1)" data-i18n="common.back">Back</button>
        <button class="btn btn-primary" onclick="goToStep(3)" id="btn-step2" data-i18n="common.next">Next</button>
      </div>
    </div>

    <!-- Step 3: Contact Details -->
    <div class="step-content" id="step-3">
      <div class="form-card">
        <h2 data-i18n="reservation.contactInfo">Contact Information</h2>
        <div class="form-row">
          <div class="form-group">
            <label data-i18n="reservation.name">Name</label>
            <input type="text" id="customer-name" required>
          </div>
          <div class="form-group">
            <label data-i18n="reservation.phone">Phone</label>
            <input type="tel" id="customer-phone" required placeholder="+44 7123 456789">
          </div>
        </div>
        <div class="form-group">
          <label data-i18n="reservation.notes">Special Requests (Optional)</label>
          <textarea id="customer-notes" data-i18n-placeholder="reservation.notesPlaceholder" placeholder="Allergies, special occasions, seating preferences..."></textarea>
        </div>
      </div>

      <div class="btn-group">
        <button class="btn btn-secondary" onclick="goToStep(2)" data-i18n="common.back">Back</button>
        <button class="btn btn-primary" onclick="goToStep(4)" id="btn-step3" data-i18n="common.next">Next</button>
      </div>
    </div>

    <!-- Step 4: Confirm -->
    <div class="step-content" id="step-4">
      <div class="form-card">
        <h2 data-i18n="reservation.confirmTitle">Confirm Your Reservation</h2>
        <div class="summary-card">
          <div class="summary-row">
            <span class="summary-label" data-i18n="reservation.date">Date</span>
            <span class="summary-value" id="summary-date"></span>
          </div>
          <div class="summary-row">
            <span class="summary-label" data-i18n="reservation.time">Time</span>
            <span class="summary-value" id="summary-time"></span>
          </div>
          <div class="summary-row">
            <span class="summary-label" data-i18n="reservation.guests">Guests</span>
            <span class="summary-value" id="summary-guests"></span>
          </div>
          <div class="summary-row">
            <span class="summary-label" data-i18n="reservation.name">Name</span>
            <span class="summary-value" id="summary-name"></span>
          </div>
          <div class="summary-row">
            <span class="summary-label" data-i18n="reservation.phone">Phone</span>
            <span class="summary-value" id="summary-phone"></span>
          </div>
        </div>
        <p style="color: #888; font-size: 14px; text-align: center;" data-i18n="reservation.tableAssignNote">Table will be assigned automatically based on availability.</p>
      </div>

      <div class="btn-group" style="justify-content: center;">
        <button class="btn btn-primary" onclick="submitReservation()" id="btn-submit" data-i18n="reservation.confirm">Confirm Reservation</button>
      </div>
    </div>

    <!-- Step 5: Success -->
    <div class="step-content" id="step-5">
      <div class="form-card">
        <div class="success-container">
          <div class="success-icon">✓</div>
          <h2 class="success-title" data-i18n="reservation.successTitle">Reservation Confirmed!</h2>
          <p class="success-message" data-i18n="reservation.successMessage">We've sent a confirmation to your phone.</p>
          <div class="confirmation-number" id="confirmation-number"></div>
          <div class="summary-card" style="text-align: left;">
            <div class="summary-row">
              <span class="summary-label" data-i18n="reservation.date">Date</span>
              <span class="summary-value" id="final-date"></span>
            </div>
            <div class="summary-row">
              <span class="summary-label" data-i18n="reservation.time">Time</span>
              <span class="summary-value" id="final-time"></span>
            </div>
            <div class="summary-row">
              <span class="summary-label" data-i18n="reservation.guests">Guests</span>
              <span class="summary-value" id="final-guests"></span>
            </div>
            <div class="summary-row">
              <span class="summary-label" data-i18n="reservation.table">Table</span>
              <span class="summary-value" id="final-table"></span>
            </div>
          </div>
          <div class="btn-group" style="justify-content: center; gap: 15px;">
            <button class="btn btn-secondary" onclick="newReservation()" data-i18n="reservation.newReservation">Make Another Reservation</button>
            <button class="btn btn-primary" onclick="finishReservation()" data-i18n="reservation.finish">Finish</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Step 6: Done / Thank You -->
    <div class="step-content" id="step-6">
      <div class="form-card">
        <div class="success-container">
          <div class="success-icon" style="background: #2ecc71;">✓</div>
          <h2 class="success-title" data-i18n="reservation.thankYou">Thank you for your reservation!</h2>
          <p class="success-message" data-i18n="reservation.seeYouSoon">We look forward to seeing you.</p>
          <div style="margin-top: 30px;">
            <p style="color: #4ecdc4; font-size: 18px;">Yanji Restaurant</p>
            <p style="color: #888; font-size: 14px; margin-top: 10px;">📍 153 Bethnal Green Road, London E2 7DG</p>
            <p style="color: #888; font-size: 14px;">📞 +447910754793</p>
          </div>
        </div>
      </div>
    </div>

    </div><!-- /reservation-content -->
  </div>

  <!-- Toast -->
  <div class="toast" id="toast"></div>

  <script>
    const API_BASE = window.YANJI_CONFIG?.API_BASE || 'http://localhost:3000';

    // ==================== i18n ====================
    const i18n = {
      en: {
        title: 'Make a Reservation',
        nav: {
          settings: 'Settings',
          dashboard: 'Dashboard',
          reservation: 'Reservation'
        },
        steps: {
          dateTime: 'Date & Time',
          guests: 'Guests',
          details: 'Details',
          confirm: 'Confirm'
        },
        reservation: {
          selectDate: 'Select Date',
          selectTime: 'Select Time',
          howMany: 'How many guests?',
          guestsLabel: 'guests',
          contactInfo: 'Contact Information',
          name: 'Name',
          phone: 'Phone',
          notes: 'Special Requests (Optional)',
          notesPlaceholder: 'Allergies, special occasions, seating preferences...',
          confirmTitle: 'Confirm Your Reservation',
          date: 'Date',
          time: 'Time',
          guests: 'Guests',
          table: 'Table',
          confirm: 'Confirm Reservation',
          successTitle: 'Reservation Confirmed!',
          successMessage: "We've sent a confirmation to your phone.",
          newReservation: 'Make Another Reservation',
          finish: 'Finish',
          thankYou: 'Thank you for your reservation!',
          seeYouSoon: 'We look forward to seeing you.',
          available: 'available',
          limited: 'limited',
          unavailable: 'full',
          closed: 'Closed',
          tableAssignNote: 'Table will be assigned automatically based on availability.',
          tablesAvailable: 'tables available',
          noAvailability: 'No availability for this selection. Please try a different time.',
          fillRequired: 'Please fill in required fields',
          slotTaken: 'Sorry, this time slot was just booked. Please select another time.',
          phonePlaceholder: '+44 7123 456789',
          unavailableTitle: 'Reservations Temporarily Unavailable',
          unavailableMessage: 'Online reservations are currently not available. Please call us to make a reservation.'
        },
        days: {
          sun: 'Sun', mon: 'Mon', tue: 'Tue', wed: 'Wed', thu: 'Thu', fri: 'Fri', sat: 'Sat'
        },
        blocks: {
          lunch: 'Lunch',
          dinner: 'Dinner'
        },
        common: {
          next: 'Next',
          back: 'Back',
          guests: 'guests'
        }
      },
      ko: {
        title: '예약하기',
        nav: {
          settings: '설정',
          dashboard: '대시보드',
          reservation: '예약'
        },
        steps: {
          dateTime: '날짜 & 시간',
          guests: '인원',
          details: '정보',
          confirm: '확인'
        },
        reservation: {
          selectDate: '날짜 선택',
          selectTime: '시간 선택',
          howMany: '몇 분이세요?',
          guestsLabel: '명',
          contactInfo: '연락처 정보',
          name: '이름',
          phone: '전화번호',
          notes: '요청사항 (선택사항)',
          notesPlaceholder: '알레르기, 기념일, 좌석 선호도...',
          confirmTitle: '예약 확인',
          date: '날짜',
          time: '시간',
          guests: '인원',
          table: '테이블',
          confirm: '예약 확정',
          successTitle: '예약이 완료되었습니다!',
          successMessage: '확인 문자를 전송했습니다.',
          newReservation: '새 예약하기',
          finish: '완료',
          thankYou: '예약해 주셔서 감사합니다!',
          seeYouSoon: '매장에서 뵙겠습니다.',
          available: '가능',
          limited: '일부',
          unavailable: '마감',
          closed: '휴무',
          tableAssignNote: '테이블은 이용 가능 여부에 따라 자동 배정됩니다.',
          tablesAvailable: '테이블 이용가능',
          noAvailability: '선택하신 조건에 이용 가능한 테이블이 없습니다. 다른 시간을 선택해주세요.',
          fillRequired: '필수 항목을 입력해주세요',
          slotTaken: '죄송합니다. 해당 시간이 방금 예약되었습니다. 다른 시간을 선택해주세요.',
          phonePlaceholder: '+44 7123 456789',
          unavailableTitle: '예약 일시 중단',
          unavailableMessage: '현재 온라인 예약이 불가능합니다. 전화로 예약해 주세요.'
        },
        days: {
          sun: '일', mon: '월', tue: '화', wed: '수', thu: '목', fri: '금', sat: '토'
        },
        blocks: {
          lunch: '점심',
          dinner: '저녁'
        },
        common: {
          next: '다음',
          back: '이전',
          guests: '명'
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
      document.querySelectorAll('.lang-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.lang === currentLang);
      });
      renderDateGrid();
      if (reservation.date) {
        renderTimeSlots();
      }
      updateAvailabilityMessage();
    }

    function setLanguage(lang) {
      currentLang = lang;
      localStorage.setItem('yanji-lang', lang);
      applyTranslations();
    }

    // ==================== State ====================
    let currentStep = 1;
    let weekStart = new Date();
    weekStart.setDate(weekStart.getDate() - weekStart.getDay());

    let reservation = {
      date: null,
      time: null,
      guests: 2,
      name: '',
      phone: '',
      notes: ''
    };

    // Cached data from API
    let operationHours = null;
    let availableSlots = {};
    let reservationsEnabled = true;

    // ==================== API Calls ====================
    async function fetchConfig() {
      // Check frontend config flag first (quick toggle)
      const frontendEnabled = window.YANJI_CONFIG?.RESERVATIONS_ENABLED !== false;

      try {
        const res = await fetch(`${API_BASE}/config`);
        const data = await res.json();
        operationHours = data.operationHours;

        // Reservations are enabled only if BOTH frontend AND backend flags are true
        const backendEnabled = data.reservationsEnabled !== false;
        reservationsEnabled = frontendEnabled && backendEnabled;

        // Show/hide content based on reservationsEnabled
        updateReservationAvailability();

        if (reservationsEnabled) {
          renderDateGrid();
        }
      } catch (e) {
        console.error('Failed to fetch config:', e);
        // Use defaults
        operationHours = {
          default: { open: '11:30', close: '22:00' },
          weekly: {
            monday: { closed: true },
            tuesday: { open: '11:30', close: '22:00' },
            wednesday: { open: '11:30', close: '22:00' },
            thursday: { open: '11:30', close: '22:00' },
            friday: { open: '11:30', close: '23:00' },
            saturday: { open: '11:30', close: '23:00' },
            sunday: { open: '11:30', close: '21:00' }
          },
          blocks: [
            { name: 'lunch', start: '11:30', end: '14:30' },
            { name: 'dinner', start: '17:00', end: '22:00' }
          ]
        };
        reservationsEnabled = frontendEnabled;
        updateReservationAvailability();
      }
    }

    function updateReservationAvailability() {
      const unavailableContainer = document.getElementById('unavailable-container');
      const reservationContent = document.getElementById('reservation-content');

      if (reservationsEnabled) {
        unavailableContainer.classList.add('hidden');
        reservationContent.classList.add('visible');
      } else {
        unavailableContainer.classList.remove('hidden');
        reservationContent.classList.remove('visible');
      }
    }

    async function fetchAvailableSlots(date, guestCount, forceRefresh = false) {
      const key = `${date}-${guestCount}`;
      if (!forceRefresh && availableSlots[key]) {
        return availableSlots[key];
      }

      try {
        const res = await fetch(`${API_BASE}/reservations/available?date=${date}&guestCount=${guestCount}`);
        const data = await res.json();
        availableSlots[key] = data;
        return data;
      } catch (e) {
        console.error('Failed to fetch available slots:', e);
        return [];
      }
    }

    // Polling for real-time availability updates
    let pollingInterval = null;
    const POLLING_INTERVAL_MS = 30000; // 30 seconds

    function startPolling() {
      stopPolling(); // Clear any existing interval
      if (reservation.date && currentStep === 1) {
        pollingInterval = setInterval(async () => {
          if (reservation.date && currentStep === 1) {
            await renderTimeSlots(true); // Force refresh
          }
        }, POLLING_INTERVAL_MS);
      }
    }

    function stopPolling() {
      if (pollingInterval) {
        clearInterval(pollingInterval);
        pollingInterval = null;
      }
    }

    async function createReservation(data) {
      const res = await fetch(`${API_BASE}/reservations`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });

      if (!res.ok) {
        const error = await res.json();
        throw new Error(error.message || 'Failed to create reservation');
      }

      return res.json();
    }

    // ==================== Rendering ====================
    function getDayHours(date) {
      if (!operationHours) return null;

      const dateStr = date.toISOString().split('T')[0];
      const dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
      const dayName = dayNames[date.getDay()];

      // Check override first
      if (operationHours.override && operationHours.override[dateStr]) {
        return operationHours.override[dateStr];
      }

      // Then weekly
      if (operationHours.weekly && operationHours.weekly[dayName]) {
        return operationHours.weekly[dayName];
      }

      // Default
      return operationHours.default;
    }

    function renderDateGrid() {
      const grid = document.getElementById('date-grid');
      grid.innerHTML = '';

      const today = new Date();
      today.setHours(0, 0, 0, 0);

      const dayNames = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];

      // Update month label
      const monthFormat = { month: 'long', year: 'numeric' };
      document.getElementById('month-label').textContent =
        weekStart.toLocaleDateString(currentLang === 'ko' ? 'ko-KR' : 'en-US', monthFormat);

      for (let i = 0; i < 7; i++) {
        const date = new Date(weekStart);
        date.setDate(date.getDate() + i);

        const dateStr = date.toISOString().split('T')[0];
        const hours = getDayHours(date);
        const isPast = date < today;
        const isClosed = hours?.closed || isPast;
        const isSelected = reservation.date === dateStr;

        const cell = document.createElement('div');
        cell.className = `date-cell ${isClosed ? 'disabled' : ''} ${isSelected ? 'selected' : ''}`;
        cell.innerHTML = `
          <div class="day-name">${t('days.' + dayNames[date.getDay()])}</div>
          <div class="day-num">${date.getDate()}</div>
        `;

        if (!isClosed) {
          cell.onclick = () => selectDate(dateStr);
        }

        grid.appendChild(cell);
      }
    }

    async function renderTimeSlots(forceRefresh = false) {
      const container = document.getElementById('time-blocks');

      // Only show loading spinner on initial load, not on polling refresh
      if (!forceRefresh) {
        container.innerHTML = '<div class="loading"></div>';
      }

      if (!reservation.date) {
        document.getElementById('time-section').style.display = 'none';
        return;
      }

      document.getElementById('time-section').style.display = 'block';

      const date = new Date(reservation.date);
      const hours = getDayHours(date);

      if (hours?.closed) {
        container.innerHTML = `<div class="closed-message">${t('reservation.closed')}</div>`;
        return;
      }

      // Fetch available slots from API (force refresh if polling)
      const slots = await fetchAvailableSlots(reservation.date, reservation.guests, forceRefresh);

      // Build a map of time -> available tables count
      const timeAvailability = {};
      slots.forEach(slot => {
        slot.slots.forEach(time => {
          timeAvailability[time] = (timeAvailability[time] || 0) + 1;
        });
      });

      container.innerHTML = '';

      const blocks = operationHours?.blocks || [
        { name: 'lunch', start: '11:30', end: '14:30' },
        { name: 'dinner', start: '17:00', end: '22:00' }
      ];

      blocks.forEach(block => {
        const section = document.createElement('div');
        section.className = 'block-section';

        const title = document.createElement('div');
        title.className = 'block-title';

        // Handle both English and Korean block names
        const blockName = block.name === '점심' ? 'lunch' : block.name === '저녁' ? 'dinner' : block.name;
        title.textContent = t('blocks.' + blockName);
        section.appendChild(title);

        const grid = document.createElement('div');
        grid.className = 'time-grid';

        // Generate time slots every 30 minutes
        const [startH, startM] = block.start.split(':').map(Number);
        const [endH, endM] = block.end.split(':').map(Number);
        const startMin = startH * 60 + startM;
        const endMin = endH * 60 + endM;

        // Check last reservation time
        const lastResTime = hours?.lastReservation;
        const [lastH, lastM] = lastResTime ? lastResTime.split(':').map(Number) : [23, 59];
        const lastResMin = lastH * 60 + lastM;

        for (let min = startMin; min < endMin && min <= lastResMin; min += 30) {
          const h = Math.floor(min / 60);
          const m = min % 60;
          const time = `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}`;

          // Check if this time is in the past
          const now = new Date();
          const slotDate = new Date(reservation.date);
          slotDate.setHours(h, m);
          const isPast = slotDate < now;

          const availCount = timeAvailability[time] || 0;
          const isAvailable = availCount > 0 && !isPast;
          const isLimited = availCount > 0 && availCount <= 2;

          const slot = document.createElement('div');
          slot.className = `time-slot ${!isAvailable ? 'disabled' : ''} ${isLimited ? 'limited' : ''} ${reservation.time === time ? 'selected' : ''}`;

          let availText = t('reservation.available');
          if (!isAvailable) {
            availText = isPast ? '-' : t('reservation.unavailable');
          } else if (isLimited) {
            availText = t('reservation.limited');
          }

          slot.innerHTML = `
            <div>${time}</div>
            <div class="availability">${availText}</div>
          `;

          if (isAvailable) {
            slot.onclick = () => selectTime(time);
          }

          grid.appendChild(slot);
        }

        section.appendChild(grid);
        container.appendChild(section);
      });
    }

    function updateAvailabilityMessage() {
      const msg = document.getElementById('availability-message');
      if (!reservation.date || !reservation.time) {
        msg.textContent = '';
        return;
      }

      const key = `${reservation.date}-${reservation.guests}`;
      const slots = availableSlots[key];

      if (slots) {
        const tablesForTime = slots.filter(s => s.slots.includes(reservation.time)).length;
        if (tablesForTime > 0) {
          msg.textContent = `${tablesForTime} ${t('reservation.tablesAvailable')}`;
          msg.style.color = '#4ecdc4';
        } else {
          msg.textContent = t('reservation.noAvailability');
          msg.style.color = '#ff6b6b';
        }
      }
    }

    function updateSummary() {
      const guestLabel = currentLang === 'ko' ? '명' : ' guests';

      const dateObj = new Date(reservation.date);
      const dateFormat = { year: 'numeric', month: 'long', day: 'numeric', weekday: 'short' };
      const dateStr = dateObj.toLocaleDateString(currentLang === 'ko' ? 'ko-KR' : 'en-US', dateFormat);

      document.getElementById('summary-date').textContent = dateStr;
      document.getElementById('summary-time').textContent = reservation.time;
      document.getElementById('summary-guests').textContent = reservation.guests + guestLabel;
      document.getElementById('summary-name').textContent = reservation.name;
      document.getElementById('summary-phone').textContent = reservation.phone;
    }

    // ==================== Actions ====================
    function changeWeek(delta) {
      weekStart.setDate(weekStart.getDate() + (delta * 7));
      renderDateGrid();
    }

    async function selectDate(dateStr) {
      reservation.date = dateStr;
      reservation.time = null;
      renderDateGrid();
      await renderTimeSlots();
      updateNextButton();
      startPolling(); // Start polling for real-time updates
    }

    function selectTime(time) {
      reservation.time = time;
      renderTimeSlots();
      updateNextButton();
    }

    async function changeGuests(delta) {
      const newCount = reservation.guests + delta;
      if (newCount >= 1 && newCount <= 10) {
        reservation.guests = newCount;
        document.getElementById('guest-count').textContent = newCount;

        // Re-fetch availability for new guest count
        if (reservation.date) {
          await renderTimeSlots();

          // Check if current time is still available
          const key = `${reservation.date}-${reservation.guests}`;
          const slots = availableSlots[key];
          if (slots && reservation.time) {
            const stillAvailable = slots.some(s => s.slots.includes(reservation.time));
            if (!stillAvailable) {
              reservation.time = null;
              updateNextButton();
            }
          }
        }

        updateAvailabilityMessage();
      }
    }

    function updateNextButton() {
      const btn = document.getElementById('btn-step1');
      btn.disabled = !reservation.date || !reservation.time;
    }

    function goToStep(step) {
      if (step === 4) {
        reservation.name = document.getElementById('customer-name').value.trim();
        reservation.phone = document.getElementById('customer-phone').value.trim();
        reservation.notes = document.getElementById('customer-notes').value.trim();

        if (!reservation.name || !reservation.phone) {
          showToast(t('reservation.fillRequired'), true);
          return;
        }

        updateSummary();
      }

      currentStep = step;

      // Manage polling based on step
      if (step === 1 && reservation.date) {
        startPolling();
      } else {
        stopPolling();
      }

      // Update step indicators
      document.querySelectorAll('.step').forEach((el, i) => {
        el.classList.remove('active', 'completed');
        if (i + 1 < step) el.classList.add('completed');
        if (i + 1 === step) el.classList.add('active');
      });

      // Show/hide step content
      document.querySelectorAll('.step-content').forEach(el => el.classList.remove('active'));
      document.getElementById('step-' + step).classList.add('active');

      // Update availability message when going to step 2
      if (step === 2) {
        updateAvailabilityMessage();
      }
    }

    async function submitReservation() {
      const btn = document.getElementById('btn-submit');
      btn.disabled = true;
      btn.textContent = '...';

      try {
        // Re-fetch availability to ensure slot is still available (prevent race conditions)
        const freshSlots = await fetchAvailableSlots(reservation.date, reservation.guests);

        // Clear cache to force fresh data
        const key = `${reservation.date}-${reservation.guests}`;
        delete availableSlots[key];
        availableSlots[key] = freshSlots;

        // Find best available table from fresh data
        const availableTable = freshSlots?.find(s => s.slots.includes(reservation.time));

        if (!availableTable) {
          // Slot was taken - go back to step 1 and refresh
          await renderTimeSlots();
          goToStep(1);
          throw new Error(t('reservation.slotTaken'));
        }

        const result = await createReservation({
          tableId: availableTable.tableId,
          tablePart: availableTable.tablePart || undefined,
          customerName: reservation.name,
          customerPhone: reservation.phone,
          guestCount: reservation.guests,
          date: reservation.date,
          startTime: reservation.time,
          createdBy: 'web'
        });

        // Show success
        const guestLabel = currentLang === 'ko' ? '명' : ' guests';
        const dateObj = new Date(reservation.date);
        const dateFormat = { year: 'numeric', month: 'long', day: 'numeric' };
        const dateStr = dateObj.toLocaleDateString(currentLang === 'ko' ? 'ko-KR' : 'en-US', dateFormat);

        document.getElementById('confirmation-number').textContent = result.reservationId.slice(0, 8).toUpperCase();
        document.getElementById('final-date').textContent = dateStr;
        document.getElementById('final-time').textContent = `${result.startTime} - ${result.endTime}`;
        document.getElementById('final-guests').textContent = result.guestCount + guestLabel;

        // Get table name
        const tableName = result.tablePart || result.tableId;
        document.getElementById('final-table').textContent = tableName;

        goToStep(5);
      } catch (error) {
        showToast(error.message, true);
        btn.disabled = false;
        btn.textContent = t('reservation.confirm');
      }
    }

    function newReservation() {
      // Stop polling
      stopPolling();

      // Reset state
      reservation = {
        date: null,
        time: null,
        guests: 2,
        name: '',
        phone: '',
        notes: ''
      };

      // Clear cached availability
      availableSlots = {};

      // Reset form fields
      document.getElementById('customer-name').value = '';
      document.getElementById('customer-phone').value = '';
      document.getElementById('customer-notes').value = '';
      document.getElementById('guest-count').textContent = '2';
      document.getElementById('availability-message').textContent = '';

      // Go back to step 1
      currentStep = 1;
      document.querySelectorAll('.step').forEach((el, i) => {
        el.classList.remove('active', 'completed');
        if (i === 0) el.classList.add('active');
      });
      document.querySelectorAll('.step-content').forEach(el => el.classList.remove('active'));
      document.getElementById('step-1').classList.add('active');

      renderDateGrid();
      document.getElementById('time-section').style.display = 'none';
      updateNextButton();
    }

    function finishReservation() {
      // Hide steps indicator
      document.querySelector('.steps').style.display = 'none';

      // Show the thank you / done view
      document.querySelectorAll('.step-content').forEach(el => el.classList.remove('active'));
      document.getElementById('step-6').classList.add('active');
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

    // ==================== Initialize ====================
    // Check frontend flag immediately - default is unavailable (shown in CSS)
    if (window.YANJI_CONFIG?.RESERVATIONS_ENABLED === false) {
      // Unavailable is already shown by default CSS, just apply translations
      reservationsEnabled = false;
      applyTranslations();
    } else {
      // Enable reservations - show form, hide unavailable
      reservationsEnabled = true;
      updateReservationAvailability();
      fetchConfig().then(() => {
        applyTranslations();
        renderDateGrid();
      });
    }
  </script>
</body>
</html>
