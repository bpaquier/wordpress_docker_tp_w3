const $detailsBtn = document.querySelectorAll("[data-detail]");
const $detailsContent = document.querySelectorAll("[data-detail-content]");
if ($detailsBtn.length > 0) {
  $detailsBtn.forEach(($btn) => {
    $btn.addEventListener("click", (e) => {
      e.preventDefault();
      const detail = $btn.getAttribute("data-detail");
      $detailsBtn.forEach(($btn) => {
        if ($btn.getAttribute("data-detail") === detail) {
          $btn.classList.add("active");
        } else {
          $btn.classList.remove("active");
        }
      });

      $detailsContent.forEach(($content) => {
        if ($content.getAttribute("data-detail-content") === detail) {
          $content.classList.add("active");
        } else {
          $content.classList.remove("active");
        }
      });
    });
  });
}
