import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
window.Echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_REVERB_APP_KEY,      // powinna być taka sama co w .env
  wsHost: window.location.hostname,              // np. 'localhost'
  wsPort: window.location.port || 80,            // domyślnie 80 (Nginx)
  forceTLS: false,
  encrypted: false,
  disableStats: true,
  enabledTransports: ['ws', 'wss'],
});
