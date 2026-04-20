const logout = document.getElementById("logout-button");

logout.addEventListener("click", async function () {
  await fetch("http://localhost:8080/logout.php", {
    method: "POST",
    headers: { Authorization: "Bearer " + token },
  });

  window.location.href = "login.html";
});
