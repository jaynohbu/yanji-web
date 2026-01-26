// Yanji Reservation Popup
// Usage: Include this script, then call YanjiReservation.open() to show the popup

(function() {
  const API_BASE = window.YANJI_CONFIG?.API_BASE || 'https://yanji.tunesbasis.com';

  // ==================== i18n ====================
  const i18n = {
    en: {
      title: 'Book a Table',
      steps: { dateTime: 'Date & Time', guests: 'Guests', details: 'Details', confirm: 'Confirm' },
      reservation: {
        selectDate: 'Select Date',
        selectTime: 'Select Time',
        howMany: 'How many guests?',
        guestsLabel: 'guests',
        contactInfo: 'Contact Information',
        name: 'Name',
        phone: 'Phone',
        smsConsent: 'Send me reservation reminders via SMS',
        notes: 'Special Requests (Optional)',
        notesPlaceholder: 'Allergies, special occasions, seating preferences...',
        confirmTitle: 'Confirm Your Reservation',
        date: 'Date', time: 'Time', guests: 'Guests', table: 'Table',
        confirm: 'Confirm Reservation',
        successTitle: 'Reservation Confirmed!',
        successMessage: "We've sent a confirmation to your phone.",
        newReservation: 'Make Another Reservation',
        available: 'available', limited: 'limited', unavailable: 'full', closed: 'Closed',
        tableAssignNote: 'Table will be assigned automatically based on availability.',
        tablesAvailable: 'tables available',
        noAvailability: 'No availability for this selection. Please try a different time.',
        fillRequired: 'Please fill in all required fields',
        slotTaken: 'Sorry, this time slot was just booked. Please select another time.',
        close: 'Close'
      },
      days: { sun: 'Sun', mon: 'Mon', tue: 'Tue', wed: 'Wed', thu: 'Thu', fri: 'Fri', sat: 'Sat' },
      blocks: { lunch: 'Lunch', dinner: 'Dinner' },
      common: { next: 'Next', back: 'Back' }
    },
    ko: {
      title: '예약하기',
      steps: { dateTime: '날짜 & 시간', guests: '인원', details: '정보', confirm: '확인' },
      reservation: {
        selectDate: '날짜 선택',
        selectTime: '시간 선택',
        howMany: '몇 분이세요?',
        guestsLabel: '명',
        contactInfo: '연락처 정보',
        name: '이름',
        phone: '전화번호',
        smsConsent: '예약 알림을 SMS로 받고 싶습니다',
        notes: '요청사항 (선택사항)',
        notesPlaceholder: '알레르기, 기념일, 좌석 선호도...',
        confirmTitle: '예약 확인',
        date: '날짜', time: '시간', guests: '인원', table: '테이블',
        confirm: '예약 확정',
        successTitle: '예약이 완료되었습니다!',
        successMessage: '확인 문자를 전송했습니다.',
        newReservation: '새 예약하기',
        available: '가능', limited: '일부', unavailable: '마감', closed: '휴무',
        tableAssignNote: '테이블은 이용 가능 여부에 따라 자동 배정됩니다.',
        tablesAvailable: '테이블 이용가능',
        noAvailability: '선택하신 조건에 이용 가능한 테이블이 없습니다.',
        fillRequired: '필수 항목을 입력해주세요',
        slotTaken: '죄송합니다. 해당 시간이 방금 예약되었습니다.',
        close: '닫기'
      },
      days: { sun: '일', mon: '월', tue: '화', wed: '수', thu: '목', fri: '금', sat: '토' },
      blocks: { lunch: '점심', dinner: '저녁' },
      common: { next: '다음', back: '이전' }
    }
  };

  let currentLang = localStorage.getItem('yanji-lang') || (navigator.language.startsWith('ko') ? 'ko' : 'en');

  function t(key) {
    const keys = key.split('.');
    let value = i18n[currentLang];
    for (const k of keys) value = value?.[k];
    return value || key;
  }

  // ==================== State ====================
  let currentStep = 1;
  let weekStart = new Date();
  weekStart.setDate(weekStart.getDate() - weekStart.getDay());

  let reservation = { date: null, time: null, guests: 2, name: '', phone: '', notes: '', smsConsent: true };
  let operationHours = null;
  let availableSlots = {};
  let popupContainer = null;

  // ==================== CSS ====================
  const styles = `
    .yanji-popup-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0,0,0,0.85);
      z-index: 99999;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      box-sizing: border-box;
      overflow-y: auto;
    }
    .yanji-popup {
      background: #1a1a2e;
      border-radius: 16px;
      max-width: 600px;
      width: 100%;
      max-height: 90vh;
      overflow-y: auto;
      position: relative;
      color: #eee;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    .yanji-popup * { box-sizing: border-box; margin: 0; padding: 0; }
    .yanji-popup-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px;
      border-bottom: 1px solid #333;
    }
    .yanji-popup-title { font-size: 20px; font-weight: bold; color: #fff; }
    .yanji-popup-close {
      background: none;
      border: none;
      color: #888;
      font-size: 28px;
      cursor: pointer;
      line-height: 1;
      padding: 5px;
    }
    .yanji-popup-close:hover { color: #fff; }
    .yanji-popup-body { padding: 20px; }
    .yanji-lang-switch { display: flex; gap: 5px; margin-right: 20px; }
    .yanji-lang-btn {
      padding: 5px 10px;
      background: #333;
      border: none;
      color: #888;
      cursor: pointer;
      border-radius: 4px;
      font-size: 12px;
    }
    .yanji-lang-btn.active { background: #4ecdc4; color: #1a1a2e; }

    /* Steps */
    .yanji-steps { display: flex; justify-content: center; margin-bottom: 25px; flex-wrap: wrap; }
    .yanji-step { display: flex; align-items: center; color: #666; }
    .yanji-step-number {
      width: 28px; height: 28px; border-radius: 50%; background: #333;
      display: flex; align-items: center; justify-content: center;
      font-size: 12px; margin-right: 6px;
    }
    .yanji-step.active .yanji-step-number { background: #4ecdc4; color: #1a1a2e; }
    .yanji-step.completed .yanji-step-number { background: #2ecc71; color: #fff; }
    .yanji-step-label { font-size: 12px; }
    .yanji-step.active .yanji-step-label { color: #fff; }
    .yanji-step-divider { width: 20px; height: 2px; background: #333; margin: 0 8px; }

    /* Form Card */
    .yanji-card {
      background: #252542;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 15px;
    }
    .yanji-card h3 { font-size: 16px; margin-bottom: 15px; color: #4ecdc4; }

    /* Date Grid */
    .yanji-date-nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
    .yanji-date-nav button {
      padding: 6px 12px; background: #333; border: none; color: #fff;
      cursor: pointer; border-radius: 4px; font-size: 14px;
    }
    .yanji-date-nav button:hover { background: #444; }
    .yanji-month-label { font-size: 14px; font-weight: bold; }
    .yanji-date-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; }
    .yanji-date-cell {
      padding: 10px 4px; background: #1a1a2e; border-radius: 6px;
      text-align: center; cursor: pointer; transition: all 0.2s;
    }
    .yanji-date-cell:hover:not(.disabled) { background: #333; }
    .yanji-date-cell.selected { background: #4ecdc4; color: #1a1a2e; }
    .yanji-date-cell.disabled { opacity: 0.3; cursor: not-allowed; }
    .yanji-day-name { font-size: 10px; color: #888; margin-bottom: 3px; }
    .yanji-date-cell.selected .yanji-day-name { color: #1a1a2e; }
    .yanji-day-num { font-size: 16px; font-weight: bold; }

    /* Time Grid */
    .yanji-block-title { font-size: 12px; color: #888; margin: 15px 0 8px; }
    .yanji-time-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
    @media (max-width: 500px) { .yanji-time-grid { grid-template-columns: repeat(3, 1fr); } }
    .yanji-time-slot {
      padding: 10px 6px; background: #1a1a2e; border-radius: 6px;
      text-align: center; cursor: pointer; transition: all 0.2s; font-size: 14px;
    }
    .yanji-time-slot:hover:not(.disabled) { background: #333; }
    .yanji-time-slot.selected { background: #4ecdc4; color: #1a1a2e; }
    .yanji-time-slot.disabled { opacity: 0.3; cursor: not-allowed; }
    .yanji-availability { font-size: 10px; color: #888; margin-top: 2px; }
    .yanji-time-slot.selected .yanji-availability { color: #1a1a2e; }
    .yanji-time-slot.limited .yanji-availability { color: #f39c12; }

    /* Guest Selector */
    .yanji-guest-selector { display: flex; align-items: center; justify-content: center; gap: 15px; padding: 15px; }
    .yanji-guest-btn {
      width: 40px; height: 40px; border-radius: 50%; background: #333;
      border: none; color: #fff; font-size: 20px; cursor: pointer;
    }
    .yanji-guest-btn:hover { background: #444; }
    .yanji-guest-btn:disabled { opacity: 0.3; cursor: not-allowed; }
    .yanji-guest-count { font-size: 36px; font-weight: bold; color: #4ecdc4; min-width: 60px; text-align: center; }
    .yanji-guest-label { font-size: 12px; color: #888; text-align: center; margin-top: 5px; }
    #yanji-availability-msg { text-align: center; margin-top: 15px; font-size: 13px; color: #4ecdc4; }

    /* Form Fields */
    .yanji-form-group { margin-bottom: 15px; }
    .yanji-form-group label { display: block; margin-bottom: 6px; color: #aaa; font-size: 13px; }
    .yanji-form-group input, .yanji-form-group textarea {
      width: 100%; padding: 12px; background: #1a1a2e; border: 1px solid #333;
      border-radius: 6px; color: #fff; font-size: 15px;
    }
    .yanji-form-group input:focus, .yanji-form-group textarea:focus { outline: none; border-color: #4ecdc4; }
    .yanji-form-group textarea { resize: vertical; min-height: 60px; }
    .yanji-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    @media (max-width: 500px) { .yanji-form-row { grid-template-columns: 1fr; } }

    /* Summary */
    .yanji-summary { background: #1a1a2e; border-radius: 8px; padding: 15px; }
    .yanji-summary-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #333; }
    .yanji-summary-row:last-child { border-bottom: none; }
    .yanji-summary-label { color: #888; font-size: 13px; }
    .yanji-summary-value { font-weight: bold; font-size: 13px; }

    /* Buttons */
    .yanji-btn {
      padding: 12px 24px; border: none; border-radius: 6px;
      cursor: pointer; font-size: 14px; transition: all 0.2s;
    }
    .yanji-btn-primary { background: #4ecdc4; color: #1a1a2e; }
    .yanji-btn-primary:hover { background: #3dbdb5; }
    .yanji-btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }
    .yanji-btn-secondary { background: #444; color: #fff; }
    .yanji-btn-secondary:hover { background: #555; }
    .yanji-btn-group { display: flex; justify-content: space-between; margin-top: 20px; }

    /* Success */
    .yanji-success { text-align: center; padding: 20px; }
    .yanji-success-icon {
      width: 60px; height: 60px; background: #4ecdc4; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 15px; font-size: 30px; color: #1a1a2e;
    }
    .yanji-success-title { font-size: 20px; margin-bottom: 8px; }
    .yanji-success-msg { color: #888; margin-bottom: 20px; font-size: 14px; }
    .yanji-confirm-code {
      background: #1a1a2e; padding: 12px 20px; border-radius: 6px;
      display: inline-block; font-size: 18px; font-family: monospace; margin-bottom: 20px;
    }

    /* Hidden steps */
    .yanji-step-content { display: none; }
    .yanji-step-content.active { display: block; }

    /* Loading */
    .yanji-loading { display: flex; justify-content: center; padding: 20px; }
    .yanji-loading::after {
      content: ''; width: 24px; height: 24px; border: 3px solid #333;
      border-top-color: #4ecdc4; border-radius: 50%; animation: yanji-spin 1s linear infinite;
    }
    @keyframes yanji-spin { to { transform: rotate(360deg); } }

    /* Toast */
    .yanji-toast {
      position: fixed; bottom: 20px; right: 20px; padding: 12px 20px;
      background: #4ecdc4; color: #1a1a2e; border-radius: 6px;
      display: none; z-index: 100000; font-size: 14px;
    }
    .yanji-toast.error { background: #ff6b6b; color: #fff; }
    .yanji-toast.active { display: block; }

    .yanji-note { color: #888; font-size: 12px; text-align: center; margin-top: 10px; }
  `;

  // ==================== HTML Template ====================
  function getPopupHTML() {
    return `
      <div class="yanji-popup-overlay" onclick="YanjiReservation.closeOnOverlay(event)">
        <div class="yanji-popup" onclick="event.stopPropagation()">
          <div class="yanji-popup-header">
            <div style="display:flex;align-items:center;">
              <div class="yanji-lang-switch">
                <button class="yanji-lang-btn" data-lang="en">EN</button>
                <button class="yanji-lang-btn" data-lang="ko">KO</button>
              </div>
              <div class="yanji-popup-title" data-i18n="title">${t('title')}</div>
            </div>
            <button class="yanji-popup-close" onclick="YanjiReservation.close()">&times;</button>
          </div>
          <div class="yanji-popup-body">
            <!-- Steps Indicator -->
            <div class="yanji-steps">
              <div class="yanji-step active" data-step="1">
                <div class="yanji-step-number">1</div>
                <span class="yanji-step-label" data-i18n="steps.dateTime">${t('steps.dateTime')}</span>
              </div>
              <div class="yanji-step-divider"></div>
              <div class="yanji-step" data-step="2">
                <div class="yanji-step-number">2</div>
                <span class="yanji-step-label" data-i18n="steps.guests">${t('steps.guests')}</span>
              </div>
              <div class="yanji-step-divider"></div>
              <div class="yanji-step" data-step="3">
                <div class="yanji-step-number">3</div>
                <span class="yanji-step-label" data-i18n="steps.details">${t('steps.details')}</span>
              </div>
              <div class="yanji-step-divider"></div>
              <div class="yanji-step" data-step="4">
                <div class="yanji-step-number">4</div>
                <span class="yanji-step-label" data-i18n="steps.confirm">${t('steps.confirm')}</span>
              </div>
            </div>

            <!-- Step 1: Date & Time -->
            <div class="yanji-step-content active" id="yanji-step-1">
              <div class="yanji-card">
                <h3 data-i18n="reservation.selectDate">${t('reservation.selectDate')}</h3>
                <div class="yanji-date-nav">
                  <button onclick="YanjiReservation.changeWeek(-1)">&lt;</button>
                  <span class="yanji-month-label" id="yanji-month-label"></span>
                  <button onclick="YanjiReservation.changeWeek(1)">&gt;</button>
                </div>
                <div class="yanji-date-grid" id="yanji-date-grid"></div>
              </div>
              <div class="yanji-card" id="yanji-time-section" style="display:none;">
                <h3 data-i18n="reservation.selectTime">${t('reservation.selectTime')}</h3>
                <div id="yanji-time-blocks"></div>
              </div>
              <div class="yanji-btn-group">
                <div></div>
                <button class="yanji-btn yanji-btn-primary" onclick="YanjiReservation.goToStep(2)" id="yanji-btn-step1" disabled data-i18n="common.next">${t('common.next')}</button>
              </div>
            </div>

            <!-- Step 2: Guests -->
            <div class="yanji-step-content" id="yanji-step-2">
              <div class="yanji-card">
                <h3 data-i18n="reservation.howMany">${t('reservation.howMany')}</h3>
                <div class="yanji-guest-selector">
                  <button class="yanji-guest-btn" onclick="YanjiReservation.changeGuests(-1)">-</button>
                  <div class="yanji-guest-count" id="yanji-guest-count">2</div>
                  <button class="yanji-guest-btn" onclick="YanjiReservation.changeGuests(1)">+</button>
                </div>
                <div class="yanji-guest-label" data-i18n="reservation.guestsLabel">${t('reservation.guestsLabel')}</div>
                <p id="yanji-availability-msg"></p>
              </div>
              <div class="yanji-btn-group">
                <button class="yanji-btn yanji-btn-secondary" onclick="YanjiReservation.goToStep(1)" data-i18n="common.back">${t('common.back')}</button>
                <button class="yanji-btn yanji-btn-primary" onclick="YanjiReservation.goToStep(3)" id="yanji-btn-step2" data-i18n="common.next">${t('common.next')}</button>
              </div>
            </div>

            <!-- Step 3: Contact Details -->
            <div class="yanji-step-content" id="yanji-step-3">
              <div class="yanji-card">
                <h3 data-i18n="reservation.contactInfo">${t('reservation.contactInfo')}</h3>
                <div class="yanji-form-row">
                  <div class="yanji-form-group">
                    <label data-i18n="reservation.name">${t('reservation.name')}</label>
                    <input type="text" id="yanji-customer-name" required>
                  </div>
                  <div class="yanji-form-group">
                    <label data-i18n="reservation.phone">${t('reservation.phone')}</label>
                    <input type="tel" id="yanji-customer-phone" required placeholder="+44 7123 456789">
                  </div>
                </div>
                <div class="yanji-form-group">
                  <label data-i18n="reservation.notes">${t('reservation.notes')}</label>
                  <textarea id="yanji-customer-notes" data-i18n-placeholder="reservation.notesPlaceholder" placeholder="${t('reservation.notesPlaceholder')}"></textarea>
                </div>
                <div class="yanji-form-group">
                  <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" id="yanji-sms-consent" checked>
                    <span data-i18n="reservation.smsConsent">${t('reservation.smsConsent')}</span>
                  </label>
                </div>
              </div>
              <div class="yanji-btn-group">
                <button class="yanji-btn yanji-btn-secondary" onclick="YanjiReservation.goToStep(2)" data-i18n="common.back">${t('common.back')}</button>
                <button class="yanji-btn yanji-btn-primary" onclick="YanjiReservation.goToStep(4)" id="yanji-btn-step3" data-i18n="common.next">${t('common.next')}</button>
              </div>
            </div>

            <!-- Step 4: Confirm -->
            <div class="yanji-step-content" id="yanji-step-4">
              <div class="yanji-card">
                <h3 data-i18n="reservation.confirmTitle">${t('reservation.confirmTitle')}</h3>
                <div class="yanji-summary">
                  <div class="yanji-summary-row">
                    <span class="yanji-summary-label" data-i18n="reservation.date">${t('reservation.date')}</span>
                    <span class="yanji-summary-value" id="yanji-summary-date"></span>
                  </div>
                  <div class="yanji-summary-row">
                    <span class="yanji-summary-label" data-i18n="reservation.time">${t('reservation.time')}</span>
                    <span class="yanji-summary-value" id="yanji-summary-time"></span>
                  </div>
                  <div class="yanji-summary-row">
                    <span class="yanji-summary-label" data-i18n="reservation.guests">${t('reservation.guests')}</span>
                    <span class="yanji-summary-value" id="yanji-summary-guests"></span>
                  </div>
                  <div class="yanji-summary-row">
                    <span class="yanji-summary-label" data-i18n="reservation.name">${t('reservation.name')}</span>
                    <span class="yanji-summary-value" id="yanji-summary-name"></span>
                  </div>
                  <div class="yanji-summary-row">
                    <span class="yanji-summary-label" data-i18n="reservation.phone">${t('reservation.phone')}</span>
                    <span class="yanji-summary-value" id="yanji-summary-phone"></span>
                  </div>
                </div>
                <p class="yanji-note" data-i18n="reservation.tableAssignNote">${t('reservation.tableAssignNote')}</p>
              </div>
              <div class="yanji-btn-group">
                <button class="yanji-btn yanji-btn-secondary" onclick="YanjiReservation.goToStep(3)" data-i18n="common.back">${t('common.back')}</button>
                <button class="yanji-btn yanji-btn-primary" onclick="YanjiReservation.submit()" id="yanji-btn-submit" data-i18n="reservation.confirm">${t('reservation.confirm')}</button>
              </div>
            </div>

            <!-- Step 5: Success -->
            <div class="yanji-step-content" id="yanji-step-5">
              <div class="yanji-card">
                <div class="yanji-success">
                  <div class="yanji-success-icon">&#10003;</div>
                  <h3 class="yanji-success-title" data-i18n="reservation.successTitle">${t('reservation.successTitle')}</h3>
                  <p class="yanji-success-msg" data-i18n="reservation.successMessage">${t('reservation.successMessage')}</p>
                  <div class="yanji-confirm-code" id="yanji-confirmation-number"></div>
                  <div class="yanji-summary" style="text-align:left;">
                    <div class="yanji-summary-row">
                      <span class="yanji-summary-label" data-i18n="reservation.date">${t('reservation.date')}</span>
                      <span class="yanji-summary-value" id="yanji-final-date"></span>
                    </div>
                    <div class="yanji-summary-row">
                      <span class="yanji-summary-label" data-i18n="reservation.time">${t('reservation.time')}</span>
                      <span class="yanji-summary-value" id="yanji-final-time"></span>
                    </div>
                    <div class="yanji-summary-row">
                      <span class="yanji-summary-label" data-i18n="reservation.guests">${t('reservation.guests')}</span>
                      <span class="yanji-summary-value" id="yanji-final-guests"></span>
                    </div>
                    <div class="yanji-summary-row">
                      <span class="yanji-summary-label" data-i18n="reservation.table">${t('reservation.table')}</span>
                      <span class="yanji-summary-value" id="yanji-final-table"></span>
                    </div>
                  </div>
                  <div style="margin-top:20px;">
                    <button class="yanji-btn yanji-btn-secondary" onclick="YanjiReservation.close()" data-i18n="reservation.close">${t('reservation.close')}</button>
                    <button class="yanji-btn yanji-btn-primary" onclick="YanjiReservation.newReservation()" style="margin-left:10px;" data-i18n="reservation.newReservation">${t('reservation.newReservation')}</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="yanji-toast" id="yanji-toast"></div>
    `;
  }

  // ==================== API Calls ====================
  async function fetchConfig() {
    try {
      const res = await fetch(`${API_BASE}/config`);
      const data = await res.json();
      operationHours = data.operationHours;
    } catch (e) {
      console.error('Failed to fetch config:', e);
      operationHours = {
        default: { open: '12:00', close: '22:30' },
        weekly: {
          monday: { open: '12:00', close: '22:30' },
          tuesday: { open: '12:00', close: '22:30' },
          wednesday: { open: '12:00', close: '22:30' },
          thursday: { open: '12:00', close: '22:30' },
          friday: { open: '12:00', close: '22:30' },
          saturday: { open: '12:00', close: '22:30' },
          sunday: { open: '12:00', close: '22:30' }
        },
        blocks: [
          { name: 'lunch', start: '12:00', end: '15:00' },
          { name: 'dinner', start: '17:30', end: '22:30' }
        ]
      };
    }
  }

  async function fetchAvailableSlots(date, guestCount) {
    const key = `${date}-${guestCount}`;
    if (availableSlots[key]) return availableSlots[key];

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

    if (operationHours.override && operationHours.override[dateStr]) {
      return operationHours.override[dateStr];
    }
    if (operationHours.weekly && operationHours.weekly[dayName]) {
      return operationHours.weekly[dayName];
    }
    return operationHours.default;
  }

  function renderDateGrid() {
    const grid = document.getElementById('yanji-date-grid');
    if (!grid) return;
    grid.innerHTML = '';

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    const dayNames = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];

    const monthFormat = { month: 'long', year: 'numeric' };
    const monthLabel = document.getElementById('yanji-month-label');
    if (monthLabel) {
      monthLabel.textContent = weekStart.toLocaleDateString(currentLang === 'ko' ? 'ko-KR' : 'en-US', monthFormat);
    }

    for (let i = 0; i < 7; i++) {
      const date = new Date(weekStart);
      date.setDate(date.getDate() + i);

      const dateStr = date.toISOString().split('T')[0];
      const hours = getDayHours(date);
      const isPast = date < today;
      const isClosed = hours?.closed || isPast;
      const isSelected = reservation.date === dateStr;

      const cell = document.createElement('div');
      cell.className = `yanji-date-cell ${isClosed ? 'disabled' : ''} ${isSelected ? 'selected' : ''}`;
      cell.innerHTML = `
        <div class="yanji-day-name">${t('days.' + dayNames[date.getDay()])}</div>
        <div class="yanji-day-num">${date.getDate()}</div>
      `;

      if (!isClosed) {
        cell.onclick = () => selectDate(dateStr);
      }

      grid.appendChild(cell);
    }
  }

  async function renderTimeSlots() {
    const container = document.getElementById('yanji-time-blocks');
    if (!container) return;
    container.innerHTML = '<div class="yanji-loading"></div>';

    if (!reservation.date) {
      document.getElementById('yanji-time-section').style.display = 'none';
      return;
    }

    document.getElementById('yanji-time-section').style.display = 'block';

    const date = new Date(reservation.date);
    const hours = getDayHours(date);

    if (hours?.closed) {
      container.innerHTML = `<div style="text-align:center;color:#888;padding:20px;">${t('reservation.closed')}</div>`;
      return;
    }

    const slots = await fetchAvailableSlots(reservation.date, reservation.guests);

    const timeAvailability = {};
    slots.forEach(slot => {
      slot.slots.forEach(time => {
        timeAvailability[time] = (timeAvailability[time] || 0) + 1;
      });
    });

    container.innerHTML = '';

    const blocks = operationHours?.blocks || [
      { name: 'lunch', start: '12:00', end: '15:00' },
      { name: 'dinner', start: '17:30', end: '22:30' }
    ];

    blocks.forEach(block => {
      const section = document.createElement('div');

      const title = document.createElement('div');
      title.className = 'yanji-block-title';
      const blockName = block.name === '점심' ? 'lunch' : block.name === '저녁' ? 'dinner' : block.name;
      title.textContent = t('blocks.' + blockName);
      section.appendChild(title);

      const grid = document.createElement('div');
      grid.className = 'yanji-time-grid';

      const [startH, startM] = block.start.split(':').map(Number);
      const [endH, endM] = block.end.split(':').map(Number);
      const startMin = startH * 60 + startM;
      const endMin = endH * 60 + endM;

      const lastResTime = hours?.lastReservation;
      const [lastH, lastM] = lastResTime ? lastResTime.split(':').map(Number) : [23, 59];
      const lastResMin = lastH * 60 + lastM;

      for (let min = startMin; min < endMin && min <= lastResMin; min += 30) {
        const h = Math.floor(min / 60);
        const m = min % 60;
        const time = `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}`;

        const now = new Date();
        const slotDate = new Date(reservation.date);
        slotDate.setHours(h, m);
        const isPast = slotDate < now;

        const availCount = timeAvailability[time] || 0;
        const isAvailable = availCount > 0 && !isPast;
        const isLimited = availCount > 0 && availCount <= 2;

        const slot = document.createElement('div');
        slot.className = `yanji-time-slot ${!isAvailable ? 'disabled' : ''} ${isLimited ? 'limited' : ''} ${reservation.time === time ? 'selected' : ''}`;

        let availText = t('reservation.available');
        if (!isAvailable) {
          availText = isPast ? '-' : t('reservation.unavailable');
        } else if (isLimited) {
          availText = t('reservation.limited');
        }

        slot.innerHTML = `<div>${time}</div><div class="yanji-availability">${availText}</div>`;

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
    const msg = document.getElementById('yanji-availability-msg');
    if (!msg) return;

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

    const summaryDate = document.getElementById('yanji-summary-date');
    const summaryTime = document.getElementById('yanji-summary-time');
    const summaryGuests = document.getElementById('yanji-summary-guests');
    const summaryName = document.getElementById('yanji-summary-name');
    const summaryPhone = document.getElementById('yanji-summary-phone');

    if (summaryDate) summaryDate.textContent = dateStr;
    if (summaryTime) summaryTime.textContent = reservation.time;
    if (summaryGuests) summaryGuests.textContent = reservation.guests + guestLabel;
    if (summaryName) summaryName.textContent = reservation.name;
    if (summaryPhone) summaryPhone.textContent = reservation.phone;
  }

  // ==================== Actions ====================
  function selectDate(dateStr) {
    reservation.date = dateStr;
    reservation.time = null;
    renderDateGrid();
    renderTimeSlots();
    updateNextButton();
  }

  function selectTime(time) {
    reservation.time = time;
    renderTimeSlots();
    updateNextButton();
  }

  function updateNextButton() {
    const btn = document.getElementById('yanji-btn-step1');
    if (btn) btn.disabled = !reservation.date || !reservation.time;
  }

  function applyTranslations() {
    if (!popupContainer) return;

    popupContainer.querySelectorAll('[data-i18n]').forEach(el => {
      el.textContent = t(el.dataset.i18n);
    });
    popupContainer.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
      el.placeholder = t(el.dataset.i18nPlaceholder);
    });
    popupContainer.querySelectorAll('.yanji-lang-btn').forEach(btn => {
      btn.classList.toggle('active', btn.dataset.lang === currentLang);
    });

    renderDateGrid();
    if (reservation.date) renderTimeSlots();
    updateAvailabilityMessage();
  }

  function showToast(message, isError = false) {
    const toast = document.getElementById('yanji-toast');
    if (!toast) return;
    toast.textContent = message;
    toast.className = 'yanji-toast active' + (isError ? ' error' : '');
    setTimeout(() => toast.classList.remove('active'), 3000);
  }

  // ==================== Public API ====================
  window.YanjiReservation = {
    open: async function() {
      // Check if reservations are enabled
      if (window.YANJI_CONFIG?.RESERVATIONS_ENABLED === false) {
        this.showUnavailable();
        return;
      }

      // Reset state
      currentStep = 1;
      weekStart = new Date();
      weekStart.setDate(weekStart.getDate() - weekStart.getDay());
      reservation = { date: null, time: null, guests: 2, name: '', phone: '', notes: '' };
      availableSlots = {};

      // Inject styles if not already done
      if (!document.getElementById('yanji-reservation-styles')) {
        const styleEl = document.createElement('style');
        styleEl.id = 'yanji-reservation-styles';
        styleEl.textContent = styles;
        document.head.appendChild(styleEl);
      }

      // Create popup container
      popupContainer = document.createElement('div');
      popupContainer.id = 'yanji-reservation-popup';
      popupContainer.innerHTML = getPopupHTML();
      document.body.appendChild(popupContainer);

      // Prevent body scroll
      document.body.style.overflow = 'hidden';

      // Set up language buttons
      popupContainer.querySelectorAll('.yanji-lang-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          currentLang = this.dataset.lang;
          localStorage.setItem('yanji-lang', currentLang);
          applyTranslations();
        });
      });

      // Apply initial translations
      applyTranslations();

      // Fetch config and render
      await fetchConfig();
      renderDateGrid();
    },

    showUnavailable: function() {
      // Inject styles if not already done
      if (!document.getElementById('yanji-reservation-styles')) {
        const styleEl = document.createElement('style');
        styleEl.id = 'yanji-reservation-styles';
        styleEl.textContent = styles;
        document.head.appendChild(styleEl);
      }

      const unavailableTitle = currentLang === 'ko' ? '예약 일시 중단' : 'Reservations Temporarily Unavailable';
      const unavailableMsg = currentLang === 'ko' ? '현재 온라인 예약이 불가능합니다. 전화로 예약해 주세요.' : 'Online reservations are currently not available. Please call us to make a reservation.';
      const closeText = currentLang === 'ko' ? '닫기' : 'Close';

      popupContainer = document.createElement('div');
      popupContainer.id = 'yanji-reservation-popup';
      popupContainer.innerHTML = `
        <div class="yanji-popup-overlay" onclick="YanjiReservation.closeOnOverlay(event)">
          <div class="yanji-popup" onclick="event.stopPropagation()" style="max-width:500px;">
            <div class="yanji-popup-header">
              <div class="yanji-popup-title">${unavailableTitle}</div>
              <button class="yanji-popup-close" onclick="YanjiReservation.close()">&times;</button>
            </div>
            <div class="yanji-popup-body" style="text-align:center;padding:40px 20px;">
              <div style="font-size:64px;margin-bottom:20px;">🚧</div>
              <p style="color:#888;font-size:16px;line-height:1.6;margin-bottom:30px;">${unavailableMsg}</p>
              <div style="margin-bottom:20px;">
                <p style="color:#4ecdc4;font-size:18px;">Yanji Restaurant</p>
                <p style="color:#888;font-size:14px;margin-top:10px;">📍 153 Bethnal Green Road, London E2 7DG</p>
                <p style="color:#888;font-size:14px;">📞 +447910754793</p>
              </div>
              <button class="yanji-btn yanji-btn-secondary" onclick="YanjiReservation.close()">${closeText}</button>
            </div>
          </div>
        </div>
      `;
      document.body.appendChild(popupContainer);
      document.body.style.overflow = 'hidden';
    },

    close: function() {
      if (popupContainer) {
        popupContainer.remove();
        popupContainer = null;
      }
      document.body.style.overflow = '';
      // Re-enable fullPage.js scrolling
      if (window.jQuery && window.jQuery.fn && window.jQuery.fn.fullpage) {
        window.jQuery.fn.fullpage.setAllowScrolling(true);
      }
    },

    closeOnOverlay: function(event) {
      if (event.target.classList.contains('yanji-popup-overlay')) {
        this.close();
      }
    },

    changeWeek: function(delta) {
      weekStart.setDate(weekStart.getDate() + (delta * 7));
      renderDateGrid();
    },

    changeGuests: async function(delta) {
      const newCount = reservation.guests + delta;
      if (newCount >= 1 && newCount <= 10) {
        reservation.guests = newCount;
        const guestCountEl = document.getElementById('yanji-guest-count');
        if (guestCountEl) guestCountEl.textContent = newCount;

        if (reservation.date) {
          await renderTimeSlots();

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
    },

    goToStep: function(step) {
      if (step === 4) {
        const nameInput = document.getElementById('yanji-customer-name');
        const phoneInput = document.getElementById('yanji-customer-phone');
        const notesInput = document.getElementById('yanji-customer-notes');
        const smsConsentInput = document.getElementById('yanji-sms-consent');

        reservation.name = nameInput ? nameInput.value.trim() : '';
        reservation.phone = phoneInput ? phoneInput.value.trim() : '';
        reservation.notes = notesInput ? notesInput.value.trim() : '';
        reservation.smsConsent = smsConsentInput ? smsConsentInput.checked : false;

        if (!reservation.name || !reservation.phone) {
          showToast(t('reservation.fillRequired'), true);
          return;
        }

        updateSummary();
      }

      currentStep = step;

      // Update step indicators
      if (popupContainer) {
        popupContainer.querySelectorAll('.yanji-step').forEach((el, i) => {
          el.classList.remove('active', 'completed');
          if (i + 1 < step) el.classList.add('completed');
          if (i + 1 === step) el.classList.add('active');
        });

        // Show/hide step content
        popupContainer.querySelectorAll('.yanji-step-content').forEach(el => el.classList.remove('active'));
        const stepEl = document.getElementById('yanji-step-' + step);
        if (stepEl) stepEl.classList.add('active');
      }

      if (step === 2) updateAvailabilityMessage();
    },

    submit: async function() {
      const btn = document.getElementById('yanji-btn-submit');
      if (btn) {
        btn.disabled = true;
        btn.textContent = '...';
      }

      try {
        const freshSlots = await fetchAvailableSlots(reservation.date, reservation.guests);

        const key = `${reservation.date}-${reservation.guests}`;
        delete availableSlots[key];
        availableSlots[key] = freshSlots;

        const availableTable = freshSlots?.find(s => s.slots.includes(reservation.time));

        if (!availableTable) {
          await renderTimeSlots();
          this.goToStep(1);
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

        const confirmEl = document.getElementById('yanji-confirmation-number');
        const finalDate = document.getElementById('yanji-final-date');
        const finalTime = document.getElementById('yanji-final-time');
        const finalGuests = document.getElementById('yanji-final-guests');
        const finalTable = document.getElementById('yanji-final-table');

        if (confirmEl) confirmEl.textContent = result.reservationId.slice(0, 8).toUpperCase();
        if (finalDate) finalDate.textContent = dateStr;
        if (finalTime) finalTime.textContent = `${result.startTime} - ${result.endTime}`;
        if (finalGuests) finalGuests.textContent = result.guestCount + guestLabel;
        if (finalTable) finalTable.textContent = result.tablePart || result.tableId;

        this.goToStep(5);
      } catch (error) {
        showToast(error.message, true);
        if (btn) {
          btn.disabled = false;
          btn.textContent = t('reservation.confirm');
        }
      }
    },

    newReservation: function() {
      reservation = { date: null, time: null, guests: 2, name: '', phone: '', notes: '', smsConsent: true };
      availableSlots = {};

      const nameInput = document.getElementById('yanji-customer-name');
      const phoneInput = document.getElementById('yanji-customer-phone');
      const notesInput = document.getElementById('yanji-customer-notes');
      const smsConsentInput = document.getElementById('yanji-sms-consent');
      const guestCountEl = document.getElementById('yanji-guest-count');
      const availMsg = document.getElementById('yanji-availability-msg');

      if (nameInput) nameInput.value = '';
      if (phoneInput) phoneInput.value = '';
      if (notesInput) notesInput.value = '';
      if (smsConsentInput) smsConsentInput.checked = true;
      if (guestCountEl) guestCountEl.textContent = '2';
      if (availMsg) availMsg.textContent = '';

      currentStep = 1;
      if (popupContainer) {
        popupContainer.querySelectorAll('.yanji-step').forEach((el, i) => {
          el.classList.remove('active', 'completed');
          if (i === 0) el.classList.add('active');
        });
        popupContainer.querySelectorAll('.yanji-step-content').forEach(el => el.classList.remove('active'));
        const step1 = document.getElementById('yanji-step-1');
        if (step1) step1.classList.add('active');
      }

      renderDateGrid();
      const timeSection = document.getElementById('yanji-time-section');
      if (timeSection) timeSection.style.display = 'none';
      updateNextButton();
    }
  };
})();
