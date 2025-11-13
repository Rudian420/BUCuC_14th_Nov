// Test the exact request being made
fetch(
  "https://script.google.com/macros/s/AKfycbyDMVagHtku27QnJhy_AeNeKJ1uzzcTRvo8H2Rh2iJjbUyj1dHr3cUYkyNwV7spxbqGYA/exec",
  {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      action: "update",
      studentId: "1000054890",
      position: "ASB",
    }),
  }
)
  .then((response) => response.text())
  .then((text) => console.log("Raw response:", text))
  .catch((error) => console.error("Error:", error));
