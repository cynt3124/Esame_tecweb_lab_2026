window.addEventListener("load", function () {
    const loader = document.getElementById("page-loader");
    loader.classList.add("hidden");
    
    setTimeout(() => loader.remove(), 500);
});