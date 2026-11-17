
document.addEventListener("DOMContentLoaded", () => {
  const path = location.pathname.split("/").pop();
  document.querySelectorAll("nav a").forEach(link => {
    if (link.getAttribute("href") === path) {
      link.classList.add("active");
    }
  });


  const browseBtn = document.querySelector(".hero .hero-text button");
  if (browseBtn) {
    browseBtn.addEventListener("click", () => {
      window.location.href = "cars.php";
    });
  }

  const form = document.querySelector("form");
  if (form && location.pathname.endsWith("add-car.html")) {
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      alert("Your car has been submitted!");
    });
  }
});

document.addEventListener("DOMContentLoaded", () => {
  const thumbs = document.querySelectorAll(".carousel-thumb");
  if (!thumbs.length) return;

  thumbs.forEach((el) => {
 
    el.addEventListener("mouseenter", () => {
      const idx = parseInt(el.getAttribute("data-index"), 10);
      if (!isNaN(idx) && typeof window.$ === "function") {
        window.$("#carouselExampleIndicators").carousel(idx);
      }
    });

    el.addEventListener("click", () => {
      const idx = parseInt(el.getAttribute("data-index"), 10);
      if (!isNaN(idx) && typeof window.$ === "function") {
        window.$("#carouselExampleIndicators").carousel(idx);
      }
    });
  });
});
