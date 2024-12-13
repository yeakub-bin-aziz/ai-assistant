document.addEventListener("DOMContentLoaded", () => {
    const chatBox = document.getElementById("chatBox");
    appendMessage("ai", "Hi, I am Yeakub's AI Assistant. How may I help you?");
});

document.getElementById("sendMessage").addEventListener("click", sendMessage);

function sendMessage() {
    const userMessage = document.getElementById("userMessage").value.trim();
    if (!userMessage) return;

    appendMessage("user", userMessage);

    const typingIndicator = document.createElement("div");
    typingIndicator.classList.add("typing");
    const typingGif = document.createElement("img");
    typingGif.src = "typing.gif";
    typingGif.alt = "Typing...";
    typingGif.style.width = "40px";
    typingIndicator.appendChild(typingGif);

    const chatBox = document.getElementById("chatBox");
    chatBox.appendChild(typingIndicator);
    chatBox.scrollTop = chatBox.scrollHeight;

    fetch("system.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ message: userMessage }),
    })
        .then(response => response.json())
        .then(data => {
            typingIndicator.remove();
            appendMessage("ai", data.response || "Error: No response received.");
        })
        .catch(() => {
            typingIndicator.remove();
            appendMessage("ai", "Error: Unable to connect to the server.");
        });

    document.getElementById("userMessage").value = "";
}

function appendMessage(sender, message) {
    const chatBox = document.getElementById("chatBox");
    const messageElement = document.createElement("div");
    messageElement.classList.add("message", sender);
    messageElement.textContent = message;
    chatBox.appendChild(messageElement);
    chatBox.scrollTop = chatBox.scrollHeight;
}
