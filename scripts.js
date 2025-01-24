const sendBtn = document.getElementById('send-btn');
const userInput = document.getElementById('user-input');
const messages = document.getElementById('messages');

sendBtn.addEventListener('click', async () => {
    // Get the user's input and trim whitespace
    const message = userInput.value.trim();
    if (!message) {
        // Do nothing if the input is empty
        return;
    }

    // Display the user's message
    messages.innerHTML += `<div class="user-message">${message}</div>`;
    userInput.value = ''; // Clear the input field

    try {
        // Send the message to the backend
        const response = await fetch('chatbot.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `message=${encodeURIComponent(message)}`,
        });

        // Check if the response is okay
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        // Parse the JSON response
        const data = await response.json();

        // Handle cases where the backend returns an error
        if (data.error) {
            throw new Error(data.error);
        }

        // Get the bot's reply and display it
        const botReply = data.choices[0]?.text.trim() || "No response.";
        messages.innerHTML += `<div class="bot-message">${botReply}</div>`;
    } catch (error) {
        // Handle any errors and display an error message
        console.error("Error:", error.message);
        messages.innerHTML += `<div class="bot-message error">Oops! Something went wrong: ${error.message}</div>`;
    }
});
