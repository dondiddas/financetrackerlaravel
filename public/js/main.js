  (function () {
      const isTouch =
        "ontouchstart" in window ||
        (navigator.maxTouchPoints && navigator.maxTouchPoints > 0) ||
        (navigator.msMaxTouchPoints && navigator.msMaxTouchPoints > 0);

      document.body.classList.add(isTouch ? "touch" : "no-touch");

      const sidebar = document.getElementById("sidebar-wrapper");
      const toggleButton = document.getElementById("menu-toggle");

      toggleButton.addEventListener("click", () => {
        sidebar.classList.toggle("active");
        toggleButton.classList.toggle("active");
      });

      if (isTouch) {
        document.addEventListener(
          "click",
          (e) => {
            if (
              !sidebar.contains(e.target) &&
              !toggleButton.contains(e.target) &&
              sidebar.classList.contains("active")
            ) {
              sidebar.classList.remove("active");
              toggleButton.classList.remove("active");
            }
          },
          { passive: true }
        );
      }
    })();