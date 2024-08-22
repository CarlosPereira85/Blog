document.addEventListener('DOMContentLoaded', function() {
    // Function to scroll to the bottom of the chat container
    function scrollToBottom() {
        const messageContainer = document.getElementById('message-container');
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }

    // Scroll to the bottom when the page loads
    scrollToBottom();

    // Scroll to the bottom when a new message is sent or received
    const observer = new MutationObserver(scrollToBottom);
    observer.observe(document.getElementById('message-container'), { childList: true });

    // Handle user list click
    document.getElementById('user-list').addEventListener('click', function(e) {
        if (e.target.classList.contains('user-item')) {
            const userId = e.target.getAttribute('data-user-id');
            // Update the URL to include the selected user ID
            const newUrl = new URL(window.location.href);
            newUrl.searchParams.set('user_id', userId);
            window.location.href = newUrl;
        }
    });

    // Highlight the selected user
    const selectedUserId = window.selectedUserId; // Accessing the global variable
    const userItems = document.querySelectorAll('.user-item');
    userItems.forEach(item => {
        if (item.getAttribute('data-user-id') == selectedUserId) {
            item.classList.add('selected');
        }
    });
});
