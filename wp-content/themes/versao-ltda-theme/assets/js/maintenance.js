(function () {
  function pad2(n) {
    return String(n).padStart(2, '0');
  }

  function parseLaunchDate(value) {
    // Expected: "YYYY-mm-dd HH:MM:SS" (site timezone).
    // Use Date parsing by converting to ISO-ish format.
    var v = (value || '').trim();
    if (!v) return null;

    // Replace space between date/time with 'T' and assume local timezone.
    // Also handle already-ISO input.
    var iso = v.replace(' ', 'T');

    var d = new Date(iso);
    if (!isFinite(d.getTime())) {
      // Fallback for browsers that can't parse.
      // Try YYYY-mm-ddTHH:MM:SS
      d = new Date(v.replace(' ', 'T'));
    }

    return isFinite(d.getTime()) ? d : null;
  }

  function renderCountdown(el, targetDate) {
    if (!el || !targetDate) return;

    function update() {
      var now = new Date();
      var diff = targetDate.getTime() - now.getTime();

      if (diff <= 0) {
        el.textContent = 'Lançamento em breve!';
        return;
      }

      var sec = Math.floor(diff / 1000);
      var days = Math.floor(sec / 86400);
      sec -= days * 86400;
      var hours = Math.floor(sec / 3600);
      sec -= hours * 3600;
      var mins = Math.floor(sec / 60);
      sec -= mins * 60;

      el.innerHTML =
        '<strong>' + days + '</strong> dias ' +
        '<strong>' + pad2(hours) + '</strong> horas ' +
        '<strong>' + pad2(mins) + '</strong> minutos ' +
        '<strong>' + pad2(sec) + '</strong> segundos';
    }

    update();
    setInterval(update, 1000);
  }

  function setBackground() {
    var body = document.body;
    if (!body) return;

    var bgUrl = body.getAttribute('data-bg');
    var bg = document.querySelector('.vltda-maintenance__bg');

    if (bg && bgUrl) {
      bg.style.backgroundImage = 'url(' + bgUrl + ')';
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    setBackground();

    var counterEl = document.querySelector('.vltda-maintenance__counter');
    if (counterEl) {
      var value = counterEl.getAttribute('data-launch-date');
      var target = parseLaunchDate(value);
      renderCountdown(counterEl, target);
    }
  });
})();

