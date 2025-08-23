document.addEventListener('DOMContentLoaded', function () {
    const userList = document.getElementById('userList');
    const chatBox = document.getElementById('chatBox');
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    let currentUser = null;

    function loadUsers() {
        fetch('fetch_users.php')
            .then(res => res.json())
            .then(users => {
                userList.innerHTML = '';
                users.forEach(u => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item d-flex justify-content-between align-items-center';
                    li.textContent = u.name;
                    const badge = document.createElement('span');
                    badge.className = 'badge rounded-pill ' + (u.online ? 'bg-success' : 'bg-secondary');
                    badge.textContent = u.online ? 'Online' : 'Offline';
                    li.appendChild(badge);
                    li.onclick = () => {
                        currentUser = u.id;
                        loadMessages();
                    };
                    userList.appendChild(li);
                });
            });
    }

    function loadMessages() {
        if (!currentUser) return;
        fetch('fetch_messages.php?user_id=' + currentUser)
            .then(res => res.json())
            .then(msgs => {
                chatBox.innerHTML = '';
                msgs.forEach(m => {
                    const div = document.createElement('div');
                    div.className = (m.sender_id == myId ? 'text-end mb-2' : 'text-start mb-2');
                    div.innerHTML = '<span class="badge bg-' + (m.sender_id == myId ? 'primary' : 'light text-dark') + '">' + m.message + '</span>';
                    chatBox.appendChild(div);
                });
                chatBox.scrollTop = chatBox.scrollHeight;
            });
    }

    function sendMessage() {
        const text = messageInput.value.trim();
        if (!text || !currentUser) return;
        const fd = new FormData();
        fd.append('receiver_id', currentUser);
        fd.append('message', text);
        fetch('send_message.php', { method: 'POST', body: fd })
            .then(res => res.text())
            .then(() => {
                messageInput.value = '';
                loadMessages();
            });
    }

    sendBtn.addEventListener('click', sendMessage);
    messageInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    function updateStatus() {
        fetch('update_last_active.php');
    }

    setInterval(() => {
        loadUsers();
        if (currentUser) loadMessages();
        updateStatus();
    }, 5000);

    loadUsers();
    updateStatus();
});
