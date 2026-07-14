(function () {
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
  });
})();


