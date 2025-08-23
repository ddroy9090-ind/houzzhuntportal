document.addEventListener('DOMContentLoaded', function () {
    const userList = document.getElementById('userList');
    const chatBox = document.getElementById('chatBox');
    const messageInput = document.getElementById('messageInput');
    const attachmentInput = document.getElementById('attachmentInput');
    const sendBtn = document.getElementById('sendBtn');
    let currentUser = null;

    const ws = new WebSocket('ws://' + window.location.hostname + ':8080');
    ws.onmessage = function (e) {
        const msg = JSON.parse(e.data);
        if ((msg.sender_id == myId && msg.receiver_id == currentUser) ||
            (msg.sender_id == currentUser && msg.receiver_id == myId)) {
            appendMessage(msg);
        }
    };

    function appendMessage(m) {
        const div = document.createElement('div');
        div.className = (m.sender_id == myId ? 'text-end mb-2' : 'text-start mb-2');
        let html = '';
        if (m.message) {
            html += '<div><span class="badge bg-' + (m.sender_id == myId ? 'primary' : 'light text-dark') + '">' + m.message + '</span></div>';
        }
        if (m.file_path) {
            const isImage = /(jpg|jpeg|png|gif|webp)$/i.test(m.file_path);
            if (isImage) {
                html += '<div><img src="' + m.file_path + '" class="img-fluid rounded" style="max-width:200px;"></div>';
            } else {
                html += '<div><a href="' + m.file_path + '" target="_blank">Attachment</a></div>';
            }
        }
        div.innerHTML = html;
        chatBox.appendChild(div);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

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
                        fetch('fetch_messages.php?user_id=' + currentUser)
                          .then(res => res.json())
                          .then(msgs => {
                              chatBox.innerHTML = '';
                              msgs.forEach(appendMessage);
                          });
                    };
                    userList.appendChild(li);
                });
            });
    }

    function sendMessage() {
        const text = messageInput.value.trim();
        const file = attachmentInput.files[0];
        if ((!text && !file) || !currentUser) return;

        const fd = new FormData();
        fd.append('receiver_id', currentUser);
        fd.append('message', text);
        if (file) {
            fd.append('attachment', file);
        }

        fetch('send_message.php', { method: 'POST', body: fd })
            .then(res => res.json())
            .then(msg => {
                messageInput.value = '';
                attachmentInput.value = '';
                ws.send(JSON.stringify(msg));
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
        updateStatus();
    }, 5000);

    loadUsers();
    updateStatus();
});
