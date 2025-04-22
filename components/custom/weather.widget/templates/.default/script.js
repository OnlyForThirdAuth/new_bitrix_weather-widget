document.addEventListener('DOMContentLoaded', function() {
    var pulse = document.getElementById('pulse_open_btn');
    var widget = document.querySelector('.weather-widget');
    if (pulse && widget) {
      pulse.insertAdjacentElement('afterend', widget);
    }
  });  