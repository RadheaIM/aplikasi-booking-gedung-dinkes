import './bootstrap';
import Alpine from 'alpinejs';

// === IMPORT DAN EXPORT FULLCALENDAR BARU ===
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';

// Export Kalender dan Plugin agar bisa diakses di window (seperti CDN)
window.FullCalendar = {
    Calendar,
    dayGridPlugin,
    timeGridPlugin,
    listPlugin,
    interactionPlugin
};

// ===========================================

window.Alpine = Alpine;
Alpine.start();